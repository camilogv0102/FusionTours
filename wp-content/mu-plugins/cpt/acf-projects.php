<?php
/**
 * Plugin Name: ACF Campos Projects (MU)
 * Description: Campos ACF exclusivos para el CPT "Projects". No aparecen en el asignador manual.
 * Author: SNC DESIGNS
 * Version: 1.0.0
 */

if ( ! defined('ABSPATH') ) exit;

add_action('acf/init', function () {
    if ( ! function_exists('acf_add_local_field_group') ) return;

    acf_add_local_field_group([
        'key'                   => 'group_projects_campos',
        'title'                 => 'Projects — Campos',
        'no_manual_assign'      => true, // ← marcar para que el asignador los excluya
        'fields'                => [

            // =========================
            // TAB — Hero
            // =========================
            [
                'key'       => 'tab_project_hero',
                'label'     => 'Hero',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_hero_titulo_linea_1',
                'label' => 'Título línea 1',
                'name'  => 'project_hero_titulo_linea_1',
                'type'  => 'text',
                'instructions' => 'Primera línea del título principal del hero',
            ],
            [
                'key'   => 'field_project_hero_titulo_linea_2',
                'label' => 'Título línea 2',
                'name'  => 'project_hero_titulo_linea_2',
                'type'  => 'text',
                'instructions' => 'Segunda línea del título principal del hero',
            ],
            [
                'key'   => 'field_project_hero_bg',
                'label' => 'Imagen de fondo',
                'name'  => 'project_hero_imagen_fondo',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_hero_servicio_fecha',
                'label' => 'Servicio - fecha',
                'name'  => 'project_hero_servicio_fecha',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_project_hero_descripcion',
                'label' => 'Descripción',
                'name'  => 'project_hero_descripcion',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'instructions' => 'Descripción principal que aparecerá en el hero del proyecto',
            ],

            // =========================
            // TAB — Project focus
            // =========================
            [
                'key'       => 'tab_project_focus',
                'label'     => 'Project focus',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_focus_titulo',
                'label' => 'Título',
                'name'  => 'project_focus_titulo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_focus_parrafo',
                'label' => 'Párrafo',
                'name'  => 'project_focus_parrafo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],

            // =========================
            // TAB — The challenge
            // =========================
            [
                'key'       => 'tab_project_challenge',
                'label'     => 'The challenge',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_challenge_titulo',
                'label' => 'Título',
                'name'  => 'project_challenge_titulo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_challenge_parrafo',
                'label' => 'Párrafo',
                'name'  => 'project_challenge_parrafo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_challenge_imagen',
                'label' => 'Imagen',
                'name'  => 'project_challenge_imagen',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],

            // =========================
            // TAB — The solution
            // =========================
            [
                'key'       => 'tab_project_solution',
                'label'     => 'The solution',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_solution_titulo',
                'label' => 'Título',
                'name'  => 'project_solution_titulo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_solution_parrafo',
                'label' => 'Párrafo',
                'name'  => 'project_solution_parrafo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_solution_imagen_1',
                'label' => 'Imagen 1',
                'name'  => 'project_solution_imagen_1',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_solution_imagen_2',
                'label' => 'Imagen 2',
                'name'  => 'project_solution_imagen_2',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_solution_imagen_3',
                'label' => 'Imagen 3',
                'name'  => 'project_solution_imagen_3',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],

            // =========================
            // TAB — Identity
            // =========================
            [
                'key'       => 'tab_project_identity',
                'label'     => 'Identity',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_identity_titulo_1',
                'label' => 'Título 1',
                'name'  => 'project_identity_titulo_1',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_identity_parrafo_1',
                'label' => 'Párrafo 1',
                'name'  => 'project_identity_parrafo_1',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_identity_titulo_2',
                'label' => 'Título 2',
                'name'  => 'project_identity_titulo_2',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_identity_parrafo_2',
                'label' => 'Párrafo 2',
                'name'  => 'project_identity_parrafo_2',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_identity_imagen_1',
                'label' => 'Imagen 1',
                'name'  => 'project_identity_imagen_1',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_identity_imagen_2',
                'label' => 'Imagen 2',
                'name'  => 'project_identity_imagen_2',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_identity_imagen_3',
                'label' => 'Imagen 3',
                'name'  => 'project_identity_imagen_3',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],
            [
                'key'   => 'field_project_identity_imagen_4',
                'label' => 'Imagen 4',
                'name'  => 'project_identity_imagen_4',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ],

            // =========================
            // TAB — Resultados
            // =========================
            [
                'key'       => 'tab_project_resultados',
                'label'     => 'Resultados',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'   => 'field_project_resultados_titulo',
                'label' => 'Título',
                'name'  => 'project_resultados_titulo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_resultados_parrafo',
                'label' => 'Párrafo',
                'name'  => 'project_resultados_parrafo',
                'type'  => 'wysiwyg',
                'tabs'  => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_project_resultados_parrafo_corto_1',
                'label' => 'Párrafo corto 1',
                'name'  => 'project_resultados_parrafo_corto_1',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_project_resultados_parrafo_corto_2',
                'label' => 'Párrafo corto 2',
                'name'  => 'project_resultados_parrafo_corto_2',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_project_resultados_parrafo_corto_3',
                'label' => 'Párrafo corto 3',
                'name'  => 'project_resultados_parrafo_corto_3',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_project_resultados_focus_area_text',
                'label' => 'Focus area text',
                'name'  => 'project_resultados_focus_area_text',
                'type'  => 'text',
            ],

        ],

        // Asignación exclusiva al CPT 'project'
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'project',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 0,
    ]);
});