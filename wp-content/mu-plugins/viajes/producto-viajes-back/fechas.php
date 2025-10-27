<?php
/**
 * Plugin Name: Viajes – Precios por Personas
 * Description: Simplifica la pestaña de “Fechas” para gestionar precios por adulto y menor en distintos orígenes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mapa de ubicaciones disponibles.
 *
 * @return array<string,string>
 */
function mv_persona_price_locations() {
	return [
		'playa_del_carmen' => __( 'Playa del Carmen', 'manaslu' ),
		'cancun'           => __( 'Cancún', 'manaslu' ),
		'riviera_maya'     => __( 'Riviera Maya', 'manaslu' ),
	];
}

/**
 * Obtiene la matriz de precios guardada para el producto.
 *
 * @param int $product_id Producto.
 * @return array<int,array<string,float>>
 */
function mv_get_persona_price_matrix( $product_id ) {
	$raw = get_post_meta( $product_id, '_viaje_persona_precios', true );
	if ( ! is_array( $raw ) ) {
		return [];
	}

	$locations = mv_persona_price_locations();
	$clean     = [];

	foreach ( $raw as $extra_id => $prices ) {
		$extra_id = (int) $extra_id;
		if ( $extra_id <= 0 ) {
			continue;
		}

		$row = [];
		foreach ( $locations as $key => $label ) {
			$value = isset( $prices[ $key ] ) ? (float) $prices[ $key ] : 0.0;
			$row[ $key ] = $value;
		}

		$clean[ $extra_id ] = $row;
	}

	return $clean;
}

/**
 * Localización activa para calcular precios.
 *
 * @param int $product_id Producto.
 * @return string
 */
function mv_get_active_tarifa_location( $product_id ) {
	return apply_filters( 'mv_active_tarifa_location', 'playa_del_carmen', $product_id );
}

/**
 * Guarda la matriz de precios para el producto.
 *
 * @param int   $product_id Producto.
 * @param array $matrix     Datos sanitizados.
 */
function mv_save_persona_price_matrix( $product_id, $matrix ) {
	update_post_meta( $product_id, '_viaje_persona_precios', $matrix );
	if ( function_exists( 'mv_viaje_update_product_base_price' ) ) {
		mv_viaje_update_product_base_price( $product_id );
	}
}

if ( ! function_exists( 'mv_find_child_extra_for_product' ) ) {
	/**
	 * Locate the child extra assigned to a product.
	 *
	 * @param int $product_id     Product ID.
	 * @param int $adult_extra_id Adult extra to skip.
	 * @return int Extra ID or 0.
	 */
	function mv_find_child_extra_for_product( $product_id, $adult_extra_id = 0 ) {
		$product_id     = (int) $product_id;
		$adult_extra_id = (int) $adult_extra_id;

		if ( $product_id <= 0 ) {
			return 0;
		}

		$assigned = get_post_meta( $product_id, 'extras_asignados', true );
		if ( ! is_array( $assigned ) || empty( $assigned ) ) {
			return 0;
		}

		$fallback = 0;

		foreach ( $assigned as $extra_id ) {
			$extra_id = (int) $extra_id;
			if ( $extra_id <= 0 || $extra_id === $adult_extra_id ) {
				continue;
			}

			if ( function_exists( 'mv_is_personas_extra' ) && ! mv_is_personas_extra( $extra_id ) ) {
				continue;
			}

			$title = strtolower( trim( get_the_title( $extra_id ) ) );
			if ( $title && ( strpos( $title, 'menor' ) !== false || strpos( $title, 'niñ' ) !== false || strpos( $title, 'child' ) !== false || strpos( $title, 'kid' ) !== false ) ) {
				return $extra_id;
			}

			if ( ! $fallback ) {
				$fallback = $extra_id;
			}
		}

		return $fallback;
	}
}

if ( ! function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
	/**
	 * Resolve adult/child pricing for a product and origin.
	 *
	 * @param int    $product_id            Product ID.
	 * @param string $preferred_location_key Preferred location key.
	 * @return array{
	 *     location_key:string,
	 *     location_label:string,
	 *     adult_price:float,
	 *     child_price:float,
	 *     currency:string,
	 *     base_price:float
	 * }
	 */
	function mv_viaje_compute_price_snapshot( $product_id, $preferred_location_key = '' ) {
		$product_id = (int) $product_id;

		$empty = array(
			'location_key'   => '',
			'location_label' => '',
			'adult_price'    => 0.0,
			'child_price'    => 0.0,
			'currency'       => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'USD',
			'base_price'     => 0.0,
		);

		if ( $product_id <= 0 || ! function_exists( 'mv_get_persona_price_matrix' ) ) {
			return $empty;
		}

		$matrix = mv_get_persona_price_matrix( $product_id );
		if ( empty( $matrix ) ) {
			return $empty;
		}

		$locations       = function_exists( 'mv_persona_price_locations' ) ? (array) mv_persona_price_locations() : array();
		$adult_extra_id  = function_exists( 'mv_find_adult_extra_for_product' ) ? (int) mv_find_adult_extra_for_product( $product_id ) : 0;
		$child_extra_id  = function_exists( 'mv_find_child_extra_for_product' ) ? (int) mv_find_child_extra_for_product( $product_id, $adult_extra_id ) : 0;
		$currency        = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : 'USD';
		$candidate_keys  = array();
		$preferred       = $preferred_location_key ? sanitize_key( $preferred_location_key ) : '';
		$default_filter  = sanitize_key( mv_get_active_tarifa_location( $product_id ) );

		if ( $preferred ) {
			$candidate_keys[] = $preferred;
		}
		if ( $default_filter ) {
			$candidate_keys[] = $default_filter;
		}
		foreach ( array_keys( $locations ) as $location_key ) {
			if ( $location_key ) {
				$candidate_keys[] = sanitize_key( $location_key );
			}
		}
		foreach ( $matrix as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			foreach ( $row as $location_key => $_value ) {
				if ( $location_key ) {
					$candidate_keys[] = sanitize_key( $location_key );
				}
			}
		}

		$candidate_keys = array_values(
			array_filter(
				array_unique( $candidate_keys ),
				static function ( $value ) {
					return '' !== $value && null !== $value;
				}
			)
		);

		$build_snapshot = static function ( $target_key ) use ( $locations, $matrix, $adult_extra_id, $child_extra_id, $currency ) {
			$adult_price = ( $adult_extra_id && isset( $matrix[ $adult_extra_id ][ $target_key ] ) ) ? (float) $matrix[ $adult_extra_id ][ $target_key ] : 0.0;
			$child_price = ( $child_extra_id && isset( $matrix[ $child_extra_id ][ $target_key ] ) ) ? (float) $matrix[ $child_extra_id ][ $target_key ] : 0.0;

			if ( $child_price <= 0 && $adult_price > 0 ) {
				$child_price = $adult_price;
			}

			if ( $adult_price <= 0 && $child_price <= 0 ) {
				return null;
			}

			$label = isset( $locations[ $target_key ] ) ? $locations[ $target_key ] : ucwords( str_replace( array( '-', '_' ), ' ', $target_key ) );
			$base  = $adult_price > 0 ? $adult_price : $child_price;

			return array(
				'location_key'   => $target_key,
				'location_label' => $label,
				'adult_price'    => $adult_price > 0 ? $adult_price : 0.0,
				'child_price'    => $child_price > 0 ? $child_price : 0.0,
				'currency'       => $currency,
				'base_price'     => $base > 0 ? $base : 0.0,
			);
		};

		foreach ( $candidate_keys as $candidate_key ) {
			$snapshot = $build_snapshot( $candidate_key );
			if ( $snapshot ) {
				return $snapshot;
			}
		}

		foreach ( $matrix as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			foreach ( $row as $location_key => $_value ) {
				$candidate = sanitize_key( $location_key );
				if ( ! $candidate ) {
					continue;
				}
				$snapshot = $build_snapshot( $candidate );
				if ( $snapshot ) {
					return $snapshot;
				}
			}
		}

		return $empty;
	}
}

if ( ! function_exists( 'mv_viaje_update_product_base_price' ) ) {
	/**
	 * Sync the base WooCommerce price with the matrix snapshot.
	 *
	 * @param int $product_id Product ID.
	 * @return void
	 */
	function mv_viaje_update_product_base_price( $product_id ) {
		if ( ! function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
			return;
		}

		$product_id = (int) $product_id;
		if ( $product_id <= 0 ) {
			return;
		}

		$snapshot  = mv_viaje_compute_price_snapshot( $product_id );
		$base      = isset( $snapshot['base_price'] ) ? (float) $snapshot['base_price'] : 0.0;

		if ( $base > 0 && function_exists( 'wc_format_decimal' ) ) {
			update_post_meta( $product_id, '_regular_price', wc_format_decimal( $base ) );
			update_post_meta( $product_id, '_price', wc_format_decimal( $base ) );
		}
	}
}

if ( ! function_exists( 'mv_viaje_filter_product_price' ) ) {
	/**
	 * Inject dynamic pricing for Viaje products.
	 *
	 * @param string|float $price   Current price.
	 * @param WC_Product    $product Product object.
	 * @return float|string
	 */
	function mv_viaje_filter_product_price( $price, $product ) {
		if ( $product instanceof WC_Product && 'viaje' === $product->get_type() && function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
			$snapshot = mv_viaje_compute_price_snapshot( $product->get_id() );
			if ( ! empty( $snapshot['base_price'] ) ) {
				return $snapshot['base_price'];
			}
		}

		return $price;
	}
}

add_filter( 'woocommerce_product_get_price', 'mv_viaje_filter_product_price', 20, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'mv_viaje_filter_product_price', 20, 2 );
add_filter( 'woocommerce_product_get_sale_price', 'mv_viaje_filter_product_price', 20, 2 );

if ( ! function_exists( 'mv_viaje_is_purchasable' ) ) {
	/**
	 * Ensure Viaje products are purchasable when dynamic price exists.
	 *
	 * @param bool        $purchasable Is purchasable.
	 * @param WC_Product  $product     Product instance.
	 * @return bool
	 */
	function mv_viaje_is_purchasable( $purchasable, $product ) {
		if ( $product instanceof WC_Product && 'viaje' === $product->get_type() && function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
			$snapshot = mv_viaje_compute_price_snapshot( $product->get_id() );
			if ( ! empty( $snapshot['base_price'] ) ) {
				return true;
			}
		}

		return $purchasable;
	}
}

add_filter( 'woocommerce_is_purchasable', 'mv_viaje_is_purchasable', 10, 2 );

if ( ! function_exists( 'mv_viaje_force_quantity' ) ) {
	/**
	 * Force quantity to match participant count.
	 *
	 * @param int $quantity  Requested quantity.
	 * @param int $product_id Product ID.
	 * @return int
	 */
	function mv_viaje_force_quantity( $quantity, $product_id ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
		if ( ! $product || 'viaje' !== $product->get_type() ) {
			return $quantity;
		}

		$adults   = isset( $_REQUEST['viaje_adultos'] ) ? absint( wp_unslash( $_REQUEST['viaje_adultos'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$children = isset( $_REQUEST['viaje_menores'] ) ? absint( wp_unslash( $_REQUEST['viaje_menores'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$total    = max( 1, $adults + $children );

		return $total;
	}
}

add_filter( 'woocommerce_add_to_cart_quantity', 'mv_viaje_force_quantity', 10, 2 );

if ( ! function_exists( 'mv_viaje_add_cart_item_data' ) ) {
	/**
	 * Persist Viaje booking data in the cart item.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @param int   $product_id     Product ID.
	 * @param int   $variation_id   Variation ID.
	 * @param int   $quantity       Quantity.
	 * @return array
	 */
	function mv_viaje_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
		if ( ! $product || 'viaje' !== $product->get_type() || ! function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
			return $cart_item_data;
		}

		$adults      = isset( $_REQUEST['viaje_adultos'] ) ? absint( wp_unslash( $_REQUEST['viaje_adultos'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$children    = isset( $_REQUEST['viaje_menores'] ) ? absint( wp_unslash( $_REQUEST['viaje_menores'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$origin_raw  = isset( $_REQUEST['viaje_origen'] ) ? sanitize_key( wp_unslash( $_REQUEST['viaje_origen'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$snapshot    = mv_viaje_compute_price_snapshot( $product_id, $origin_raw );
		$total       = max( 1, $adults + $children );

		$cart_item_data['viaje_booking'] = array(
			'adults'             => $adults,
			'children'           => $children,
			'origin_key'         => $snapshot['location_key'],
			'origin_label'       => $snapshot['location_label'],
			'adult_price'        => $snapshot['adult_price'],
			'child_price'        => $snapshot['child_price'],
			'currency'           => $snapshot['currency'],
			'total_participants' => $total,
		);

		// Distinguish items by their booking signature.
		$cart_item_data['viaje_booking_signature'] = md5( $product_id . '|' . $snapshot['location_key'] . '|' . $adults . '|' . $children );

		return $cart_item_data;
	}
}

add_filter( 'woocommerce_add_cart_item_data', 'mv_viaje_add_cart_item_data', 10, 4 );

if ( ! function_exists( 'mv_viaje_apply_cart_pricing' ) ) {
	/**
	 * Apply dynamic pricing inside the cart.
	 *
	 * @param WC_Cart $cart Cart object.
	 * @return void
	 */
	function mv_viaje_apply_cart_pricing( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( ! $cart || ! is_a( $cart, 'WC_Cart' ) || ! function_exists( 'mv_viaje_compute_price_snapshot' ) ) {
			return;
		}

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( empty( $cart_item['data'] ) || ! $cart_item['data'] instanceof WC_Product ) {
				continue;
			}

			$product = $cart_item['data'];
			if ( 'viaje' !== $product->get_type() ) {
				continue;
			}

			$booking = isset( $cart_item['viaje_booking'] ) && is_array( $cart_item['viaje_booking'] ) ? $cart_item['viaje_booking'] : array();

			$adults       = isset( $booking['adults'] ) ? max( 0, (int) $booking['adults'] ) : 0;
			$children     = isset( $booking['children'] ) ? max( 0, (int) $booking['children'] ) : 0;
			$origin_key   = isset( $booking['origin_key'] ) ? sanitize_key( $booking['origin_key'] ) : '';
			$snapshot     = mv_viaje_compute_price_snapshot( $product->get_id(), $origin_key );
			$adult_price  = isset( $snapshot['adult_price'] ) ? (float) $snapshot['adult_price'] : 0.0;
			$child_price  = isset( $snapshot['child_price'] ) ? (float) $snapshot['child_price'] : 0.0;

			if ( $child_price <= 0 ) {
				$child_price = $adult_price;
			}

			$total_participants = max( 1, $adults + $children );
			if ( $total_participants <= 0 ) {
				$total_participants = max( 1, (int) $cart_item['quantity'] );
			}

			if ( (int) $cart_item['quantity'] !== $total_participants ) {
				$cart->cart_contents[ $cart_item_key ]['quantity'] = $total_participants;
				$cart_item['quantity']                              = $total_participants;
			}

			$total_price = ( $adults * $adult_price ) + ( $children * $child_price );
			if ( $total_price <= 0 && $adult_price > 0 ) {
				$total_price = $total_participants * $adult_price;
			}

			$unit_price = $total_participants > 0 ? $total_price / $total_participants : 0.0;
			if ( $unit_price < 0 ) {
				$unit_price = 0.0;
			}

			$product->set_price( $unit_price );

			$cart->cart_contents[ $cart_item_key ]['viaje_booking'] = array_merge(
				$booking,
				array(
					'adults'             => $adults,
					'children'           => $children,
					'origin_key'         => $snapshot['location_key'],
					'origin_label'       => $snapshot['location_label'],
					'adult_price'        => $adult_price,
					'child_price'        => $child_price,
					'currency'           => $snapshot['currency'],
					'total_participants' => $total_participants,
				)
			);
		}
	}
}

add_action( 'woocommerce_before_calculate_totals', 'mv_viaje_apply_cart_pricing', 15 );

if ( ! function_exists( 'mv_viaje_render_cart_item_data' ) ) {
	/**
	 * Display Viaje metadata in the cart.
	 *
	 * @param array $item_data Existing item data.
	 * @param array $cart_item Cart item.
	 * @return array
	 */
	function mv_viaje_render_cart_item_data( $item_data, $cart_item ) {
		if ( empty( $cart_item['viaje_booking'] ) || ! is_array( $cart_item['viaje_booking'] ) ) {
			return $item_data;
		}

		$booking = $cart_item['viaje_booking'];

		if ( ! empty( $booking['origin_label'] ) ) {
			$item_data[] = array(
				'name'  => __( 'Salida desde', 'blankslate' ),
				'value' => wc_clean( $booking['origin_label'] ),
			);
		}

		if ( ! empty( $booking['adults'] ) ) {
			$item_data[] = array(
				'name'  => __( 'Adultos', 'blankslate' ),
				'value' => (int) $booking['adults'],
			);
		}

		if ( ! empty( $booking['children'] ) ) {
			$item_data[] = array(
				'name'  => __( 'Menores', 'blankslate' ),
				'value' => (int) $booking['children'],
			);
		}

		return $item_data;
	}
}

add_filter( 'woocommerce_get_item_data', 'mv_viaje_render_cart_item_data', 10, 2 );

if ( ! function_exists( 'mv_viaje_store_order_item_data' ) ) {
	/**
	 * Persist Viaje metadata into the order line item.
	 *
	 * @param WC_Order_Item_Product $item          Order line item.
	 * @param string                $cart_item_key Cart item key.
	 * @param array                 $values        Cart item values.
	 * @param WC_Order              $order         Order instance.
	 * @return void
	 */
	function mv_viaje_store_order_item_data( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values['viaje_booking'] ) || ! is_array( $values['viaje_booking'] ) ) {
			return;
		}

		$booking = $values['viaje_booking'];

		if ( ! empty( $booking['origin_label'] ) ) {
			$item->add_meta_data( __( 'Salida desde', 'blankslate' ), $booking['origin_label'] );
		}

		$item->add_meta_data( __( 'Adultos', 'blankslate' ), isset( $booking['adults'] ) ? (int) $booking['adults'] : 0 );
		$item->add_meta_data( __( 'Menores', 'blankslate' ), isset( $booking['children'] ) ? (int) $booking['children'] : 0 );
		$item->add_meta_data( 'viaje_origin_key', isset( $booking['origin_key'] ) ? $booking['origin_key'] : '' );
	}
}

add_action( 'woocommerce_checkout_create_order_line_item', 'mv_viaje_store_order_item_data', 10, 4 );

/**
 * Registro del tipo de producto "viaje".
 */
add_action(
	'init',
	function () {
		if ( function_exists( 'wc_get_product_types' ) && ! get_term_by( 'slug', 'viaje', 'product_type' ) ) {
			wp_insert_term( 'viaje', 'product_type' );
		}
	}
);

add_filter(
	'product_type_selector',
	function ( $types ) {
		$types['viaje'] = __( 'Viajes', 'manaslu' );
		return $types;
	}
);

add_filter(
	'woocommerce_product_class',
	function ( $classname, $type ) {
		if ( 'viaje' === $type && class_exists( 'WC_Product_Simple' ) ) {
			if ( ! class_exists( 'WC_Product_Viaje', false ) ) {
				class WC_Product_Viaje extends WC_Product_Simple {
					public function get_type() {
						return 'viaje';
					}
				}
			}
			return 'WC_Product_Viaje';
		}
		return $classname;
	},
	10,
	2
);

add_action(
	'save_post_product',
	function ( $post_id, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$posted_type = '';
		if ( isset( $_POST['product-type'] ) ) {
			$posted_type = sanitize_text_field( wp_unslash( $_POST['product-type'] ) );
		}
		if ( isset( $_POST['product_type'] ) ) {
			$posted_type = sanitize_text_field( wp_unslash( $_POST['product_type'] ) );
		}

		if ( 'viaje' === $posted_type ) {
			if ( ! term_exists( 'viaje', 'product_type' ) ) {
				wp_insert_term( 'viaje', 'product_type' );
			}
			wp_set_object_terms( $post_id, 'viaje', 'product_type', false );
		}
	},
	100,
	3
);

/**
 * Renombra la pestaña y mantiene la visibilidad condicional.
 */
add_filter(
	'woocommerce_product_data_tabs',
	function ( $tabs ) {
		$tabs['viaje_fecha'] = [
			'label'    => __( 'Precios', 'manaslu' ),
			'target'   => 'viaje_fecha_data',
			'class'    => [ 'show_if_viaje' ],
			'priority' => 9,
		];

		return $tabs;
	},
	50
);

/**
 * Renderiza el panel de precios.
 */
add_action(
	'woocommerce_product_data_panels',
	function () {
		global $post;
		if ( ! $post || 'product' !== $post->post_type ) {
			return;
		}

		$product_id = (int) $post->ID;
		$assigned   = get_post_meta( $product_id, 'extras_asignados', true );
		$assigned   = is_array( $assigned ) ? array_map( 'intval', $assigned ) : [];

		$personas = [];
		foreach ( $assigned as $extra_id ) {
			$terms = get_the_terms( $extra_id, 'extra_category' );
			if ( is_wp_error( $terms ) || ! $terms ) {
				continue;
			}
			foreach ( $terms as $term ) {
				if ( 'personas' === $term->slug ) {
					$personas[] = $extra_id;
					break;
				}
			}
		}

		$personas = array_values( array_unique( $personas ) );
		$matrix   = mv_get_persona_price_matrix( $product_id );
		$locations = mv_persona_price_locations();
		?>
		<div id="viaje_fecha_data" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php if ( empty( $personas ) ) : ?>
					<p><?php esc_html_e( 'Asigna los extras “Adulto” y “Menor” (categoría Personas) desde la caja lateral para poder cargar los precios.', 'manaslu' ); ?></p>
				<?php else : ?>
					<table class="widefat striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Extra', 'manaslu' ); ?></th>
								<?php foreach ( $locations as $key => $label ) : ?>
									<th><?php echo esc_html( $label ); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $personas as $extra_id ) : ?>
								<?php
								$title   = get_the_title( $extra_id );
								$current = isset( $matrix[ $extra_id ] ) ? $matrix[ $extra_id ] : [];
								?>
								<tr>
									<td>
										<strong><?php echo esc_html( $title ); ?></strong>
										<input type="hidden" name="viaje_persona_present[]" value="<?php echo esc_attr( $extra_id ); ?>">
									</td>
									<?php foreach ( $locations as $key => $label ) : ?>
										<?php
										$value = isset( $current[ $key ] ) ? (float) $current[ $key ] : 0.0;
										?>
										<td>
											<input
												type="number"
												step="0.01"
												min="0"
												name="viaje_persona_precios[<?php echo esc_attr( $extra_id ); ?>][<?php echo esc_attr( $key ); ?>]"
												value="<?php echo esc_attr( $value ); ?>"
												style="width:100%;"
											/>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
);

/**
 * Guarda los precios enviados desde el panel.
 */
add_action(
	'woocommerce_admin_process_product_object',
	function ( $product ) {
		$present = isset( $_POST['viaje_persona_present'] ) && is_array( $_POST['viaje_persona_present'] )
			? array_map( 'intval', wp_unslash( $_POST['viaje_persona_present'] ) )
			: [];

		$data = isset( $_POST['viaje_persona_precios'] ) && is_array( $_POST['viaje_persona_precios'] )
			? wp_unslash( $_POST['viaje_persona_precios'] )
			: [];

		$locations = mv_persona_price_locations();
		$matrix    = [];

		foreach ( $present as $extra_id ) {
			if ( $extra_id <= 0 ) {
				continue;
			}

			$row      = [];
			$raw_row  = isset( $data[ $extra_id ] ) ? (array) $data[ $extra_id ] : [];

			foreach ( $locations as $key => $label ) {
				$value     = isset( $raw_row[ $key ] ) ? $raw_row[ $key ] : '';
				$row[ $key ] = ( '' !== $value ) ? (float) wc_format_decimal( $value ) : 0.0;
			}

			$matrix[ $extra_id ] = $row;
		}

		mv_save_persona_price_matrix( $product->get_id(), $matrix );
	}
);

/* -------------------------------
 * Funciones heredadas (stubs)
 * ----------------------------- */

if ( ! function_exists( 'mv_get_fechas' ) ) {
	/**
	 * Compatibilidad: ya no hay fechas, devolvemos un arreglo vacío.
	 *
	 * @param int $product_id Producto.
	 * @return array
	 */
	function mv_get_fechas( $product_id ) {
		return [];
	}
}

if ( ! function_exists( 'mv_get_sales_map' ) ) {
	function mv_get_sales_map( $product_id ) {
		return [];
	}
}

if ( ! function_exists( 'mv_set_sales_map' ) ) {
	function mv_set_sales_map( $product_id, $map ) {
		return;
	}
}

if ( ! function_exists( 'mv_available_for_idx' ) ) {
	function mv_available_for_idx( $product_id, $idx, $row ) {
		return 0;
	}
}

if ( ! function_exists( 'mv_cart_selected_idx' ) ) {
	function mv_cart_selected_idx( $product_id ) {
		return -1;
	}
}

if ( ! function_exists( 'mv_fmt_date' ) ) {
	function mv_fmt_date( $ymd ) {
		return '';
	}
}

if ( ! function_exists( 'mv_get_comprando_viaje_url' ) ) {
	function mv_get_comprando_viaje_url( $product_id ) {
		$product_id = (int) $product_id;
		if ( ! $product_id ) {
			return '';
		}

		$product = get_post( $product_id );
		if ( ! $product || 'product' !== $product->post_type ) {
			return '';
		}

		$link = get_permalink( $product_id );

		return $link ? esc_url_raw( $link ) : '';
	}
}
