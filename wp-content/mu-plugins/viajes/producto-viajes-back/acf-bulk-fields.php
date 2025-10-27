<?php
/**
 * Plugin Name: Viajes – Campos Operativos
 * Description: Define únicamente los campos ACF necesarios para el tipo de producto Viaje.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$fields_operativa = [
			[
				'key'          => 'field_' . substr( md5( 'operativa_que_incluye' ), 0, 12 ),
				'label'        => 'Qué incluye',
				'name'         => 'operativa_que_incluye',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'full',
				'media_upload' => 0,
				'instructions' => 'Detalle de servicios o beneficios incluidos en el viaje.',
			],
			[
				'key'          => 'field_' . substr( md5( 'operativa_recomendaciones_restricciones' ), 0, 12 ),
				'label'        => 'Recomendaciones y restricciones',
				'name'         => 'operativa_recomendaciones_restricciones',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'full',
				'media_upload' => 0,
				'instructions' => 'Consejos para el viajero y restricciones relevantes.',
			],
			[
				'key'          => 'field_' . substr( md5( 'operativa_dias_operacion' ), 0, 12 ),
				'label'        => 'Días de operación',
				'name'         => 'operativa_dias_operacion',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'full',
				'media_upload' => 0,
				'instructions' => 'Indica los días o temporadas en los que opera el tour.',
			],
		];

		acf_add_local_field_group(
			[
				'key'               => 'group_' . substr( md5( 'grupo_operativa_viajes' ), 0, 12 ),
				'title'             => 'Información operativa',
				'fields'            => $fields_operativa,
				'location'          => [
					[
						[
							'param'    => 'post_taxonomy',
							'operator' => '==',
							'value'    => 'product_type:viaje',
						],
					],
				],
				'position'          => 'normal',
				'style'             => 'default',
				'label_placement'   => 'top',
				'instruction_placement' => 'label',
				'active'            => true,
			]
		);
	}
);
