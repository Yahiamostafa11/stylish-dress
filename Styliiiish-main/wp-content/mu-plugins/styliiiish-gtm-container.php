<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('styliiiish_get_gtm_container_id')) {
    function styliiiish_get_gtm_container_id()
    {
        if (defined('STYLIIIISH_GTM_CONTAINER_ID') && STYLIIIISH_GTM_CONTAINER_ID) {
            return trim((string) STYLIIIISH_GTM_CONTAINER_ID);
        }

        $envId = getenv('GTM_CONTAINER_ID');
        if (is_string($envId) && $envId !== '') {
            return trim($envId);
        }

        return 'GTM-NWP98MR3';
    }
}

if (!function_exists('styliiiish_should_output_gtm')) {
    function styliiiish_should_output_gtm()
    {
        if (is_admin()) {
            return false;
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            return false;
        }

        if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
            return false;
        }

        return true;
    }
}

if (!function_exists('styliiiish_print_gtm_head')) {
    function styliiiish_print_gtm_head()
    {
        if (!styliiiish_should_output_gtm()) {
            return;
        }

        $containerId = styliiiish_get_gtm_container_id();
        if ($containerId === '') {
            return;
        }

        ?>
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){
                w[l]=w[l]||[];
                w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
                var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),
                    dl=l!=='dataLayer'?'&l='+l:'';
                j.async=true;
                j.src='https://www.googletagmanager.com/gtm.js?id='+encodeURIComponent(i)+dl;
                f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo esc_js($containerId); ?>');
        </script>
        <!-- End Google Tag Manager -->
        <?php
    }
}

if (!function_exists('styliiiish_print_gtm_noscript')) {
    function styliiiish_print_gtm_noscript()
    {
        static $printed = false;
        if ($printed) {
            return;
        }

        if (!styliiiish_should_output_gtm()) {
            return;
        }

        $containerId = styliiiish_get_gtm_container_id();
        if ($containerId === '') {
            return;
        }

        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo rawurlencode($containerId); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php

        $printed = true;
    }
}

add_action('wp_head', 'styliiiish_print_gtm_head', 1);
add_action('wp_body_open', 'styliiiish_print_gtm_noscript', 1);
add_action('wp_footer', 'styliiiish_print_gtm_noscript', 100);
