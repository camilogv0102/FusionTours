<?php
/**
 * Plugin Name: Desactivar Gutenberg (MU)
 * Description: Desactiva el editor de bloques (Gutenberg) en posts, páginas, CPTs y widgets. Elimina patrones y estilos de bloques del front.
 * Author: SNC DESIGNS
 * Version: 1.0.0
 */

if ( ! defined('ABSPATH') ) exit;

/**
 * 1) Desactivar Gutenberg (editor de bloques) para TODOS los tipos de post.
 */
add_filter('use_block_editor_for_post_type', function($use_block_editor, $post_type) {
	$permitidos = array(
		'post',
	);
	if ( in_array($post_type, $permitidos, true) ) {
		return true;
	}
	return false;
}, 100, 2);

/**
 * 2) Desactivar el editor de widgets basado en bloques (usar widgets clásicos).
 */
add_filter('gutenberg_use_widgets_block_editor', '__return_false'); // Gutenberg plugin
add_filter('use_widgets_block_editor', '__return_false');           // Core 5.8+

/**
 * 3) Desactivar patrones de bloques y el directorio de bloques.
 */
add_action('after_setup_theme', function() {
	remove_theme_support('core-block-patterns');
}, 11);

// Evitar carga separada de assets de bloques (por si el tema la habilita).
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * 4) Quitar CSS del block editor en el front para aligerar.
 */
add_action('wp_enqueue_scripts', function() {
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('global-styles');      // estilos globales (theme.json)
	wp_dequeue_style('classic-theme-styles');
}, 100);

/**
 * 5) Opcional: forzar editor clásico en pantalla de edición (por si algo lo re-habilita).
 */
add_filter('use_block_editor_for_post', function( $use_block_editor, $post ) {
	if ( $post && 'post' === $post->post_type ) {
		return true;
	}
	return false;
}, 100, 2);

/**
 * 6) Eliminar campo de descripción (excerpt) nativo que aparece sin Gutenberg.
 */
add_action('init', function() {
    // Remover soporte de excerpt de todos los post types
    $post_types = get_post_types(['public' => true], 'names');
    foreach ($post_types as $post_type) {
        remove_post_type_support($post_type, 'excerpt');
    }
}, 20);

/**
 * 6b) Ocultar el editor de contenido clásico (post content) cuando Gutenberg está desactivado.
 *     Mantén una lista de post types permitidos que sí conservan el editor.
 */
add_action('init', function() {
    // Post types que SÍ mantienen el editor de contenido
    $permitidos = array(
        'post',     // entradas del blog
        'project',  // CPT Projects (si quieres editar contenido nativo allí)
        // agrega aquí otros CPTs que necesiten el editor
    );

    $post_types = get_post_types(['public' => true], 'names');
    foreach ($post_types as $post_type) {
        if (in_array($post_type, $permitidos, true)) {
            continue;
        }
        // Quitar soporte de editor (oculta el textarea principal)
        remove_post_type_support($post_type, 'editor');
    }
}, 21);

/**
 * 6c) Backstop: remover explícitamente el metabox del editor si algún tema/plugin lo reinyecta.
 */
add_action('add_meta_boxes', function() {
    remove_meta_box('postdivrich', null, 'normal'); // editor clásico
}, 100);

/**
 * 7) Remover metabox de excerpt del admin si aparece.
 */
add_action('add_meta_boxes', function() {
    remove_meta_box('postexcerpt', null, 'normal');
    remove_meta_box('postexcerpt', null, 'side');
    remove_meta_box('postexcerpt', null, 'advanced');
});

/**
 * 8) Remover columna de excerpt del admin si existe.
 */
add_filter('manage_posts_columns', function($columns) {
    if (isset($columns['excerpt'])) {
        unset($columns['excerpt']);
    }
    return $columns;
});

add_filter('manage_pages_columns', function($columns) {
    if (isset($columns['excerpt'])) {
        unset($columns['excerpt']);
    }
    return $columns;
});

// Para CPTs
add_action('admin_init', function() {
    $post_types = get_post_types(['public' => true, '_builtin' => false], 'names');
    foreach ($post_types as $post_type) {
        add_filter("manage_{$post_type}_posts_columns", function($columns) {
            if (isset($columns['excerpt'])) {
                unset($columns['excerpt']);
            }
            return $columns;
        });
    }
});
