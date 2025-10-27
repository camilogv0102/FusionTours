<?php
/**
 * Plugin Name: CPT — Projects (MU)
 * Description: Custom Post Type "Projects" para portafolio / trabajos.
 * Author: SNC DESIGNS
 * Version: 1.0.0
 */

if ( ! defined('ABSPATH') ) exit;

/**
 * Registrar CPT: project
 */
add_action('init', function () {
    $labels = [
        'name'                  => __('Projects', 'cpt-projects'),
        'singular_name'         => __('Project', 'cpt-projects'),
        'menu_name'             => __('Projects', 'cpt-projects'),
        'name_admin_bar'        => __('Project', 'cpt-projects'),
        'add_new'               => __('Add New', 'cpt-projects'),
        'add_new_item'          => __('Add New Project', 'cpt-projects'),
        'new_item'              => __('New Project', 'cpt-projects'),
        'edit_item'             => __('Edit Project', 'cpt-projects'),
        'view_item'             => __('View Project', 'cpt-projects'),
        'all_items'             => __('All Projects', 'cpt-projects'),
        'search_items'          => __('Search Projects', 'cpt-projects'),
        'parent_item_colon'     => __('Parent Projects:', 'cpt-projects'),
        'not_found'             => __('No projects found.', 'cpt-projects'),
        'not_found_in_trash'    => __('No projects found in Trash.', 'cpt-projects'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'projects', 'with_front' => false],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title', 'thumbnail'],
        // mantener REST para compatibilidad con constructores/ACF
        'show_in_rest'       => true,
    ];

    register_post_type('project', $args);
});

/**
 * Registrar taxonomía personalizada: project_tag (Etiquetas de proyectos)
 */
add_action('init', function () {
    $labels = [
        'name'                       => __('Project Tags', 'cpt-projects'),
        'singular_name'              => __('Project Tag', 'cpt-projects'),
        'menu_name'                  => __('Tags', 'cpt-projects'),
        'all_items'                  => __('All Tags', 'cpt-projects'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => __('New Tag Name', 'cpt-projects'),
        'add_new_item'               => __('Add New Tag', 'cpt-projects'),
        'edit_item'                  => __('Edit Tag', 'cpt-projects'),
        'update_item'                => __('Update Tag', 'cpt-projects'),
        'view_item'                  => __('View Tag', 'cpt-projects'),
        'separate_items_with_commas' => __('Separate tags with commas', 'cpt-projects'),
        'add_or_remove_items'        => __('Add or remove tags', 'cpt-projects'),
        'choose_from_most_used'      => __('Choose from the most used', 'cpt-projects'),
        'popular_items'              => __('Popular Tags', 'cpt-projects'),
        'search_items'               => __('Search Tags', 'cpt-projects'),
        'not_found'                  => __('Not Found', 'cpt-projects'),
        'no_terms'                   => __('No tags', 'cpt-projects'),
        'items_list'                 => __('Tags list', 'cpt-projects'),
        'items_list_navigation'      => __('Tags list navigation', 'cpt-projects'),
    ];

    $args = [
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => ['slug' => 'project-tag', 'with_front' => false],
    ];

    register_taxonomy('project_tag', ['project'], $args);
});

/**
 * Limitar etiquetas a máximo 4 por proyecto (server-side seguro)
 */
add_action('save_post_project', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $tags = wp_get_post_terms($post_id, 'project_tag');
    if (is_wp_error($tags)) {
        return; // si la taxonomía no existe aún o error transitorio, no forzar
    }

    if (count($tags) > 4) {
        $first_four = array_slice(array_map(function($tag) { return (int) $tag->term_id; }, $tags), 0, 4);
        wp_set_post_terms($post_id, $first_four, 'project_tag', false);

        // Aviso en admin
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible"><p>Se han limitado las etiquetas a un máximo de 4 por proyecto.</p></div>';
        });
    }
});

/**
 * (Opcional) UI: aviso y prevención en el metabox de etiquetas (no jerárquicas)
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'project') return;

    wp_enqueue_script('jquery');
    wp_add_inline_script('jquery', '
        jQuery(function($){
            var box = $("#tagsdiv-project_tag"); // metabox de tags para la taxonomía project_tag
            if (!box.length) return;

            var maxTags = 4;

            function countTags(){
                // En UI de tags no jerárquicas, los seleccionados aparecen en .tagchecklist > span
                return box.find(".tagchecklist span").length;
            }

            function updateUI(){
                var n = countTags();
                var counter = box.find(".snc-tag-counter");
                if (!counter.length){
                    box.find(".inside").prepend(\'<div class="snc-tag-counter" style="background:#f0f0f1;padding:8px;margin-bottom:10px;border-radius:4px;font-size:13px;"></div>\');
                    counter = box.find(".snc-tag-counter");
                }
                counter.text("Etiquetas seleccionadas: " + n + "/" + maxTags);
            }

            // Interceptar el botón "Añadir" de esta taxonomía
            $(document).on("click", "#project_tag-add-submit", function(e){
                var n = countTags();
                if (n >= maxTags){
                    e.preventDefault();
                    alert("No puedes agregar más de " + maxTags + " etiquetas por proyecto.");
                    return false;
                }
            });

            // Observar cambios en la lista
            var target = box.find(".tagchecklist")[0];
            if (target && window.MutationObserver){
                var mo = new MutationObserver(function(){ updateUI(); });
                mo.observe(target, {childList:true, subtree:false});
            }

            // Inicial
            updateUI();
        });
    ');
});

/**
 * One-time flush de reglas (mu-plugins no tienen activation hook)
 */
add_action('init', function () {
    $flag = 'cpt_projects_flushed';
    if ( ! get_option($flag) ) {
        flush_rewrite_rules(false);
        update_option($flag, 1);
    }
}, 20);

/**
 * (Opcional) Soporte de Elementor para este CPT
 */
add_filter('elementor_cpt_support', function ($post_types) {
    if (is_array($post_types) && ! in_array('project', $post_types, true)) {
        $post_types[] = 'project';
    }
    return $post_types;
});

/**
 * Columnas del admin: etiquetas
 */
add_filter('manage_project_posts_columns', function ($columns) {
    $new = [];
    $new['cb'] = $columns['cb'];
    $new['title'] = $columns['title'];
    $new['project_tag'] = __('Tags', 'cpt-projects');
    $new['date'] = $columns['date'];
    return $new;
});

add_action('manage_project_posts_custom_column', function ($column, $post_id) {
    if ($column === 'project_tag') {
        $tags = get_the_terms($post_id, 'project_tag');
        if ($tags && !is_wp_error($tags)) {
            $tag_links = array_map(function($tag) {
                $url = admin_url('edit.php?post_type=project&project_tag=' . $tag->slug);
                return '<a href="' . esc_url($url) . '">' . esc_html($tag->name) . '</a>';
            }, $tags);
            echo implode(', ', $tag_links);
        } else {
            echo '&mdash;';
        }
    }
}, 10, 2);

/**
 * (Opcional) Mantener columnas (sin orden por taxonomía para evitar queries costosos/errores)
 * Si quieres ordenar por taxonomía, requiere join custom a term_relationships/terms. Lo dejamos desactivado por seguridad.
 */
// add_filter('manage_edit-project_sortable_columns', function ($columns) {
//     $columns['project_tag'] = 'project_tag';
//     return $columns;
// });
// add_action('pre_get_posts', function ($query) {
//     if (!is_admin() || !$query->is_main_query()) return;
//     if ('project_tag' === $query->get('orderby')) {
//         // Implementar join manual si se requiere
//     }
// });