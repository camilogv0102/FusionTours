<?php
/**
 * Plugin Name: Viajes Overrides
 * Description: Ajustes temporales para ocultar módulos avanzados no requeridos.
 * Author: SNC DESIGNS
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Ocultar pestañas de producto no utilizadas.
 */
add_filter(
	'woocommerce_product_data_tabs',
	function ( $tabs ) {
		unset( $tabs['mv_seguro'], $tabs['mv_sc_extras_tab'] );
		$default_hide = [
			'shipping',
			'linked_product',
			'attribute',
			'advanced',
		];
		foreach ( $default_hide as $tab_key ) {
			if ( isset( $tabs[ $tab_key ] ) ) {
				if ( ! isset( $tabs[ $tab_key ]['class'] ) || ! is_array( $tabs[ $tab_key ]['class'] ) ) {
					$tabs[ $tab_key ]['class'] = [];
				}
				$tabs[ $tab_key ]['class'][] = 'mv-hidden-tab';
			}
		}
		if ( isset( $tabs['mv_extras']['class'] ) && is_array( $tabs['mv_extras']['class'] ) ) {
			$tabs['mv_extras']['class'][] = 'mv-hidden-tab';
		}
		return $tabs;
	},
	100
);

/**
 * Desregistrar endpoints y shortcodes del módulo de Seguro de viaje.
 */
add_action(
	'init',
	function () {
		remove_action( 'wp_ajax_viaje_seguro_set', 'mv_ajax_seguro_set' );
		remove_action( 'wp_ajax_nopriv_viaje_seguro_set', 'mv_ajax_seguro_set' );
		remove_shortcode( 'viaje_seguro' );
	},
	20
);

/**
 * Fuerza a ocultar los paneles generados por las pestañas deshabilitadas.
 */
add_action(
	'admin_head',
	function () {
		?>
		<style>
			a[href="#mv_seguro_data"],
			a[href="#mv_sc_extras_panel"],
			#mv_seguro_data,
			#mv_sc_extras_panel,
			a[href="#mv_extras_data"] {
				display: none !important;
			}
		</style>
		<?php
	},
	110
);
