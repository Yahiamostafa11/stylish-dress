<?php


/**
 * Build human-readable attributes string from real WooCommerce attributes.
 * Result example: "weight: 90 Kg : 120 Kg | size: XL | color: Off-white"
 */
function styliiiish_get_attributes_text( $product_id ) {
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        return '';
    }

    $attributes = $product->get_attributes();
    if ( empty( $attributes ) ) {
        return '';
    }

    $chunks = array();

    foreach ( $attributes as $attr ) {
        if ( is_object( $attr ) ) {
            $name    = wc_attribute_label( $attr->get_name() );
            $options = $attr->get_options();

            if ( $attr->is_taxonomy() ) {
                $terms_names = array();
                foreach ( $options as $term_id ) {
                    $term = get_term( $term_id, $attr->get_name() );
                    if ( $term && ! is_wp_error( $term ) ) {
                        $terms_names[] = $term->name;
                    }
                }
                $value = implode( ', ', $terms_names );
            } else {
                $value = implode( ', ', $options );
            }
        } else {
            // Older style arrays (just in case)
            $name  = isset( $attr['name'] ) ? $attr['name'] : '';
            $value = isset( $attr['value'] ) ? $attr['value'] : '';
        }

        $name  = trim( wp_strip_all_tags( $name ) );
        $value = trim( wp_strip_all_tags( $value ) );

        if ( $name === '' || $value === '' ) {
            continue;
        }

        $chunks[] = sprintf( '%s: %s', $name, $value );
    }

    return implode( ' | ', $chunks );
}


/**
 * Update real WooCommerce product attributes (and taxonomies)
 * based on a text string entered in the dashboard.
 */
function styliiiish_update_wc_attributes_from_string( $product_id, $raw_string ) {
    $parsed = styliiiish_parse_attributes_string( $raw_string );
    if ( empty( $parsed ) ) {
        return;
    }

    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        return;
    }

    $new_attributes = array();
    $position       = 0;

    foreach ( $parsed as $label => $vals_string ) {

        $label_clean = trim( wp_strip_all_tags( $label ) );
        if ( $label_clean === '' ) {
            continue;
        }

        // Split into values
        $values = preg_split( '/[,|]/u', $vals_string );
        $values = array_filter( array_map( 'trim', $values ) );
        if ( empty( $values ) ) {
            continue;
        }

        // Detect taxonomy
        $base_slug = wc_sanitize_taxonomy_name( $label_clean );
        $taxonomy  = wc_attribute_taxonomy_name( $base_slug );
        $is_tax    = taxonomy_exists( $taxonomy );

        if ( $is_tax ) {
            // Create or find terms
            $term_ids = array();
            foreach ( $values as $val ) {
                $term = get_term_by( 'name', $val, $taxonomy );
                if ( ! $term ) {
                    $term = wp_insert_term( $val, $taxonomy );
                    if ( is_wp_error( $term ) ) {
                        continue;
                    }
                    $term_id = $term['term_id'];
                    $term    = get_term( $term_id, $taxonomy );
                }
                if ( $term && ! is_wp_error( $term ) ) {
                    $term_ids[] = (int) $term->term_id;
                }
            }

            // Assign terms to product
            wp_set_object_terms( $product_id, $term_ids, $taxonomy, false );

            // Create attribute object
            $attr_obj = new WC_Product_Attribute();
            $attr_obj->set_id( wc_attribute_taxonomy_id_by_name( $taxonomy ) );
            $attr_obj->set_name( $taxonomy );
            $attr_obj->set_options( $term_ids );
            $attr_obj->set_position( $position );
            $attr_obj->set_visible( 1 );
            $attr_obj->set_variation( 0 );
            $new_attributes[] = $attr_obj;

        } else {
            // Custom attribute (non taxonomy)
            $attr_obj = new WC_Product_Attribute();
            $attr_obj->set_id( 0 );
            $attr_obj->set_name( $label_clean );
            $attr_obj->set_options( $values );
            $attr_obj->set_position( $position );
            $attr_obj->set_visible( 1 );
            $attr_obj->set_variation( 0 );
            $new_attributes[] = $attr_obj;
        }

        $position++;
    }

    // Apply attributes through WooCommerce API
    $product->set_attributes( $new_attributes );
    $product->save();

    wc_delete_product_transients( $product_id );
}


?>