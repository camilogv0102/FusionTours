<?php
/**
 * Plugin Name: ACF Campos FAQs (MU)
 * Description: Campos administrables para la página de preguntas frecuentes.
 * Author: SNC DESIGNS
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! function_exists( 'blankslate_faq_default_hero_image' ) ) {
	function blankslate_faq_default_hero_image() {
		$upload_dir = wp_upload_dir();
		$hero_url   = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/faqs.png';

		return array(
			'ID'          => 0,
			'id'          => 0,
			'url'         => $hero_url,
			'link'        => $hero_url,
			'alt'         => 'Colonia de flamencos frente a un fondo verde y cielo azul',
			'title'       => 'Preguntas frecuentes — Fusion Tours',
			'description' => '',
			'caption'     => '',
			'mime_type'   => 'image/png',
			'type'        => 'image',
			'subtype'     => 'png',
			'sizes'       => array(),
		);
	}
}

if ( ! function_exists( 'blankslate_faq_default_intro_text' ) ) {
	function blankslate_faq_default_intro_text() {
		return 'Encuentra respuestas rápidas a las dudas más comunes sobre nuestros tours, reservaciones y políticas de viaje.';
	}
}

if ( ! function_exists( 'blankslate_faq_default_items' ) ) {
	function blankslate_faq_default_items() {
		return array(
			array(
				'pregunta'  => '¿Cómo puedo reservar un tour con Fusion Tours?',
				'respuesta' => 'Puedes reservar directamente en nuestro sitio web seleccionando tu tour favorito y completando el formulario de pago. También podemos ayudarte vía WhatsApp o correo.',
			),
			array(
				'pregunta'  => '¿Cuál es la política de cancelación?',
				'respuesta' => 'Las cancelaciones con al menos 48 horas de anticipación reciben reembolso completo. Con menos tiempo, aplican cargos por logística y disponibilidad.',
			),
			array(
				'pregunta'  => '¿Los tours incluyen transporte?',
				'respuesta' => 'La mayoría de nuestros tours incluyen transporte redondo desde puntos designados en Riviera Maya. Revisa la descripción de cada tour para confirmar.',
			),
			array(
				'pregunta'  => '¿Qué debo llevar el día del tour?',
				'respuesta' => 'Recomendamos calzado cómodo, bloqueador biodegradable, traje de baño y una identificación oficial. Dependiendo del tour, podríamos sugerir artículos adicionales.',
			),
			array(
				'pregunta'  => '¿Puedo modificar la fecha de mi tour?',
				'respuesta' => 'Sí, sujetándonos a disponibilidad. Contáctanos con al menos 24 horas de anticipación para reprogramar sin cargos adicionales.',
			),
			array(
				'pregunta'  => '¿Ofrecen tarifas especiales para grupos?',
				'respuesta' => 'Contamos con tarifas preferenciales para grupos y agencias. Escríbenos a nuestro correo de ventas o vía WhatsApp para recibir una cotización personalizada.',
			),
		);
	}
}

if ( ! function_exists( 'blankslate_faq_is_faq_template_post' ) ) {
	function blankslate_faq_is_faq_template_post( $post_id ) {
		$post_id = (int) $post_id;
		if ( ! $post_id ) {
			return false;
		}
		return 'template-faqs.php' === get_post_meta( $post_id, '_wp_page_template', true );
	}
}

if ( ! function_exists( 'blankslate_faq_is_empty_value' ) ) {
	function blankslate_faq_is_empty_value( $value ) {
		if ( null === $value || '' === $value || false === $value ) {
			return true;
		}

		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( ! blankslate_faq_is_empty_value( $item ) ) {
					return false;
				}
			}
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'blankslate_faq_apply_default_value' ) ) {
	function blankslate_faq_apply_default_value( $value, $default ) {
		if ( blankslate_faq_is_empty_value( $value ) ) {
			return $default;
		}
		return $value;
	}
}

if ( ! function_exists( 'blankslate_faq_default_items_group' ) ) {
	function blankslate_faq_default_items_group() {
		$defaults = blankslate_faq_default_items();

		$group = array();
		$index = 1;
		foreach ( $defaults as $item ) {
			$group[ 'item_' . $index ] = array(
				'pregunta'  => $item['pregunta'],
				'respuesta' => $item['respuesta'],
			);
			$index++;
		}

		return $group;
	}
}

if ( ! function_exists( 'blankslate_faq_merge_group_defaults' ) ) {
	function blankslate_faq_merge_group_defaults( $value, $default_group ) {
		if ( blankslate_faq_is_empty_value( $value ) ) {
			return $default_group;
		}

		if ( ! is_array( $value ) ) {
			return $default_group;
		}

		$merged = $default_group;
		foreach ( $value as $key => $maybe_data ) {
			if ( isset( $default_group[ $key ] ) && is_array( $default_group[ $key ] ) && is_array( $maybe_data ) ) {
				$merged[ $key ] = blankslate_faq_merge_group_defaults( $maybe_data, $default_group[ $key ] );
			} else {
				$merged[ $key ] = $maybe_data;
			}
		}

		return $merged;
	}
}

if ( ! function_exists( 'blankslate_faq_normalize_items' ) ) {
	function blankslate_faq_normalize_items( $value ) {
		$defaults = blankslate_faq_default_items();

		if ( blankslate_faq_is_empty_value( $value ) ) {
			return $defaults;
		}

		if ( isset( $value[0] ) && isset( $value[0]['pregunta'] ) ) {
			return $value;
		}

		if ( ! is_array( $value ) ) {
			return $defaults;
		}

		$items  = array();
		$index  = 0;
		$cursor = 1;

		while ( $cursor <= count( $defaults ) ) {
			$key           = 'item_' . $cursor;
			$default_item  = isset( $defaults[ $index ] ) ? $defaults[ $index ] : array( 'pregunta' => '', 'respuesta' => '' );
			$current_value = isset( $value[ $key ] ) && is_array( $value[ $key ] ) ? $value[ $key ] : array();

			$question = isset( $current_value['pregunta'] ) ? trim( (string) $current_value['pregunta'] ) : '';
			$answer   = isset( $current_value['respuesta'] ) ? trim( (string) $current_value['respuesta'] ) : '';

			if ( '' === $question && '' === $answer ) {
				$question = $default_item['pregunta'];
				$answer   = $default_item['respuesta'];
			}

			if ( '' === $question && '' === $answer ) {
				$cursor++;
				$index++;
				continue;
			}

			$items[] = array(
				'pregunta'  => $question,
				'respuesta' => $answer,
			);

			$cursor++;
			$index++;
		}

		return ! empty( $items ) ? $items : $defaults;
	}
}

if ( ! function_exists( 'blankslate_faq_group_from_items' ) ) {
	function blankslate_faq_group_from_items( $items ) {
		$defaults   = blankslate_faq_default_items_group();
		$normalized = blankslate_faq_normalize_items( $items );

		$group = array();
		$index = 1;

		foreach ( $normalized as $item ) {
			$key                = 'item_' . $index;
			$default_item       = isset( $defaults[ $key ] ) ? $defaults[ $key ] : array( 'pregunta' => '', 'respuesta' => '' );
			$group[ $key ] = array(
				'pregunta'  => isset( $item['pregunta'] ) ? $item['pregunta'] : $default_item['pregunta'],
				'respuesta' => isset( $item['respuesta'] ) ? $item['respuesta'] : $default_item['respuesta'],
			);
			$index++;
		}

		return blankslate_faq_merge_group_defaults( $group, $defaults );
	}
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'    => 'group_faq_settings',
				'title'  => 'FAQs — Ajustes',
				'fields' => array(
					array(
						'key'       => 'faq_tab_hero',
						'label'     => 'Hero',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'faq_hero_imagen',
						'label'         => 'Imagen de encabezado',
						'name'          => 'faq_hero_imagen',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'large',
						'library'       => 'all',
					),
					array(
						'key'           => 'faq_hero_titulo',
						'label'         => 'Título',
						'name'          => 'faq_hero_titulo',
						'type'          => 'text',
						'default_value' => 'PREGUNTAS FRECUENTES',
					),
					array(
						'key'           => 'faq_intro_texto',
						'label'         => 'Introducción',
						'name'          => 'faq_intro_texto',
						'type'          => 'textarea',
						'rows'          => 3,
						'default_value' => blankslate_faq_default_intro_text(),
					),
					array(
						'key'       => 'faq_tab_listado',
						'label'     => 'Listado de preguntas',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'        => 'faq_items',
						'label'      => 'Preguntas & respuestas',
						'name'       => 'faq_items',
						'type'       => 'group',
						'layout'     => 'block',
						'sub_fields' => array(
							array(
								'key'        => 'faq_items_item_1',
								'label'      => 'Pregunta 1',
								'name'       => 'item_1',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_1_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Cómo puedo reservar un tour con Fusion Tours?',
									),
									array(
										'key'           => 'faq_items_item_1_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'Puedes reservar directamente en nuestro sitio web seleccionando tu tour favorito y completando el formulario de pago. También podemos ayudarte vía WhatsApp o correo.',
									),
								),
							),
							array(
								'key'        => 'faq_items_item_2',
								'label'      => 'Pregunta 2',
								'name'       => 'item_2',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_2_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Cuál es la política de cancelación?',
									),
									array(
										'key'           => 'faq_items_item_2_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'Las cancelaciones con al menos 48 horas de anticipación reciben reembolso completo. Con menos tiempo, aplican cargos por logística y disponibilidad.',
									),
								),
							),
							array(
								'key'        => 'faq_items_item_3',
								'label'      => 'Pregunta 3',
								'name'       => 'item_3',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_3_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Los tours incluyen transporte?',
									),
									array(
										'key'           => 'faq_items_item_3_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'La mayoría de nuestros tours incluyen transporte redondo desde puntos designados en Riviera Maya. Revisa la descripción de cada tour para confirmar.',
									),
								),
							),
							array(
								'key'        => 'faq_items_item_4',
								'label'      => 'Pregunta 4',
								'name'       => 'item_4',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_4_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Qué debo llevar el día del tour?',
									),
									array(
										'key'           => 'faq_items_item_4_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'Recomendamos calzado cómodo, bloqueador biodegradable, traje de baño y una identificación oficial. Dependiendo del tour, podríamos sugerir artículos adicionales.',
									),
								),
							),
							array(
								'key'        => 'faq_items_item_5',
								'label'      => 'Pregunta 5',
								'name'       => 'item_5',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_5_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Puedo modificar la fecha de mi tour?',
									),
									array(
										'key'           => 'faq_items_item_5_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'Sí, sujetándonos a disponibilidad. Contáctanos con al menos 24 horas de anticipación para reprogramar sin cargos adicionales.',
									),
								),
							),
							array(
								'key'        => 'faq_items_item_6',
								'label'      => 'Pregunta 6',
								'name'       => 'item_6',
								'type'       => 'group',
								'layout'     => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'faq_items_item_6_pregunta',
										'label'         => 'Pregunta',
										'name'          => 'pregunta',
										'type'          => 'text',
										'default_value' => '¿Ofrecen tarifas especiales para grupos?',
									),
									array(
										'key'           => 'faq_items_item_6_respuesta',
										'label'         => 'Respuesta',
										'name'          => 'respuesta',
										'type'          => 'textarea',
										'rows'          => 4,
										'default_value' => 'Contamos con tarifas preferenciales para grupos y agencias. Escríbenos a nuestro correo de ventas o vía WhatsApp para recibir una cotización personalizada.',
									),
								),
							),
						),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'template-faqs.php',
						),
					),
				),
			)
		);
	}
);

add_filter(
	'acf/load_value/name=faq_hero_imagen',
	function ( $value, $post_id, $field ) {
		if ( blankslate_faq_is_faq_template_post( $post_id ) ) {
			return blankslate_faq_apply_default_value( $value, blankslate_faq_default_hero_image() );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=faq_hero_titulo',
	function ( $value, $post_id, $field ) {
		if ( blankslate_faq_is_faq_template_post( $post_id ) ) {
			return blankslate_faq_apply_default_value( $value, 'PREGUNTAS FRECUENTES' );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=faq_intro_texto',
	function ( $value, $post_id, $field ) {
		if ( blankslate_faq_is_faq_template_post( $post_id ) ) {
			return blankslate_faq_apply_default_value( $value, blankslate_faq_default_intro_text() );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=faq_items',
	function ( $value, $post_id, $field ) {
		$default_group = blankslate_faq_default_items_group();
		if ( blankslate_faq_is_faq_template_post( $post_id ) ) {
			$value = blankslate_faq_merge_group_defaults( $value, $default_group );
		}

		if ( is_admin() ) {
			return $value;
		}

		return blankslate_faq_normalize_items( $value );
	},
	10,
	3
);
