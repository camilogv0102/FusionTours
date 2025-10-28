<?php
/**
 * Lovable-inspired single product layout with interactions markup.
 *
 * @package BlankSlate
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $product;

$did_setup_post = false;
if ( have_posts() ) {
	the_post();
	$did_setup_post = true;
}

if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product instanceof WC_Product ) {
	wc_get_template( 'single-product-notices.php' );
	get_footer( 'shop' );
	return;
}

do_action( 'woocommerce_before_single_product' );

$product_id = $product->get_id();

$gallery_ids = array_filter(
	array_merge(
		array( $product->get_image_id() ),
		(array) $product->get_gallery_image_ids()
	)
);
$gallery_images = array();
foreach ( $gallery_ids as $image_id ) {
	$src = wp_get_attachment_image_src( $image_id, 'large' );
	if ( ! $src ) {
		continue;
	}
	$gallery_images[] = array(
		'url' => $src[0],
		'alt' => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: $product->get_name(),
	);
}
if ( empty( $gallery_images ) ) {
	$gallery_images[] = array(
		'url' => wc_placeholder_img_src( 'large' ),
		'alt' => $product->get_name(),
	);
}

$sanitize_meta_array = static function ( $value ) {
	if ( empty( $value ) ) {
		return array();
	}

	if ( is_string( $value ) ) {
		$decoded = maybe_unserialize( $value );
		if ( is_array( $decoded ) ) {
			$value = $decoded;
		}
	}

	if ( is_array( $value ) ) {
		return array_values(
			array_filter(
				$value,
				static function ( $item ) {
					return ! empty( $item );
				}
			)
		);
	}

	return array();
};

$feature_badges = $sanitize_meta_array( get_post_meta( $product_id, 'fusion_feature_badges', true ) );
if ( empty( $feature_badges ) ) {
	$feature_badges = array(
		array(
			'icon' => 'captain',
			'text' => __( 'Tripulación certificada', 'blankslate' ),
		),
		array(
			'icon' => 'bus',
			'text' => __( 'Transporte ida y vuelta', 'blankslate' ),
		),
	);
}

$trip_dates_raw = get_post_meta( $product_id, '_viaje_fechas', true );
$trip_dates     = array();
$includes = $sanitize_meta_array( get_post_meta( $product_id, 'fusion_includes', true ) );
if ( empty( $includes ) ) {
	$includes = array(
		__( 'Paseo en barco desde Cancún', 'blankslate' ),
		__( 'Transporte ida y vuelta desde tu hotel', 'blankslate' ),
		__( 'Tripulación certificada', 'blankslate' ),
		__( 'Snorkel con tiburón ballena', 'blankslate' ),
		__( 'Equipo de snorkel incluido', 'blankslate' ),
		__( 'Bebidas y snacks a bordo', 'blankslate' ),
		__( 'Box lunch', 'blankslate' ),
		__( 'Guía bilingüe certificado', 'blankslate' ),
		__( 'Chaleco salvavidas', 'blankslate' ),
		__( 'Toallas', 'blankslate' ),
	);
}


$pricing_options = $sanitize_meta_array( get_post_meta( $product_id, 'fusion_pricing_options', true ) );

$product_type = $product->get_type();

$base_currency    = function_exists( 'get_option' ) ? strtoupper( (string) get_option( 'woocommerce_currency' ) ) : 'USD';
$current_currency = function_exists( 'get_woocommerce_currency' ) ? strtoupper( (string) get_woocommerce_currency() ) : $base_currency;
$can_convert_prices = ( $current_currency && $base_currency && $current_currency !== $base_currency && has_filter( 'woocs_convert_price' ) );

$convert_price = static function ( $amount ) use ( $can_convert_prices ) {
	$numeric = is_numeric( $amount ) ? (float) $amount : 0.0;
	if ( ! $can_convert_prices ) {
		return $numeric;
	}
	$converted = apply_filters( 'woocs_convert_price', $numeric, false );
	return is_numeric( $converted ) ? (float) $converted : $numeric;
};

$trip_dates_raw = get_post_meta( $product_id, '_viaje_fechas', true );
$trip_dates     = array();
if ( is_array( $trip_dates_raw ) ) {
    $format_trip_date = static function ( $value ) {
        if ( empty( $value ) ) {
            return '';
        }
        $timestamp = strtotime( $value . ' 00:00:00' );
        if ( ! $timestamp ) {
            return esc_html( $value );
        }
        $month_names = array( '', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' );
        $day         = wp_date( 'd', $timestamp );
        $month_index = (int) wp_date( 'n', $timestamp );
        $month_label = $month_names[ $month_index ] ?? wp_date( 'F', $timestamp );
        return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( sprintf( '%s %s', $day, $month_label ), 'UTF-8' ) : strtoupper( sprintf( '%s %s', $day, $month_label ) );
    };

    foreach ( $trip_dates_raw as $idx => $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }

        $start       = $row['inicio'] ?? '';
        $end         = $row['fin'] ?? '';
        $status      = isset( $row['estado'] ) ? strtolower( (string) $row['estado'] ) : '';
        $is_closed   = in_array( $status, array( 'agotado', 'cerrado' ), true );
        $start_label = $format_trip_date( $start );
        $end_label   = $format_trip_date( $end );
        $label       = trim( $start_label && $end_label ? sprintf( '%s – %s', $start_label, $end_label ) : ( $start_label ?: $end_label ) );

        $trip_dates[] = array(
            'index'       => (int) $idx,
            'label'       => $label ?: sprintf( __( 'Fecha #%d', 'blankslate' ), (int) $idx + 1 ),
            'start'       => $start,
            'end'         => $end,
            'status'      => $status,
            'is_closed'   => $is_closed,
        );
    }
}

$trip_dates_payload      = array();
$default_trip_date_index  = null;
foreach ( $trip_dates as $date_entry ) {
    $trip_dates_payload[] = array(
        'index'  => $date_entry['index'],
        'label'  => $date_entry['label'],
        'status' => $date_entry['status'],
    );
    if ( null === $default_trip_date_index && empty( $date_entry['is_closed'] ) ) {
        $default_trip_date_index = $date_entry['index'];
    }
}
$trip_dates_json = $trip_dates_payload ? wp_json_encode( $trip_dates_payload ) : '';

if ( 'viaje' === $product_type && function_exists( 'mv_get_persona_price_matrix' ) ) {
    $matrix           = mv_get_persona_price_matrix( $product_id );
    $location_labels  = function_exists( 'mv_persona_price_locations' ) ? mv_persona_price_locations() : array();
    $pricing_override = array();

    if ( $matrix ) {
        $adult_extra_id = function_exists( 'mv_find_adult_extra_for_product' ) ? mv_find_adult_extra_for_product( $product_id ) : 0;

        $resolve_child_extra = static function( $product_id, $adult_id ) {
            $assigned = get_post_meta( $product_id, 'extras_asignados', true );
            if ( ! is_array( $assigned ) ) {
                return 0;
            }

            $candidates = array();
            foreach ( $assigned as $extra_id ) {
                $extra_id = (int) $extra_id;
                if ( $extra_id === $adult_id ) {
                    continue;
                }
                if ( function_exists( 'mv_is_personas_extra' ) && mv_is_personas_extra( $extra_id ) ) {
                    $candidates[] = $extra_id;
                    $title = strtolower( trim( get_the_title( $extra_id ) ) );
                    if ( strpos( $title, 'menor' ) !== false || strpos( $title, 'niñ' ) !== false || strpos( $title, 'child' ) !== false || strpos( $title, 'kid' ) !== false ) {
                        return $extra_id;
                    }
                }
            }

            return ! empty( $candidates ) ? (int) $candidates[0] : 0;
        };

        $child_extra_id = $resolve_child_extra( $product_id, $adult_extra_id );
        $currency       = $current_currency;

        foreach ( (array) $location_labels as $location_key => $location_label ) {
            $adult_price = ( $adult_extra_id && isset( $matrix[ $adult_extra_id ][ $location_key ] ) ) ? (float) $matrix[ $adult_extra_id ][ $location_key ] : 0;
            $child_price = ( $child_extra_id && isset( $matrix[ $child_extra_id ][ $location_key ] ) ) ? (float) $matrix[ $child_extra_id ][ $location_key ] : 0;

            if ( $can_convert_prices ) {
                $adult_price = $convert_price( $adult_price );
                if ( $child_price > 0 ) {
                    $child_price = $convert_price( $child_price );
                }
            }

            if ( $adult_price <= 0 && $child_price <= 0 ) {
                continue;
            }

            $label = sprintf( __( 'Desde %s', 'blankslate' ), $location_label );
            if ( function_exists( 'mb_strtoupper' ) ) {
                $label = mb_strtoupper( $label, 'UTF-8' );
            } else {
                $label = strtoupper( $label );
            }

            $pricing_override[] = array(
                'location'   => $label,
                'location_key' => $location_key,
                'adults'     => $adult_price,
                'children'   => $child_price > 0 ? $child_price : $adult_price,
                'currency'   => $currency,
                'price_html' => '',
                'raw_label'  => $location_label,
            );
        }

        if ( empty( $pricing_override ) ) {
            foreach ( $matrix as $prices ) {
                if ( ! is_array( $prices ) ) {
                    continue;
                }
                foreach ( $prices as $location_key => $value ) {
                    $value = (float) $value;
                    if ( $value <= 0 ) {
                        continue;
                    }
                    $location_label = isset( $location_labels[ $location_key ] ) ? $location_labels[ $location_key ] : $location_key;
                $label          = sprintf( __( 'Desde %s', 'blankslate' ), $location_label );
                if ( function_exists( 'mb_strtoupper' ) ) {
                    $label = mb_strtoupper( $label, 'UTF-8' );
                } else {
                    $label = strtoupper( $label );
                }
                if ( $can_convert_prices ) {
                    $value = $convert_price( $value );
                }
                $pricing_override[] = array(
                    'location'   => $label,
                    'location_key' => $location_key,
                    'adults'     => $value,
                    'children'   => $value,
                        'currency'   => $currency,
                        'price_html' => '',
                        'raw_label'  => $location_label,
                    );
                }
                if ( $pricing_override ) {
                    break;
                }
            }
        }

        if ( $pricing_override ) {
            $pricing_options = $pricing_override;
        }
    }
}
if ( empty( $pricing_options ) ) {
	$display_price = (float) $product->get_price();
	$price_currency = get_woocommerce_currency();

	if ( $product->is_type( 'variable' ) ) {
		$variable_price = (float) $product->get_variation_price( 'min', false );
		if ( $variable_price > 0 ) {
			$display_price = $variable_price;
		}
	}

	if ( $display_price <= 0 && $product->get_regular_price() ) {
		$display_price = (float) $product->get_regular_price();
	}

	$price_html = $product->get_price_html();

	$pricing_options = array(
		array(
			'location'   => __( 'Precio del tour', 'blankslate' ),
			'adults'     => $display_price,
			'children'   => $display_price,
			'currency'   => $price_currency,
			'price_html' => $price_html,
		),
	);
} else {
	foreach ( $pricing_options as &$pricing_option ) {
		$pricing_option['adults']   = isset( $pricing_option['adults'] ) ? (float) $pricing_option['adults'] : 0;
		$pricing_option['children'] = isset( $pricing_option['children'] ) && '' !== $pricing_option['children'] ? (float) $pricing_option['children'] : $pricing_option['adults'];

		$existing_currency = isset( $pricing_option['currency'] ) ? strtoupper( (string) $pricing_option['currency'] ) : '';
		$should_convert    = $can_convert_prices && ( '' === $existing_currency || $existing_currency === $base_currency );

		if ( $should_convert ) {
			$pricing_option['adults'] = $convert_price( $pricing_option['adults'] );
			if ( $pricing_option['children'] > 0 ) {
				$pricing_option['children'] = $convert_price( $pricing_option['children'] );
			}
			$pricing_option['price_html'] = '';
		} elseif ( empty( $pricing_option['price_html'] ) ) {
			$pricing_option['price_html'] = '';
		}

		if ( $pricing_option['children'] <= 0 ) {
			$pricing_option['children'] = $pricing_option['adults'];
		}

		$pricing_option['currency'] = $current_currency;
		if ( empty( $pricing_option['location_key'] ) ) {
			$raw_location = '';
			if ( ! empty( $pricing_option['raw_label'] ) ) {
				$raw_location = (string) $pricing_option['raw_label'];
			} elseif ( ! empty( $pricing_option['location'] ) ) {
				$raw_location = (string) $pricing_option['location'];
			}
			if ( $raw_location ) {
				$pricing_option['location_key'] = sanitize_key( str_replace( '-', '_', sanitize_title( $raw_location ) ) );
			}
		}
		if ( empty( $pricing_option['location_key'] ) && ! empty( $pricing_option['location'] ) ) {
			$pricing_option['location_key'] = sanitize_key( str_replace( '-', '_', sanitize_title( (string) $pricing_option['location'] ) ) );
		}
		if ( empty( $pricing_option['location_key'] ) ) {
			$pricing_option['location_key'] = 'origin_' . uniqid();
		}
		if ( empty( $pricing_option['price_html'] ) ) {
			$pricing_option['price_html'] = '';
		}
	}
	unset( $pricing_option );
}

$requires_quote   = (bool) get_post_meta( $product_id, 'fusion_requires_quote', true );
$whatsapp_number  = sanitize_text_field( get_post_meta( $product_id, 'fusion_whatsapp_number', true ) );
$whatsapp_message = sanitize_textarea_field( get_post_meta( $product_id, 'fusion_whatsapp_message', true ) );

$pricing_payload = array();
foreach ( $pricing_options as $index => $option ) {
	$location_label = $option['location'] ?? '';
	$location_value = $option['location_key'] ?? '';

	if ( ! $location_value && ! empty( $option['raw_label'] ) ) {
		$location_value = sanitize_key( str_replace( '-', '_', sanitize_title( (string) $option['raw_label'] ) ) );
	}
	if ( ! $location_value && $location_label ) {
		$location_value = sanitize_key( str_replace( '-', '_', sanitize_title( (string) $location_label ) ) );
	}
	if ( ! $location_value ) {
		$location_value = 'origin_' . ( (int) $index );
	}

	$pricing_payload[] = array(
		'location'     => $location_label,
		'label'        => $option['raw_label'] ?? $location_label,
		'value'        => $location_value,
		'location_key' => $location_value,
		'adults'       => isset( $option['adults'] ) ? (float) $option['adults'] : 0,
		'children'     => isset( $option['children'] ) ? (float) $option['children'] : ( isset( $option['adults'] ) ? (float) $option['adults'] : 0 ),
		'currency'     => $option['currency'] ?? get_woocommerce_currency(),
	);
}

$default_origin_location = '';
if ( ! empty( $pricing_payload ) ) {
	$preferred_origin = sanitize_key( mv_get_active_tarifa_location( $product_id ) );
	if ( $preferred_origin ) {
		foreach ( $pricing_payload as $payload_entry ) {
			if ( isset( $payload_entry['location_key'] ) && $payload_entry['location_key'] === $preferred_origin ) {
				$default_origin_location = $preferred_origin;
				break;
			}
		}
	}
	if ( '' === $default_origin_location ) {
		$default_origin_location = $pricing_payload[0]['location_key'] ?? $pricing_payload[0]['value'] ?? $pricing_payload[0]['location'] ?? '';
	}
}

$recommended_ids = $product->get_upsell_ids();
if ( empty( $recommended_ids ) ) {
	$recommended_ids = wc_get_related_products( $product_id, 4 );
}

$recommended_products = array();
foreach ( $recommended_ids as $recommended_id ) {
	$recommended_product = wc_get_product( $recommended_id );
	if ( ! $recommended_product ) {
		continue;
	}
	$image = wp_get_attachment_image_src( $recommended_product->get_image_id(), 'large' );
	$recommended_products[] = array(
		'id'          => $recommended_id,
		'name'        => $recommended_product->get_name(),
		'permalink'   => $recommended_product->get_permalink(),
		'image'       => $image ? $image[0] : wc_placeholder_img_src(),
		'category'    => wp_strip_all_tags( wc_get_product_category_list( $recommended_id, ', ' ) ),
		'description' => wp_trim_words( $recommended_product->get_short_description() ?: $recommended_product->get_description(), 24 ),
	);
}

$description = apply_filters( 'woocommerce_short_description', $product->get_short_description() );
if ( ! $description ) {
	$description = apply_filters( 'the_content', $product->get_description() );
}

$render_feature_icon = static function ( $slug ) {
	if ( 'bus' === $slug || 'transport' === $slug ) {
		return '<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.9758 1.59802H5.59336C4.95762 1.59802 4.34791 1.85057 3.89837 2.30011C3.44884 2.74965 3.19629 3.35935 3.19629 3.99509V19.9756C3.19624 20.3126 3.26739 20.6458 3.40509 20.9533C3.54278 21.2609 3.74392 21.5359 3.99531 21.7604V22.6723C3.99531 23.0166 4.13211 23.3469 4.37561 23.5904C4.61911 23.8339 4.94936 23.9707 5.29373 23.9707H6.69202C7.03638 23.9707 7.36663 23.8339 7.61013 23.5904C7.85363 23.3469 7.99043 23.0166 7.99043 22.6723V22.3726H17.5787V22.6723C17.5787 23.0166 17.7155 23.3469 17.959 23.5904C18.2025 23.8339 18.5328 23.9707 18.8771 23.9707H20.2754C20.6198 23.9707 20.95 23.8339 21.1935 23.5904C21.437 23.3469 21.5738 23.0166 21.5738 22.6723V21.7604C21.8252 21.5359 22.0264 21.2609 22.1641 20.9533C22.3017 20.6458 22.3729 20.3126 22.3728 19.9756V3.99509C22.3728 3.35935 22.1203 2.74965 21.6708 2.30011C21.2212 1.85057 20.6115 1.59802 19.9758 1.59802Z" fill="#0070C0"/></svg>';
	}

	return '<svg width="28" height="26" viewBox="0 0 28 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.1658 4.00219H22.0126C21.6025 4.01641 21.1486 4.0968 20.6564 4.23734C19.672 4.51953 18.5564 5.03359 17.4134 5.64719C15.122 6.87219 12.7212 8.49094 10.7689 9.39875C9.32514 10.0769 7.52592 10.5144 5.85795 10.9027C4.18998 11.2909 2.63412 11.6355 1.79139 12.0292C1.40146 12.2152 1.19529 12.4066 1.09631 12.5706C0.99787 12.7292 0.981464 12.855 1.01756 13.03C1.08975 13.38 1.48623 13.8722 2.02873 14.2277L2.20865 14.3425C11.1025 14.2167 18.0369 13.5933 23.4126 11.4659C23.5275 10.5034 23.779 9.56828 24.315 8.81359V8.80813L24.3204 8.80266C25.6603 7.04172 24.8072 4.83344 23.0955 4.16023C22.822 4.05359 22.5103 4.00109 22.1658 4V4.00219ZM23.3634 14.1259C20.9462 15.4986 17.6869 16.1494 14.1103 16.3791C10.3533 16.6197 6.2517 16.3845 2.43342 15.9525L2.53678 16.7181C4.24248 16.8931 7.92514 17.3634 11.9884 17.347C16.2103 17.3306 20.7165 16.7291 23.3962 14.8205C23.3853 14.5963 23.3744 14.3666 23.3634 14.1259ZM23.7517 15.7775C21.1869 17.5002 17.5283 18.1236 13.9134 18.2822C16.3579 20.415 18.797 21.2955 20.8751 21.5142C23.347 21.7767 25.3322 21.0384 26.0595 20.4314C26.7705 19.8408 27.0165 19.365 27.0603 18.9986C27.0986 18.6267 26.9509 18.2713 26.6119 17.8775C25.9939 17.1447 24.7798 16.4338 23.7517 15.7775Z" fill="#0070C0"/></svg>';
};

$pricing_json = ! empty( $pricing_payload ) ? wp_json_encode( $pricing_payload ) : '';

ob_start();
?>
	<div
		class="fusion-product__quote-box"
		data-fusion-quote
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		data-product-type="<?php echo esc_attr( $product_type ); ?>"
		<?php echo $pricing_json ? ' data-fusion-pricing="' . esc_attr( $pricing_json ) . '"' : ''; ?>
		<?php echo ! empty( $pricing_payload ) ? ' data-fusion-origins="' . esc_attr( wp_json_encode( $pricing_payload ) ) . '"' : ''; ?>
		<?php echo $trip_dates_json ? ' data-fusion-dates="' . esc_attr( $trip_dates_json ) . '"' : ''; ?>
		<?php echo null !== $default_trip_date_index ? ' data-default-date="' . esc_attr( $default_trip_date_index ) . '"' : ''; ?>
		<?php echo $default_origin_location ? ' data-default-origin="' . esc_attr( $default_origin_location ) . '"' : ''; ?>
	>
	<div class="fusion-product__quote-content">
		<?php if ( ! $requires_quote && ! empty( $pricing_payload ) ) : ?>
			<?php if ( count( $pricing_payload ) > 1 ) : ?>
				<div class="fusion-product__pricing-table">
					<?php foreach ( $pricing_options as $option ) : ?>
						<div class="fusion-product__pricing-block">
							<p class="fusion-product__pricing-location"><?php echo esc_html( $option['location'] ); ?></p>
							<?php if ( ! empty( $option['price_html'] ) ) : ?>
								<div class="fusion-product__pricing-simple">
									<?php echo wp_kses_post( $option['price_html'] ); ?>
								</div>
							<?php else : ?>
								<?php
								$has_children = isset( $option['children'] ) && (float) $option['children'] !== (float) $option['adults'];
								?>
								<div class="fusion-product__pricing-row">
									<div class="fusion-product__pricing-col">
										<span class="fusion-product__pricing-label">
											<?php echo esc_html( $has_children ? __( 'Adultos', 'blankslate' ) : __( 'Precio', 'blankslate' ) ); ?>
										</span>
										<strong class="fusion-product__pricing-value">
											<?php echo wp_kses_post( wc_price( (float) $option['adults'], array( 'currency' => $option['currency'] ) ) ); ?>
										</strong>
									</div>
									<?php if ( $has_children ) : ?>
										<div class="fusion-product__pricing-col">
											<span class="fusion-product__pricing-label"><?php esc_html_e( 'Menores', 'blankslate' ); ?></span>
											<strong class="fusion-product__pricing-value">
												<?php echo wp_kses_post( wc_price( (float) $option['children'], array( 'currency' => $option['currency'] ) ) ); ?>
											</strong>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<button class="fusion-product__quote-button fusion-product__quote-button--outline" type="button" data-fusion-open-quote>
				<?php esc_html_e( 'Cotiza tu entrada', 'blankslate' ); ?>
			</button>
			<p class="fusion-product__quote-disclaimer">
				<?php esc_html_e( '*Se aplica precio regular: adultos (13+), menores (3-12).', 'blankslate' ); ?>
			</p>
			<?php elseif ( $whatsapp_number ) : ?>
			<?php
			$whatsapp_url = sprintf(
				'https://wa.me/%1$s%2$s',
				rawurlencode( preg_replace( '/[^0-9]/', '', $whatsapp_number ) ),
				$whatsapp_message ? '?text=' . rawurlencode( $whatsapp_message ) : ''
			);
			?>
			<a class="fusion-product__quote-button" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.0416 2.25C6.90458 2.25 2.75 6.1875 2.75 11.2438C2.75 13.55 3.61458 15.6187 5.07292 17.1854L4.25 21.75L9 19.8583C9.95313 20.1479 10.9812 20.2438 12.0416 20.2438C17.1854 20.2438 21.3333 16.3062 21.3333 11.25C21.3333 6.19375 17.1771 2.25 12.0416 2.25ZM12.0416 18.8375C11.1229 18.8375 10.2292 18.7271 9.37917 18.4958L6.73958 19.5437L7.34792 16.8041C5.975 15.5458 5.08333 13.8041 5.08333 11.8875C5.08333 7.49167 8.9875 4.20312 13.0417 4.20312C17.0958 4.20312 21 7.49167 21 11.8875C21 16.2833 17.0958 18.8375 12.0416 18.8375Z" fill="white"/><path d="M16.1665 14.8521L14.7458 14.7271C14.5458 14.7104 14.3521 14.7792 14.2021 14.9167L13.2687 15.7583C11.9042 15.0583 10.7417 14.0687 9.87083 12.8729L10.7917 12.0167C10.9354 11.8771 11.0021 11.6812 10.9792 11.4854L10.8271 10.0979C10.7833 9.69375 10.45 9.38958 10.0396 9.38958H9.11042C8.64792 9.38958 8.25833 9.77708 8.28125 10.2396C8.47708 14.1354 11.4687 17.3229 15.2542 17.6396C15.7167 17.6771 16.1042 17.2917 16.1042 16.8229V15.9187C16.1146 15.5437 15.8292 15.2146 15.45 15.1792L16.1665 14.8521Z" fill="white"/></svg>
				<?php esc_html_e( 'Cotiza por WhatsApp', 'blankslate' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
<?php
$quote_box_markup = ob_get_clean();
?>

<div class="fusion-product" data-fusion-product>
	<section class="fusion-product__gallery">
		<div class="fusion-product__gallery-main" data-fusion-gallery>
			<?php foreach ( $gallery_images as $index => $image ) : ?>
				<div class="fusion-product__gallery-frame<?php echo 0 === $index ? ' is-active' : ''; ?>" data-fusion-gallery-frame>
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
				</div>
			<?php endforeach; ?>

			<div class="fusion-product__gallery-controls">
				<button class="fusion-product__gallery-nav fusion-product__gallery-nav--prev" type="button" aria-label="<?php esc_attr_e( 'Imagen anterior', 'blankslate' ); ?>" data-fusion-gallery-prev>
					<svg width="24" height="36" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.7472 1.50415L16.5702 -0.708374L0.599304 15.5208C0.215011 15.9173 0 16.4478 0 17C0 17.5522 0.215011 18.0827 0.599304 18.4791L16.5702 34.7083L18.7472 32.4937L3.50137 17L18.7472 1.50415Z" fill="white"/></svg>
				</button>
				<button class="fusion-product__gallery-nav fusion-product__gallery-nav--next" type="button" aria-label="<?php esc_attr_e( 'Siguiente imagen', 'blankslate' ); ?>" data-fusion-gallery-next>
					<svg width="24" height="36" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.99983 34.4959L7.17686 36.7084L23.1477 20.4792C23.5321 20.0827 23.7471 19.5522 23.7471 19C23.7471 18.4478 23.5321 17.9173 23.1477 17.5209L7.17686 1.29166L4.99983 3.50626L20.2457 19L4.99983 34.4959Z" fill="#3F3F3F" fill-opacity="0.78"/></svg>
				</button>
			</div>
		</div>

		<div class="fusion-product__gallery-thumbs" data-fusion-gallery-thumbs>
			<?php foreach ( $gallery_images as $index => $image ) : ?>
				<button class="fusion-product__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-fusion-gallery-thumb="<?php echo esc_attr( $index ); ?>">
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
				</button>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="fusion-product__content">
		<div class="fusion-product__content-main">
			<?php if ( $feature_badges ) : ?>
				<div class="fusion-product__features">
					<?php foreach ( $feature_badges as $badge ) : ?>
						<div class="fusion-product__feature">
							<?php echo $render_feature_icon( sanitize_key( $badge['icon'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $badge['text'] ?? '' ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="fusion-product__title"><?php the_title(); ?></h1>

			<div class="fusion-product__sidebar fusion-product__sidebar--mobile">
				<?php echo $quote_box_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="fusion-product__description">
				<?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="fusion-product__divider"></div>

			<?php if ( $includes ) : ?>
				<div class="fusion-product__includes">
					<h2 class="fusion-product__section-title"><?php esc_html_e( '¿Qué incluye?', 'blankslate' ); ?></h2>
					<ul>
						<?php foreach ( $includes as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="fusion-product__divider"></div>
			<?php endif; ?>

			<div class="fusion-product__accordion" data-fusion-accordion>
				<div class="fusion-product__accordion-item">
					<button class="fusion-product__accordion-trigger" type="button">
						<div class="fusion-product__accordion-label">
							<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.3251 20.075C15.9736 20.4261 15.497 20.6233 15.0001 20.6233C14.5032 20.6233 14.0267 20.4261 13.6751 20.075L6.60262 13.005C6.25104 12.6532 6.05359 12.1762 6.05371 11.6789C6.05383 11.1816 6.2515 10.7047 6.60324 10.3531C6.95499 10.0015 7.43199 9.80408 7.92931 9.8042C8.42664 9.80432 8.90354 10.002 9.25512 10.3537L15.0001 16.0987L20.7451 10.3537C21.0986 10.012 21.5721 9.8228 22.0638 9.82684C22.5554 9.83088 23.0257 10.0278 23.3736 10.3753C23.7214 10.7228 23.9188 11.193 23.9233 11.6846C23.9278 12.1762 23.739 12.6499 23.3976 13.0037L16.3264 20.0762L16.3251 20.075Z" fill="#696969"/></svg>
							<span><?php esc_html_e( 'Recomendaciones y restricciones', 'blankslate' ); ?></span>
						</div>
						<span class="fusion-product__accordion-icon" aria-hidden="true">+</span>
					</button>
					<div class="fusion-product__accordion-content">
						<ul>
							<li><?php esc_html_e( 'Llegar 15 minutos antes de la hora de salida.', 'blankslate' ); ?></li>
							<li><?php esc_html_e( 'Usar bloqueador solar biodegradable.', 'blankslate' ); ?></li>
							<li><?php esc_html_e( 'No tocar a la vida marina durante la actividad.', 'blankslate' ); ?></li>
							<li><?php esc_html_e( 'Seguir las instrucciones del guía en todo momento.', 'blankslate' ); ?></li>
						</ul>
					</div>
				</div>

				<div class="fusion-product__accordion-item">
					<button class="fusion-product__accordion-trigger" type="button">
						<div class="fusion-product__accordion-label">
							<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.3251 20.075C15.9736 20.4261 15.497 20.6233 15.0001 20.6233C14.5032 20.6233 14.0267 20.4261 13.6751 20.075L6.60262 13.005C6.25104 12.6532 6.05359 12.1762 6.05371 11.6789C6.05383 11.1816 6.2515 10.7047 6.60324 10.3531C6.95499 10.0015 7.43199 9.80408 7.92931 9.8042C8.42664 9.80432 8.90354 10.002 9.25512 10.3537L15.0001 16.0987L20.7451 10.3537C21.0986 10.012 21.5721 9.8228 22.0638 9.82684C22.5554 9.83088 23.0257 10.0278 23.3736 10.3753C23.7214 10.7228 23.9188 11.193 23.9233 11.6846C23.9278 12.1762 23.739 12.6499 23.3976 13.0037L16.3264 20.0762L16.3251 20.075Z" fill="#696969"/></svg>
							<span><?php esc_html_e( 'Días de operación', 'blankslate' ); ?></span>
						</div>
						<span class="fusion-product__accordion-icon" aria-hidden="true">+</span>
					</button>
					<div class="fusion-product__accordion-content">
						<p><?php esc_html_e( 'Opera de mayo a septiembre, sujeto a condiciones climáticas y disponibilidad.', 'blankslate' ); ?></p>
					</div>
				</div>
			</div>

			<div class="fusion-product__divider"></div>

			<?php if ( $recommended_products ) : ?>
				<div class="fusion-product__recommended">
					<h2 class="fusion-product__section-title"><?php esc_html_e( 'Recomendados', 'blankslate' ); ?></h2>
					<div class="fusion-product__recommended-grid">
						<?php foreach ( $recommended_products as $recommended ) : ?>
							<a href="<?php echo esc_url( $recommended['permalink'] ); ?>" class="fusion-product__recommended-card">
								<div class="fusion-product__recommended-media" style="background-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%), url('<?php echo esc_url( $recommended['image'] ); ?>');"></div>
								<div class="fusion-product__recommended-body">
									<span class="fusion-product__recommended-tag"><?php echo esc_html( $recommended['category'] ?: __( 'Experiencia destacada', 'blankslate' ) ); ?></span>
									<h3><?php echo esc_html( $recommended['name'] ); ?></h3>
									<p><?php echo esc_html( $recommended['description'] ); ?></p>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<aside class="fusion-product__sidebar fusion-product__sidebar--desktop">
			<?php echo $quote_box_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</aside>
	</section>
</div>

<?php if ( ! $requires_quote && ! empty( $pricing_payload ) ) : ?>
	<div
		class="fusion-product__quote-dialog"
		hidden
		data-fusion-quote-dialog
		data-product-id="<?php echo esc_attr( $product_id ); ?>"
		data-product-type="<?php echo esc_attr( $product_type ); ?>"
		data-fusion-pricing="<?php echo esc_attr( $pricing_json ); ?>"
		<?php echo ! empty( $pricing_payload ) ? ' data-fusion-origins="' . esc_attr( wp_json_encode( $pricing_payload ) ) . '"' : ''; ?>
		<?php echo $trip_dates_json ? ' data-fusion-dates="' . esc_attr( $trip_dates_json ) . '"' : ''; ?>
		<?php echo null !== $default_trip_date_index ? ' data-default-date="' . esc_attr( $default_trip_date_index ) . '"' : ''; ?>
		<?php echo $default_origin_location ? ' data-default-origin="' . esc_attr( $default_origin_location ) . '"' : ''; ?>
	>
		<div class="fusion-product__quote-dialog-overlay" data-fusion-close-quote></div>
		<div class="fusion-product__quote-dialog-content" role="dialog" aria-modal="true" aria-labelledby="fusion-quote-title">
			<button class="fusion-product__quote-dialog-close" type="button" aria-label="<?php esc_attr_e( 'Cerrar', 'blankslate' ); ?>" data-fusion-close-quote>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round"/><path d="M6 6L18 18" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
			<div class="fusion-product__quote-dialog-body">
				<h2 id="fusion-quote-title"><?php esc_html_e( 'Coticemos', 'blankslate' ); ?></h2>

				<?php if ( count( $pricing_payload ) > 1 ) : ?>
			<div class="fusion-product__origin fusion-product__origin--inline">
				<label for="fusion-product-origin-modal">
					<?php esc_html_e( 'Salida desde', 'blankslate' ); ?>
				</label>
				<select id="fusion-product-origin-modal" data-fusion-origin>
					<option value="">
						<?php esc_html_e( 'Selecciona un punto de salida', 'blankslate' ); ?>
					</option>
					<?php foreach ( $pricing_payload as $origin_option ) : ?>
						<option value="<?php echo esc_attr( $origin_option['location_key'] ?? $origin_option['value'] ?? $origin_option['location'] ); ?>" <?php selected( $default_origin_location, $origin_option['location_key'] ?? $origin_option['value'] ?? $origin_option['location'] ); ?>>
							<?php echo esc_html( $origin_option['location'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
				<?php endif; ?>

				<div class="fusion-product__quote-controls">
					<div class="fusion-product__quote-control" data-fusion-counter>
						<div class="fusion-product__quote-control-label">
							<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_adult_dialog)"><path d="M12.8928 10.2275C10.0048 10.1828 7.79864 7.86115 7.7791 5.11375C7.82681 2.25148 10.1663 0.0200715 12.8928 0C15.7834 0.109306 17.986 2.32186 18.0066 5.11375C17.9498 7.97828 15.6222 10.2081 12.8928 10.2275ZM17.0274 11.2883C20.6622 11.3308 22.5053 14.5192 22.5221 17.7076V25.5687H18.9588L18.9588 18.6598C18.872 18.1821 18.5485 18.0202 18.2521 18.067C17.9747 18.1109 17.721 18.3376 17.7075 18.6598V25.5687H7.80667V18.6598C7.73088 18.2052 7.44172 18.0304 7.1604 18.0481C6.85916 18.067 6.56693 18.3066 6.55542 18.6598V25.5687H3.04654V17.7076C3.02566 14.3096 5.17516 11.3135 8.56804 11.2883H17.0274Z" fill="#0070C0"/></g><defs><clipPath id="clip0_adult_dialog"><rect width="25.5687" height="25.5687" fill="white"/></clipPath></defs></svg>
							<span><?php esc_html_e( 'Adultos', 'blankslate' ); ?></span>
						</div>
						<div class="fusion-product__quote-counter">
							<button type="button" data-fusion-counter-down aria-label="<?php esc_attr_e( 'Restar adultos', 'blankslate' ); ?>">-</button>
							<span data-fusion-counter-value>2</span>
							<button type="button" data-fusion-counter-up aria-label="<?php esc_attr_e( 'Sumar adultos', 'blankslate' ); ?>">+</button>
						</div>
					</div>
					<div class="fusion-product__quote-control" data-fusion-counter>
						<div class="fusion-product__quote-control-label">
							<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.7844 10.6568C14.0478 10.6568 15.0716 9.63302 15.0716 8.36966C15.0716 7.1063 14.0478 6.08252 12.7844 6.08252C11.521 6.08252 10.4973 7.1063 10.4973 8.36966C10.4973 9.63302 11.521 10.6568 12.7844 10.6568Z" fill="#0070C0"/><path d="M18.3587 13.5154C18.3587 11.4669 16.6958 9.804 14.6473 9.804H10.9216C8.87307 9.804 7.21021 11.4669 7.21021 13.5154V16.374H9.54878V25.5687H12.7844H16.0201V16.374H18.3587V13.5154Z" fill="#0070C0"/><path d="M12.7844 3.43609C13.5754 3.43609 14.2158 2.79571 14.2158 2.00471C14.2158 1.21372 13.5754 0.573334 12.7844 0.573334C11.9934 0.573334 11.353 1.21372 11.353 2.00471C11.353 2.79571 11.9934 3.43609 12.7844 3.43609Z" fill="#0070C0"/></svg>
							<span><?php esc_html_e( 'Menores', 'blankslate' ); ?></span>
						</div>
						<div class="fusion-product__quote-counter">
							<button type="button" data-fusion-counter-down aria-label="<?php esc_attr_e( 'Restar menores', 'blankslate' ); ?>">-</button>
							<span data-fusion-counter-value>0</span>
							<button type="button" data-fusion-counter-up aria-label="<?php esc_attr_e( 'Sumar menores', 'blankslate' ); ?>">+</button>
						</div>
					</div>
				</div>
				<?php if ( $trip_dates ) : ?>
				<div class="fusion-product__quote-date" data-fusion-date-wrapper>
					<label class="fusion-product__quote-date-label" for="fusion-trip-date">
						<?php esc_html_e( 'Selecciona tu fecha', 'blankslate' ); ?>
					</label>
					<select class="fusion-product__quote-date-select" id="fusion-trip-date" name="viaje_fecha_idx" data-fusion-date>
						<option value="" <?php echo null === $default_trip_date_index ? 'selected' : ''; ?>>
							<?php esc_html_e( 'Elige una fecha disponible', 'blankslate' ); ?>
						</option>
						<?php foreach ( $trip_dates as $date_entry ) :
							$disabled      = ! empty( $date_entry['is_closed'] );
							$status_label  = $disabled ? __( 'Sin cupo', 'blankslate' ) : '';
							$option_label  = $date_entry['label'];
						?>
							<option value="<?php echo esc_attr( $date_entry['index'] ); ?>" <?php selected( $default_trip_date_index, $date_entry['index'] ); ?> <?php disabled( $disabled ); ?> data-status="<?php echo esc_attr( $date_entry['status'] ); ?>">
								<?php echo esc_html( $option_label ); ?><?php echo $status_label ? ' — ' . esc_html( $status_label ) : ''; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>
				<div class="fusion-product__quote-total">
					<div class="fusion-product__quote-total-row">
						<span><?php esc_html_e( 'Total estimado', 'blankslate' ); ?></span>
						<strong data-fusion-quote-total><?php echo esc_html( get_woocommerce_currency_symbol( $pricing_payload[0]['currency'] ?? get_woocommerce_currency() ) . '0.00' ); ?></strong>
					</div>
					<p><?php esc_html_e( '*No incluye impuesto portuario (adultos $20 USD, menores $5 USD).', 'blankslate' ); ?></p>
				</div>

				<div class="fusion-product__quote-actions">
					<button class="fusion-product__quote-button fusion-product__quote-button--solid" type="button" data-fusion-add-to-cart>
						<?php esc_html_e( 'Agregar al carrito', 'blankslate' ); ?>
					</button>
					<button class="fusion-product__quote-button fusion-product__quote-button--solid" type="button" data-fusion-buy-now>
						<?php esc_html_e( 'Pagar ahora', 'blankslate' ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
do_action( 'woocommerce_after_single_product' );

if ( $did_setup_post ) {
	wp_reset_postdata();
}

get_footer( 'shop' );
