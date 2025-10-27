<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

require_once get_stylesheet_directory() . '/disables.php';




add_filter('woocommerce_add_to_cart_redirect', 'redirigir_checkout_despues_agregar');
function redirigir_checkout_despues_agregar($url) {
    if (isset($_GET['add-to-cart'])) {
        // Cambia '/ProEffects/checkout/' según tu estructura
        $url = home_url('/ProEffects/checkout/');
    }
    return $url;
}

add_filter('woocommerce_checkout_fields', function($fields) {
    // Mantener solo los campos deseados
    $keep = [
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_country'
    ];
    foreach ($fields['billing'] as $key => $field) {
        if (!in_array($key, $keep)) {
            unset($fields['billing'][$key]);
        }
    }
    // También puedes limpiar los campos de envío si existen
    $fields['shipping'] = [];
    return $fields;
});


