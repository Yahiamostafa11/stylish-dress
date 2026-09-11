<?php
class Paymob_Pixel_Customization_Html
{
        public static function paymob_pixel_customization_html_option()
        {
                $pixel_settings = get_option('woocommerce_paymob-pixel_settings', array());
                ?>
                <table class="customization-settings-table">
                        <!-- Title Row -->
                        <tr class="customization-title-row">
                                <th colspan="6"><?php esc_html_e('Customization', 'paymob-woocommerce'); ?></th>
                        </tr>

                        <!-- Header Row -->
                        <tr>
                                <th><?php esc_html_e('Setting', 'paymob-woocommerce'); ?></th>
                                <th><?php esc_html_e('Value', 'paymob-woocommerce'); ?></th>
                                <th><?php esc_html_e('Setting', 'paymob-woocommerce'); ?></th>
                                <th><?php esc_html_e('Value', 'paymob-woocommerce'); ?></th>
                                <th><?php esc_html_e('Setting', 'paymob-woocommerce'); ?></th>
                                <th><?php esc_html_e('Value', 'paymob-woocommerce'); ?></th>
                        </tr>

                        <!-- Settings Rows -->
                        <tr>
                                <td><?php esc_html_e('Font Family', 'paymob-woocommerce'); ?></td>
                                <td>
                                        <select name="woocommerce_paymob_pixel_font_family">
                                                <?php
                                                $fonts = [
                                                        'Arial',
                                                        'Verdana',
                                                        'Helvetica',
                                                        'Times New Roman',
                                                        'Georgia',
                                                        'Garamond',
                                                        'Courier New',
                                                        'Brush Script MT',
                                                        'Merriweather',
                                                        'Roboto',
                                                        'Gotham',
                                                        'Cursive',
                                                        'Emoji',
                                                        'Fangsong',
                                                        'Fantasy',
                                                        'Math',
                                                        'Monospace',
                                                        'Sans-serif',
                                                        'Serif',
                                                        'Sofia',
                                                        'System-ui',
                                                        'Tahoma',
                                                        'Ui-monospace',
                                                        'Ui-sans-serif',
                                                        'Ui-serif',
                                                        'Ui-rounded'
                                                ];
                                                $selected_font = isset($pixel_settings['font_family']) ? $pixel_settings['font_family'] : 'Gotham';
                                                foreach ($fonts as $font) {
                                                        printf(
                                                                '<option value="%s"%s>%s</option>',
                                                                esc_attr($font),
                                                                selected($selected_font, $font, false),
                                                                esc_html($font)
                                                        );
                                                }
                                                ?>
                                        </select>
                                </td>
                                <td><?php esc_html_e('Font Size Label', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_size_label"
                                                value="<?php echo esc_attr($pixel_settings['font_size_label'] ?? '16'); ?>" />
                                </td>
                                <td><?php esc_html_e('Font Size Input Fields', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_size_input_fields"
                                                value="<?php echo esc_attr($pixel_settings['font_size_input_fields'] ?? '16'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Font Size Payment Button', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_size_payment_button"
                                                value="<?php echo esc_attr($pixel_settings['font_size_payment_button'] ?? '14'); ?>" />
                                </td>
                                <td><?php esc_html_e('Font Weight Label', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_weight_label"
                                                value="<?php echo esc_attr($pixel_settings['font_weight_label'] ?? '400'); ?>" />
                                </td>
                                <td><?php esc_html_e('Font Weight Input Fields', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_weight_input_fields"
                                                value="<?php echo esc_attr($pixel_settings['font_weight_input_fields'] ?? '200'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Font Weight Payment Button', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_font_weight_payment_button"
                                                value="<?php echo esc_attr($pixel_settings['font_weight_payment_button'] ?? '600'); ?>" />
                                </td>
                                <td><?php esc_html_e('Color Container', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_container"
                                                value="<?php echo esc_attr($pixel_settings['color_container'] ?? '#FFFFFF'); ?>" />
                                </td>
                                <td><?php esc_html_e('Color Border Input Fields', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_border_input_fields"
                                                value="<?php echo esc_attr($pixel_settings['color_border_input_fields'] ?? '#D0D5DD'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Color Border Payment Button', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_border_payment_button"
                                                value="<?php echo esc_attr($pixel_settings['color_border_payment_button'] ?? '#A1B8FF'); ?>" />
                                </td>
                                <td><?php esc_html_e('Radius Border', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_radius_border"
                                                value="<?php echo esc_attr($pixel_settings['radius_border'] ?? '8'); ?>" />
                                </td>
                                <td><?php esc_html_e('Color Disabled', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_disabled"
                                                value="<?php echo esc_attr($pixel_settings['color_disabled'] ?? '#A1B8FF'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Color Error', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_error"
                                                value="<?php echo esc_attr($pixel_settings['color_error'] ?? '#CC1142'); ?>" />
                                </td>
                                <td><?php esc_html_e('Color Primary', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_primary"
                                                value="<?php echo esc_attr($pixel_settings['color_primary'] ?? '#144DFF'); ?>" />
                                </td>
                                <td><?php esc_html_e('Color Input Fields', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_input_fields"
                                                value="<?php echo esc_attr($pixel_settings['color_input_fields'] ?? '#FFFFFF'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Text Color for Label', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_text_color_for_label"
                                                value="<?php echo esc_attr($pixel_settings['text_color_for_label'] ?? '#000000'); ?>" />
                                </td>
                                <td><?php esc_html_e('Text Color for Payment Button', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_text_color_for_payment_button"
                                                value="<?php echo esc_attr($pixel_settings['text_color_for_payment_button'] ?? '#FFFFFF'); ?>" />
                                </td>
                                <td><?php esc_html_e('Text Color for Input Fields', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_text_color_for_input_fields"
                                                value="<?php echo esc_attr($pixel_settings['text_color_for_input_fields'] ?? '#000000'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Color for Text Placeholder', 'paymob-woocommerce'); ?></td>
                                <td><input type="color" name="woocommerce_paymob_pixel_color_for_text_placeholder"
                                                value="<?php echo esc_attr($pixel_settings['color_for_text_placeholder'] ?? '#667085'); ?>" />
                                </td>
                                <td><?php esc_html_e('Width of Container', 'paymob-woocommerce'); ?></td>
                                <td><input type="text" name="woocommerce_paymob_pixel_width_of_container"
                                                value="<?php echo esc_attr($pixel_settings['width_of_container'] ?? '100'); ?>" />
                                </td>
                                <td><?php esc_html_e('Vertical Padding', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_vertical_padding"
                                                value="<?php echo esc_attr($pixel_settings['vertical_padding'] ?? '40'); ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td><?php esc_html_e('Vertical Spacing Between Components', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_vertical_spacing_between_components"
                                                value="<?php echo esc_attr($pixel_settings['vertical_spacing_between_components'] ?? '18'); ?>" />
                                </td>
                                <td><?php esc_html_e('Container Padding', 'paymob-woocommerce'); ?></td>
                                <td><input type="number" name="woocommerce_paymob_pixel_container_padding"
                                                value="<?php echo esc_attr($pixel_settings['container_padding'] ?? '0'); ?>" />
                                </td>
                                <td></td>
                                <td></td>
                        </tr>
                        <tr>
                                <td colspan="6" style="text-align: center; padding-top: 20px;">
                                        <button id="reset-defaults" type="button" class="reset-button" title="Reset to Defaults">
                                                <i class="fas fa-sync"></i>Reset Default
                                        </button>
                                </td>
                        </tr>
                </table>
                <?php
        }
}
