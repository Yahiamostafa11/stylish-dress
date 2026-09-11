<?php

add_filter('woocommerce_get_settings_checkout', 'paymob_pixel_settings_option', 10, 2);
function paymob_pixel_settings_option($settings, $current_section)
{
    return Paymob_Pixel_Settings::paymob_pixel_settings_option( $settings, $current_section );
}

// Render Custom HTML Table
add_action('woocommerce_admin_field_custom_html', 'paymob_pixel_customization_html_option');

function paymob_pixel_customization_html_option()
{
    return Paymob_Pixel_Customization_Html::paymob_pixel_customization_html_option();
    
}
add_action('woocommerce_update_options_checkout', 'save_paymob_pixel_settings');
/**
 * Save paymob_add_gateway settings.
 *
 * @return void
 */
function save_paymob_pixel_settings()
{
    return Paymob_Save_Pixel_Settings::save_paymob_pixel_settings();
}


add_action('wp_ajax_update_pixel_data', 'update_pixel_data');
add_action('wp_ajax_nopriv_update_pixel_data', 'update_pixel_data');
function update_pixel_data()
{
    return Paymob_Update_Pixel_Data::update_pixel_data();
}

add_action('wp_ajax_create_order', 'create_order');
add_action('wp_ajax_nopriv_create_order', 'create_order');

function create_order() {
    // Check for nonce for security
    check_ajax_referer('update_checkout', 'security');
    try {
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- inside create order' );
        // Get cart data
        $cart = WC()->cart->get_cart();
        if (empty($cart)) {
        Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- cart empty' );
            wp_send_json_error(['message' => 'Cart is empty.']);
            return;
        }
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' ---------after 1st if' );
        // Create a new order
        $order = wc_create_order();

        // Add products to the order
        foreach ($cart as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $order->add_product(wc_get_product($product_id), $quantity, [
                'subtotal' => $cart_item['line_subtotal'],
                'total'    => $cart_item['line_total'],
            ]);
        }
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- after for cart ietms' );
        // Add applied coupons
        $applied_coupons = WC()->cart->get_applied_coupons();
        if (!empty($applied_coupons)) {
            foreach ($applied_coupons as $coupon_code) {
                $coupon = new WC_Coupon($coupon_code);
                $order->apply_coupon($coupon);
            }
        }

        // Add fees
        $fees = WC()->cart->get_fees();
        foreach ($fees as $fee) {
            $order->add_fee([
                'name'      => $fee->name,
                'amount'    => $fee->amount,
                'tax_class' => $fee->taxable ? $fee->tax_class : '',
            ]);
        }
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- after fees for' );
      
       // Add taxes to the order
        $cart_taxes = WC()->cart->get_cart_contents_taxes(); // Cart item taxes
        $shipping_taxes = WC()->cart->get_shipping_taxes();  // Shipping taxes

        // Combine taxes
        $all_taxes = array_replace_recursive($cart_taxes, $shipping_taxes);

        foreach ($all_taxes as $tax_rate_id => $tax_amount) {
            if ($tax_amount > 0) {
                $tax_item = new WC_Order_Item_Tax();
                $tax_item->set_rate_id($tax_rate_id); // Set the tax rate ID
                $tax_item->set_tax_total($tax_amount); // Set the total tax amount
                $tax_item->set_label(WC_Tax::get_rate_label($tax_rate_id)); // Set the tax label

                // Add tax item to the order
                $order->add_item($tax_item);
            }
        }

Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- after all tax' );
        // Add shipping
        $shipping_methods = WC()->shipping->get_packages();
        if (!empty($shipping_methods)) {
            foreach ($shipping_methods as $package) {
                foreach ($package['rates'] as $rate) {
                    $order->add_shipping($rate);
                }
            }
        }
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- after shipping' );
        // Check if customer data exists
        if (empty(WC()->customer)) {
        Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- inside wc customer' );
            wp_send_json_error(['message' => 'Billing data is not defined.']);
            return;
        }
Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- after wc customer' );
        // Add billing and shipping details
        $order->set_address([
            'first_name' => WC()->customer->get_billing_first_name(),
            'last_name'  => WC()->customer->get_billing_last_name(),
            'company'    => WC()->customer->get_billing_company(),
            'address_1'  => WC()->customer->get_billing_address_1(),
            'address_2'  => WC()->customer->get_billing_address_2(),
            'city'       => WC()->customer->get_billing_city(),
            'state'      => WC()->customer->get_billing_state(),
            'postcode'   => WC()->customer->get_billing_postcode(),
            'country'    => WC()->customer->get_billing_country(),
            'phone'      => WC()->customer->get_billing_phone(),
            'email'      => WC()->customer->get_billing_email(),
        ], 'billing');

        $order->set_address([
            'first_name' => WC()->customer->get_shipping_first_name(),
            'last_name'  => WC()->customer->get_shipping_last_name(),
            'company'    => WC()->customer->get_shipping_company(),
            'address_1'  => WC()->customer->get_shipping_address_1(),
            'address_2'  => WC()->customer->get_shipping_address_2(),
            'city'       => WC()->customer->get_shipping_city(),
            'state'      => WC()->customer->get_shipping_state(),
            'postcode'   => WC()->customer->get_shipping_postcode(),
            'country'    => WC()->customer->get_shipping_country(),
        ], 'shipping');

        // Calculate totals and save the order
        $order->calculate_totals();
        $order->save();
        Paymob_Pixel_Checkout::update_paymob_intention_with_orderID($order->get_id(),WC()->session->get('cs'), WC()->session->get('pixel_identifier'));
        Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- oid'.$order->get_id(). ' cs  '.WC()->session->get('cs').' pxl idn '. WC()->session->get('pixel_identifier') );
        $session = WC()->session;
        $session->__unset('order_id');
        $session->set( 'order_id',  WC()->session->get('pixel_identifier'));
        Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- merchant oid from session'.$session->get( 'order_id'));
        // Return success response
        wp_send_json_success([
            'message'  => 'Order created successfully!',
            'order_id' => $order->get_id(),
        ]);

    } catch (Exception $e) {
        wp_send_json_error(array('message' => 'No order created.'));
    }
}

add_action('wp_enqueue_scripts', 'enqueue_paymob_pixel_checkout');

function enqueue_paymob_pixel_checkout()
{
    if (is_checkout()) {
        return Paymob_Pixel_Checkout::enqueue_paymob_pixel_checkout();
    }
}

add_action( 'admin_enqueue_scripts', 'enqueue_paymob_pixel_styles' );
function enqueue_paymob_pixel_styles() {

    return Paymob_Pixel_Style::enqueue_paymob_pixel_styles();
}

// Register AJAX action for retrieving the order ID
add_action('wp_ajax_get_order_id_from_session', 'get_order_id_from_session');
add_action('wp_ajax_nopriv_get_order_id_from_session', 'get_order_id_from_session'); // For non-logged-in users too
function get_order_id_from_session() {
    // Verify AJAX request and get data
    check_ajax_referer('update_checkout', 'security');
    // Retrieve the order ID from the session
    $order_id = WC()->session->get('order_id');
    if ($order_id) {
        return wp_send_json_success(array('order_id' => $order_id));
    } else {
        return wp_send_json_error(array('message' => 'No order ID found in session.'));
    }
}


add_action('wp_ajax_paymob_apply_discount', 'paymob_apply_discount');
add_action('wp_ajax_nopriv_paymob_apply_discount', 'paymob_apply_discount');

function paymob_apply_discount() {
    check_ajax_referer('update_checkout', 'security');

    $original  = floatval($_POST['original'] ?? 0);
    $discount  = floatval($_POST['discount'] ?? 0);
    $final     = floatval($_POST['final_total'] ?? 0);

    if (WC()->session) {
        WC()->session->set('paymob_original_amount', $original);
        WC()->session->set('paymob_discount', $discount);
        WC()->session->set('paymob_final_total', $final);
    }

    wp_send_json_success([
        'original' => $original,
        'discount' => $discount,
        'final'    => $final,
        'session'  => [
            'orig' => WC()->session->get('paymob_original_amount'),
            'disc' => WC()->session->get('paymob_discount'),
            'final' => WC()->session->get('paymob_final_total'),
        ]
    ]);
}