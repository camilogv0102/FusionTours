<?php
namespace Brandoon\WooFilterPro;

class Translation_Handler {
    public static function wfp_init() {
        add_action('init', [self::class, 'wfp_register_filters'], 20);
    }

    public static function wfp_register_filters() {
        static $registered = false;

        if ($registered) {
            return;
        }

        $registered = true;

        if (defined('WFP_PLUGIN_BASENAME')) {
            load_plugin_textdomain(
                'woo-filter-pro',
                false,
                dirname(WFP_PLUGIN_BASENAME) . '/languages'
            );
        }

        add_filter('gettext', [self::class, 'wfp_traducir_woocommerce'], 20, 3);
        add_filter('woocommerce_coupon_error', [self::class, 'wfp_traducir_woocommerce'], 20, 3);
        add_filter('woocommerce_coupon_message', [self::class, 'wfp_traducir_woocommerce'], 20, 3);
        add_filter('gettext', [self::class, 'wfp_traducir_mensaje_carrito_vacio'], 20, 3);
        add_filter('woocommerce_widget_cart_is_empty', [self::class, 'wfp_mensaje_carrito_vacio_custom']);
    }

    public static function wfp_traducir_woocommerce($translated, $text = '', $domain = '') {
        if ($text === 'Coupon code') {
            return __('Código de cupón', 'woo-filter-pro');
        }
        if ($text === 'Apply') {
            return __('Aplicar', 'woo-filter-pro');
        }
        if ($text === 'If you have a coupon code, please apply it below.') {
            return __('Si tienes un código de cupón, escríbelo aquí abajo.', 'woo-filter-pro');
        }
        return $translated;
    }

    public static function wfp_traducir_mensaje_carrito_vacio($translated_text, $text, $domain) {
        if ($domain === 'woocommerce' && trim($text) === 'No products in the cart.') {
            return __('No hay productos en el carrito.', 'woo-filter-pro');
        }
        return $translated_text;
    }

    public static function wfp_mensaje_carrito_vacio_custom($message) {
        return sprintf('<p class="woocommerce-mini-cart__empty-message">%s</p>', __('No hay productos en el carrito.', 'woo-filter-pro'));
    }
}
