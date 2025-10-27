<?php
/**
 * Shortcode: [project_tags]
 * Renderiza dinámicamente las etiquetas (taxonomía project_tag) asociadas a un proyecto.
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

if ( ! function_exists('snc_projects_render_tags_shortcode') ) {
    /**
     * Render project tags either for the current project loop or a specific project_id.
     *
     * Atributos disponibles:
     * - project_id (int)   : ID del proyecto a mostrar. Si se omite, usa el proyecto actual en singular.
     * - separator (string) : Texto a usar entre etiquetas. Si se deja vacío, se genera markup envolvente.
     * - class (string)     : Clases adicionales para el contenedor.
     */
    function snc_projects_render_tags_shortcode($atts = [], $content = null, $shortcode_tag = '') {
        $atts = shortcode_atts(
            [
                'project_id' => 0,
                'separator'  => '',
                'class'      => '',
            ],
            $atts,
            $shortcode_tag
        );

        $project_id = absint($atts['project_id']);
        if ( ! $project_id ) {
            $current = get_post();
            if ( ! $current || $current->post_type !== 'project' ) {
                return '';
            }
            $project_id = $current->ID;
        } else {
            $project = get_post($project_id);
            if ( ! $project || $project->post_type !== 'project' ) {
                return '';
            }
        }

        $terms = get_the_terms($project_id, 'project_tag');
        if ( empty($terms) || is_wp_error($terms) ) {
            return '';
        }

        $separator   = (string) $atts['separator'];

        if ( $separator !== '' ) {
            $rendered = [];
            foreach ( $terms as $term ) {
                $name = esc_html($term->name);
                $rendered[] = $name;
            }

            return implode($separator, $rendered);
        }

        $classes = ['project-tags'];
        if ( ! empty($atts['class']) ) {
            $custom_classes = preg_split('/\s+/', $atts['class']);
            if ( is_array($custom_classes) ) {
                foreach ( $custom_classes as $class_name ) {
                    $sanitized = sanitize_html_class($class_name);
                    if ( $sanitized ) {
                        $classes[] = $sanitized;
                    }
                }
            }
        }

        ob_start();
        ?>
        <div class="<?php echo esc_attr(implode(' ', array_unique($classes))); ?>">
            <?php foreach ( $terms as $term ) : ?>
                <?php
                $name = esc_html($term->name);
                ?>
                <span class="project-tag">
                    <?php echo $name; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php
        return trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }
}

add_action('init', function () {
    add_shortcode('project_tags', 'snc_projects_render_tags_shortcode');
});
