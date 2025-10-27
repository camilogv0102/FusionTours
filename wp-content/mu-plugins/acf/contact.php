<?php
/**
 * Plugin Name: ACF Campos Contacto (MU)
 * Description: Campos administrables para la página de contacto.
 * Author: SNC DESIGNS
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! function_exists( 'blankslate_contact_default_hero_image' ) ) {
	function blankslate_contact_default_hero_image() {
		$upload_dir = wp_upload_dir();
		$hero_url   = trailingslashit( $upload_dir['baseurl'] ) . 'snc-media/contact.png';

		return array(
			'ID'          => 0,
			'id'          => 0,
			'url'         => $hero_url,
			'link'        => $hero_url,
			'alt'         => 'Personas en cuatrimotos recorriendo la selva',
			'title'       => 'Contacto — Fusion Tours',
			'description' => '',
			'caption'     => '',
			'mime_type'   => 'image/png',
			'type'        => 'image',
			'subtype'     => 'png',
			'sizes'       => array(),
		);
	}
}

if ( ! function_exists( 'blankslate_contact_default_info_blocks' ) ) {
	function blankslate_contact_default_info_blocks() {
		return array(
			array(
				'titulo' => 'EMAILS:',
				'items'  => array(
					array(
						'label'  => '',
						'valor'  => 'RESERVATIONSFUSIONTOURSRVM@GMAIL.COM',
						'enlace' => 'mailto:reservationsfusiontoursrvm@gmail.com',
					),
					array(
						'label'  => '',
						'valor'  => 'FUSIONTOURSRVM2025@GMAIL.COM',
						'enlace' => 'mailto:fusiontoursrvm2025@gmail.com',
					),
				),
			),
			array(
				'titulo' => 'WHATSAPP - VENTAS/SALES:',
				'items'  => array(
					array(
						'label'  => '',
						'valor'  => '+52 984-254-9858',
						'enlace' => 'https://wa.me/529842549858',
					),
					array(
						'label'  => '',
						'valor'  => '+52 322-229-0911',
						'enlace' => 'https://wa.me/523222290911',
					),
				),
			),
			array(
				'titulo' => 'ATENCIÓN AL CLIENTE / CUSTOMER SERVICE',
				'items'  => array(
					array(
						'label'  => '',
						'valor'  => '+52 984-131-2269',
						'enlace' => 'tel:+529841312269',
					),
				),
			),
		);
	}
}

if ( ! function_exists( 'blankslate_contact_default_social_links' ) ) {
	function blankslate_contact_default_social_links() {
		return array(
			array(
				'nombre' => 'Facebook',
				'url'    => 'https://www.facebook.com/fusiontoursrvm',
			),
			array(
				'nombre' => 'WhatsApp',
				'url'    => 'https://wa.me/529842549858',
			),
			array(
				'nombre' => 'Twitter',
				'url'    => 'https://x.com/FusionToursRVM',
			),
			array(
				'nombre' => 'Instagram',
				'url'    => 'https://www.instagram.com/fusiontoursrvm',
			),
		);
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
				'key'    => 'group_contacto_settings',
				'title'  => 'Contacto — Ajustes',
				'fields' => array(
					array(
						'key'       => 'contacto_tab_hero',
						'label'     => 'Hero',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'contacto_hero_imagen',
						'label'         => 'Imagen de encabezado',
						'name'          => 'contacto_hero_imagen',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'large',
						'library'       => 'all',
					),
					array(
						'key'           => 'contacto_hero_titulo',
						'label'         => 'Título',
						'name'          => 'contacto_hero_titulo',
						'type'          => 'text',
						'default_value' => 'CONTÁCTANOS',
					),
					array(
						'key'       => 'contacto_tab_formulario',
						'label'     => 'Formulario',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'contacto_form_shortcode',
						'label'         => 'Shortcode del formulario',
						'name'          => 'contacto_form_shortcode',
						'type'          => 'text',
						'default_value' => '[elementor-template id="67"]',
						'instructions'  => 'Ingresa el shortcode del formulario a mostrar.',
					),
					array(
						'key'       => 'contacto_tab_info',
						'label'     => 'Información de contacto',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'contacto_info_grupos',
						'label'         => 'Bloques de información',
						'name'          => 'contacto_info_grupos',
						'type'          => 'group',
						'layout'        => 'block',
						'sub_fields'    => array(
							array(
								'key'    => 'contacto_info_grupo_emails',
								'label'  => 'Bloque: Emails',
								'name'   => 'emails',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_info_emails_titulo',
										'label'         => 'Título',
										'name'          => 'titulo',
										'type'          => 'text',
										'default_value' => 'EMAILS:',
									),
									array(
										'key'    => 'contacto_info_emails_item_1',
										'label'  => 'Primer elemento',
										'name'   => 'item_1',
										'type'   => 'group',
										'layout' => 'block',
										'sub_fields' => array(
											array(
												'key'           => 'contacto_info_emails_item_1_label',
												'label'         => 'Etiqueta',
												'name'          => 'label',
												'type'          => 'text',
												'default_value' => '',
											),
											array(
												'key'           => 'contacto_info_emails_item_1_valor',
												'label'         => 'Valor',
												'name'          => 'valor',
												'type'          => 'text',
												'default_value' => 'RESERVATIONSFUSIONTOURSRVM@GMAIL.COM',
											),
											array(
												'key'           => 'contacto_info_emails_item_1_enlace',
												'label'         => 'URL',
												'name'          => 'enlace',
												'type'          => 'url',
												'default_value' => 'mailto:reservationsfusiontoursrvm@gmail.com',
											),
										),
									),
									array(
										'key'    => 'contacto_info_emails_item_2',
										'label'  => 'Segundo elemento',
										'name'   => 'item_2',
										'type'   => 'group',
										'layout' => 'block',
										'sub_fields' => array(
											array(
												'key'           => 'contacto_info_emails_item_2_label',
												'label'         => 'Etiqueta',
												'name'          => 'label',
												'type'          => 'text',
												'default_value' => '',
											),
											array(
												'key'           => 'contacto_info_emails_item_2_valor',
												'label'         => 'Valor',
												'name'          => 'valor',
												'type'          => 'text',
												'default_value' => 'FUSIONTOURSRVM2025@GMAIL.COM',
											),
											array(
												'key'           => 'contacto_info_emails_item_2_enlace',
												'label'         => 'URL',
												'name'          => 'enlace',
												'type'          => 'url',
												'default_value' => 'mailto:fusiontoursrvm2025@gmail.com',
											),
										),
									),
								),
							),
							array(
								'key'    => 'contacto_info_grupo_ventas',
								'label'  => 'Bloque: WhatsApp Ventas',
								'name'   => 'ventas',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_info_ventas_titulo',
										'label'         => 'Título',
										'name'          => 'titulo',
										'type'          => 'text',
										'default_value' => 'WHATSAPP - VENTAS/SALES:',
									),
									array(
										'key'    => 'contacto_info_ventas_item_1',
										'label'  => 'Primer elemento',
										'name'   => 'item_1',
										'type'   => 'group',
										'layout' => 'block',
										'sub_fields' => array(
											array(
												'key'           => 'contacto_info_ventas_item_1_label',
												'label'         => 'Etiqueta',
												'name'          => 'label',
												'type'          => 'text',
												'default_value' => '',
											),
											array(
												'key'           => 'contacto_info_ventas_item_1_valor',
												'label'         => 'Valor',
												'name'          => 'valor',
												'type'          => 'text',
												'default_value' => '+52 984-254-9858',
											),
											array(
												'key'           => 'contacto_info_ventas_item_1_enlace',
												'label'         => 'URL',
												'name'          => 'enlace',
												'type'          => 'url',
												'default_value' => 'https://wa.me/529842549858',
											),
										),
									),
									array(
										'key'    => 'contacto_info_ventas_item_2',
										'label'  => 'Segundo elemento',
										'name'   => 'item_2',
										'type'   => 'group',
										'layout' => 'block',
										'sub_fields' => array(
											array(
												'key'           => 'contacto_info_ventas_item_2_label',
												'label'         => 'Etiqueta',
												'name'          => 'label',
												'type'          => 'text',
												'default_value' => '',
											),
											array(
												'key'           => 'contacto_info_ventas_item_2_valor',
												'label'         => 'Valor',
												'name'          => 'valor',
												'type'          => 'text',
												'default_value' => '+52 322-229-0911',
											),
											array(
												'key'           => 'contacto_info_ventas_item_2_enlace',
												'label'         => 'URL',
												'name'          => 'enlace',
												'type'          => 'url',
												'default_value' => 'https://wa.me/523222290911',
											),
										),
									),
								),
							),
							array(
								'key'    => 'contacto_info_grupo_servicio',
								'label'  => 'Bloque: Atención al cliente',
								'name'   => 'servicio',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_info_servicio_titulo',
										'label'         => 'Título',
										'name'          => 'titulo',
										'type'          => 'text',
										'default_value' => 'ATENCIÓN AL CLIENTE / CUSTOMER SERVICE',
									),
									array(
										'key'    => 'contacto_info_servicio_item_1',
										'label'  => 'Primer elemento',
										'name'   => 'item_1',
										'type'   => 'group',
										'layout' => 'block',
										'sub_fields' => array(
											array(
												'key'           => 'contacto_info_servicio_item_1_label',
												'label'         => 'Etiqueta',
												'name'          => 'label',
												'type'          => 'text',
												'default_value' => '',
											),
											array(
												'key'           => 'contacto_info_servicio_item_1_valor',
												'label'         => 'Valor',
												'name'          => 'valor',
												'type'          => 'text',
												'default_value' => '+52 984-131-2269',
											),
											array(
												'key'           => 'contacto_info_servicio_item_1_enlace',
												'label'         => 'URL',
												'name'          => 'enlace',
												'type'          => 'url',
												'default_value' => 'tel:+529841312269',
											),
										),
									),
								),
							),
						),
					),
					array(
						'key'       => 'contacto_tab_social',
						'label'     => 'Redes sociales',
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'          => 'contacto_social',
						'label'        => 'Perfiles',
						'name'         => 'contacto_social',
						'type'         => 'group',
						'layout'       => 'block',
						'sub_fields'   => array(
							array(
								'key'    => 'contacto_social_facebook',
								'label'  => 'Facebook',
								'name'   => 'facebook',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_social_facebook_nombre',
										'label'         => 'Nombre',
										'name'          => 'nombre',
										'type'          => 'text',
										'default_value' => 'Facebook',
									),
									array(
										'key'           => 'contacto_social_facebook_url',
										'label'         => 'URL',
										'name'          => 'url',
										'type'          => 'url',
										'default_value' => 'https://www.facebook.com/fusiontoursrvm',
									),
								),
							),
							array(
								'key'    => 'contacto_social_whatsapp',
								'label'  => 'WhatsApp',
								'name'   => 'whatsapp',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_social_whatsapp_nombre',
										'label'         => 'Nombre',
										'name'          => 'nombre',
										'type'          => 'text',
										'default_value' => 'WhatsApp',
									),
									array(
										'key'           => 'contacto_social_whatsapp_url',
										'label'         => 'URL',
										'name'          => 'url',
										'type'          => 'url',
										'default_value' => 'https://wa.me/529842549858',
									),
								),
							),
							array(
								'key'    => 'contacto_social_twitter',
								'label'  => 'Twitter',
								'name'   => 'twitter',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_social_twitter_nombre',
										'label'         => 'Nombre',
										'name'          => 'nombre',
										'type'          => 'text',
										'default_value' => 'Twitter',
									),
									array(
										'key'           => 'contacto_social_twitter_url',
										'label'         => 'URL',
										'name'          => 'url',
										'type'          => 'url',
										'default_value' => 'https://x.com/FusionToursRVM',
									),
								),
							),
							array(
								'key'    => 'contacto_social_instagram',
								'label'  => 'Instagram',
								'name'   => 'instagram',
								'type'   => 'group',
								'layout' => 'block',
								'sub_fields' => array(
									array(
										'key'           => 'contacto_social_instagram_nombre',
										'label'         => 'Nombre',
										'name'          => 'nombre',
										'type'          => 'text',
										'default_value' => 'Instagram',
									),
									array(
										'key'           => 'contacto_social_instagram_url',
										'label'         => 'URL',
										'name'          => 'url',
										'type'          => 'url',
										'default_value' => 'https://www.instagram.com/fusiontoursrvm',
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
							'value'    => 'template-contact.php',
						),
					),
				),
			)
		);
	}
);

if ( ! function_exists( 'blankslate_contact_is_contact_template_post' ) ) {
	/**
	 * Checks whether the given post uses the contact template.
	 *
	 * @param mixed $post_id Post ID passed by ACF.
	 * @return bool
	 */
	function blankslate_contact_is_contact_template_post( $post_id ) {
		$post_id = (int) $post_id;
		if ( ! $post_id ) {
			return false;
		}
		return 'template-contact.php' === get_post_meta( $post_id, '_wp_page_template', true );
	}
}

if ( ! function_exists( 'blankslate_contact_is_empty_value' ) ) {
	/**
	 * Determines if the provided value should be considered empty for defaults.
	 *
	 * @param mixed $value Value to evaluate.
	 * @return bool
	 */
	function blankslate_contact_is_empty_value( $value ) {
		if ( null === $value || '' === $value || false === $value ) {
			return true;
		}

		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( ! blankslate_contact_is_empty_value( $item ) ) {
					return false;
				}
			}
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'blankslate_contact_apply_default_value' ) ) {
	/**
	 * Returns the default when value is empty.
	 *
	 * @param mixed $value   Stored value.
	 * @param mixed $default Default fallback.
	 * @return mixed
	 */
	function blankslate_contact_apply_default_value( $value, $default ) {
		if ( blankslate_contact_is_empty_value( $value ) ) {
			return $default;
		}
		return $value;
	}
}

if ( ! function_exists( 'blankslate_contact_build_info_block' ) ) {
	/**
	 * Merges a stored group of info fields with default data.
	 *
	 * @param array $value   Stored value for the block.
	 * @param array $default Default block structure.
	 * @return array
	 */
	function blankslate_contact_build_info_block( $value, $default ) {
		$value          = is_array( $value ) ? $value : array();
		$default        = is_array( $default ) ? $default : array();
		$default_title  = isset( $default['titulo'] ) ? $default['titulo'] : '';
		$default_items  = isset( $default['items'] ) && is_array( $default['items'] ) ? $default['items'] : array();
		$title_candidate = isset( $value['titulo'] ) ? trim( (string) $value['titulo'] ) : '';
		$title          = '' === $title_candidate ? $default_title : $title_candidate;

		$item_defs = array(
			array( 'name' => 'item_1', 'index' => 0 ),
			array( 'name' => 'item_2', 'index' => 1 ),
			array( 'name' => 'item_3', 'index' => 2 ),
		);

		$items = array();
		foreach ( $item_defs as $item_def ) {
			$item_value   = isset( $value[ $item_def['name'] ] ) && is_array( $value[ $item_def['name'] ] ) ? $value[ $item_def['name'] ] : array();
			$default_item = isset( $default_items[ $item_def['index'] ] ) ? $default_items[ $item_def['index'] ] : array();

			$label_candidate = isset( $item_value['label'] ) ? trim( (string) $item_value['label'] ) : '';
			$label           = '' === $label_candidate && isset( $default_item['label'] ) ? $default_item['label'] : $label_candidate;

			$value_candidate = isset( $item_value['valor'] ) ? trim( (string) $item_value['valor'] ) : '';
			$valor           = '' === $value_candidate && isset( $default_item['valor'] ) ? $default_item['valor'] : $value_candidate;

			$link_candidate = isset( $item_value['enlace'] ) ? trim( (string) $item_value['enlace'] ) : '';
			$enlace         = '' === $link_candidate && isset( $default_item['enlace'] ) ? $default_item['enlace'] : $link_candidate;

			if ( '' === $label && '' === $valor && '' === $enlace ) {
				continue;
			}

			$items[] = array(
				'label'  => $label,
				'valor'  => $valor,
				'enlace' => $enlace,
			);
		}

		if ( empty( $items ) && ! empty( $default_items ) ) {
			$items = $default_items;
		}

		return array(
			'titulo' => $title,
			'items'  => $items,
		);
	}
}

if ( ! function_exists( 'blankslate_contact_normalize_info_blocks' ) ) {
	/**
	 * Normalizes the info blocks value so the template receives a predictable structure.
	 *
	 * @param mixed $value Stored value.
	 * @return array
	 */
	function blankslate_contact_normalize_info_blocks( $value ) {
		$defaults = blankslate_contact_default_info_blocks();
		if ( blankslate_contact_is_empty_value( $value ) ) {
			return $defaults;
		}

		if ( isset( $value[0] ) && is_array( $value[0] ) && array_key_exists( 'titulo', $value[0] ) ) {
			return $value;
		}

		if ( ! is_array( $value ) ) {
			return $defaults;
		}

		$map    = array(
			'emails'   => 0,
			'ventas'   => 1,
			'servicio' => 2,
		);
		$blocks = array();

		foreach ( $map as $group_key => $default_index ) {
			$group_value   = isset( $value[ $group_key ] ) && is_array( $value[ $group_key ] ) ? $value[ $group_key ] : array();
			$default_block = isset( $defaults[ $default_index ] ) ? $defaults[ $default_index ] : array();
			$block         = blankslate_contact_build_info_block( $group_value, $default_block );
			if ( blankslate_contact_is_empty_value( $block ) ) {
				continue;
			}
			$blocks[] = $block;
		}

		return ! empty( $blocks ) ? $blocks : $defaults;
	}
}

if ( ! function_exists( 'blankslate_contact_normalize_social_links' ) ) {
	/**
	 * Normalizes social profiles so the template receives consistent data.
	 *
	 * @param mixed $value Stored value.
	 * @return array
	 */
	function blankslate_contact_normalize_social_links( $value ) {
		$defaults = blankslate_contact_default_social_links();
		if ( blankslate_contact_is_empty_value( $value ) ) {
			return $defaults;
		}

		if ( isset( $value[0] ) && is_array( $value[0] ) && array_key_exists( 'nombre', $value[0] ) ) {
			return $value;
		}

		if ( ! is_array( $value ) ) {
			return $defaults;
		}

		$map   = array(
			'facebook'  => 0,
			'whatsapp'  => 1,
			'twitter'   => 2,
			'instagram' => 3,
		);
		$links = array();

		foreach ( $map as $group_key => $default_index ) {
			$entry         = isset( $value[ $group_key ] ) && is_array( $value[ $group_key ] ) ? $value[ $group_key ] : array();
			$default_entry = isset( $defaults[ $default_index ] ) ? $defaults[ $default_index ] : array();

			$name_candidate = isset( $entry['nombre'] ) ? trim( (string) $entry['nombre'] ) : '';
			$nombre         = '' === $name_candidate && isset( $default_entry['nombre'] ) ? $default_entry['nombre'] : $name_candidate;

			$url_candidate = isset( $entry['url'] ) ? trim( (string) $entry['url'] ) : '';
			$url           = '' === $url_candidate && isset( $default_entry['url'] ) ? $default_entry['url'] : $url_candidate;

			if ( '' === $nombre || '' === $url ) {
				continue;
			}

			$links[] = array(
				'nombre' => $nombre,
				'url'    => $url,
			);
		}

		return ! empty( $links ) ? $links : $defaults;
	}
}

if ( ! function_exists( 'blankslate_contact_group_from_info_blocks' ) ) {
	/**
	 * Converts normalized info blocks into the group structure stored by ACF.
	 *
	 * @param mixed $blocks Canonical blocks as consumed by the template.
	 * @return array
	 */
	function blankslate_contact_group_from_info_blocks( $blocks ) {
		$defaults   = blankslate_contact_default_info_blocks();
		$normalized = blankslate_contact_normalize_info_blocks( $blocks );

		$map    = array(
			'emails'   => array( 'index' => 0, 'items' => 2 ),
			'ventas'   => array( 'index' => 1, 'items' => 2 ),
			'servicio' => array( 'index' => 2, 'items' => 1 ),
		);
		$result = array();

		foreach ( $map as $group_key => $meta ) {
			$index         = $meta['index'];
			$item_count    = $meta['items'];
			$default_block = isset( $defaults[ $index ] ) ? $defaults[ $index ] : array();
			$block         = isset( $normalized[ $index ] ) ? $normalized[ $index ] : $default_block;
			$titulo        = isset( $block['titulo'] ) ? $block['titulo'] : ( $default_block['titulo'] ?? '' );

			$result[ $group_key ] = array(
				'titulo' => $titulo,
			);

			for ( $offset = 0; $offset < $item_count; $offset++ ) {
				$default_item = isset( $default_block['items'][ $offset ] ) ? $default_block['items'][ $offset ] : array();
				$item         = isset( $block['items'][ $offset ] ) ? $block['items'][ $offset ] : $default_item;

				$result[ $group_key ][ 'item_' . ( $offset + 1 ) ] = array(
					'label'  => isset( $item['label'] ) ? $item['label'] : ( $default_item['label'] ?? '' ),
					'valor'  => isset( $item['valor'] ) ? $item['valor'] : ( $default_item['valor'] ?? '' ),
					'enlace' => isset( $item['enlace'] ) ? $item['enlace'] : ( $default_item['enlace'] ?? '' ),
				);
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'blankslate_contact_group_from_social_links' ) ) {
	/**
	 * Converts normalized social links into the group structure used by ACF.
	 *
	 * @param mixed $links Canonical links as consumed by the template.
	 * @return array
	 */
	function blankslate_contact_group_from_social_links( $links ) {
		$defaults   = blankslate_contact_default_social_links();
		$normalized = blankslate_contact_normalize_social_links( $links );

		$map    = array(
			'facebook'  => 0,
			'whatsapp'  => 1,
			'twitter'   => 2,
			'instagram' => 3,
		);
		$result = array();

		foreach ( $map as $group_key => $index ) {
			$default_entry = isset( $defaults[ $index ] ) ? $defaults[ $index ] : array();
			$entry         = isset( $normalized[ $index ] ) ? $normalized[ $index ] : $default_entry;

			$result[ $group_key ] = array(
				'nombre' => isset( $entry['nombre'] ) ? $entry['nombre'] : ( $default_entry['nombre'] ?? '' ),
				'url'    => isset( $entry['url'] ) ? $entry['url'] : ( $default_entry['url'] ?? '' ),
			);
		}

		return $result;
	}
}

add_filter(
	'acf/load_value/name=contacto_hero_imagen',
	function ( $value, $post_id, $field ) {
		if ( blankslate_contact_is_contact_template_post( $post_id ) ) {
			return blankslate_contact_apply_default_value( $value, blankslate_contact_default_hero_image() );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=contacto_hero_titulo',
	function ( $value, $post_id, $field ) {
		if ( blankslate_contact_is_contact_template_post( $post_id ) ) {
			return blankslate_contact_apply_default_value( $value, 'CONTÁCTANOS' );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=contacto_form_shortcode',
	function ( $value, $post_id, $field ) {
		if ( blankslate_contact_is_contact_template_post( $post_id ) ) {
			return blankslate_contact_apply_default_value( $value, '[elementor-template id="67"]' );
		}
		return $value;
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=contacto_info_grupos',
	function ( $value, $post_id, $field ) {
		if ( blankslate_contact_is_contact_template_post( $post_id ) ) {
			$value = blankslate_contact_apply_default_value( $value, blankslate_contact_default_info_blocks() );
		}
		return blankslate_contact_normalize_info_blocks( $value );
	},
	10,
	3
);

add_filter(
	'acf/load_value/name=contacto_social',
	function ( $value, $post_id, $field ) {
		if ( blankslate_contact_is_contact_template_post( $post_id ) ) {
			$value = blankslate_contact_apply_default_value( $value, blankslate_contact_default_social_links() );
		}
		return blankslate_contact_normalize_social_links( $value );
	},
	10,
	3
);
