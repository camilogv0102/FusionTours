<?php
namespace Brandoon\WooFilterPro;

class Ajax_Handler {
    public static function wfp_obtener_atributos_por_categoria() {
        $request = wp_unslash($_POST);

        $categoria_slug = isset($request['categoria']) ? sanitize_text_field($request['categoria']) : '';
        if ($categoria_slug === '') {
            wp_send_json_error(__('Categoría no enviada', 'woo-filter-pro'));
        }

        $taxonomia       = isset($request['taxonomia']) ? sanitize_key($request['taxonomia']) : 'product_cat';
        $valor_taxonomia = isset($request['valor_taxonomia']) ? sanitize_text_field($request['valor_taxonomia']) : '';

        if ($taxonomia === '') {
            $taxonomia = 'product_cat';
        }

        $cache_key = 'filtros_' . md5($categoria_slug . '_' . $taxonomia . '_' . $valor_taxonomia);
        $html      = get_transient($cache_key);
        if ($html !== false) {
            wp_send_json_success($html);
        }

        $tax_query = [
            'relation' => 'AND',
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categoria_slug,
            ],
        ];

        if (
            $taxonomia !== 'product_cat'
            && $valor_taxonomia !== ''
            && taxonomy_exists($taxonomia)
        ) {
            $tax_query[] = [
                'taxonomy' => $taxonomia,
                'field'    => 'slug',
                'terms'    => $valor_taxonomia,
            ];
        }

        $producto_ids = get_posts([
            'post_type'              => 'product',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'tax_query'              => $tax_query,
            'wfp_skip_filters'       => true,
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'cache_results'          => false,
        ]);

        if (empty($producto_ids)) {
            set_transient($cache_key, '', HOUR_IN_SECONDS);
            wp_send_json_success('');
        }

        $attribute_taxonomies = self::get_attribute_taxonomies();
        if (empty($attribute_taxonomies)) {
            set_transient($cache_key, '', HOUR_IN_SECONDS);
            wp_send_json_success('');
        }

        $terms = wp_get_object_terms($producto_ids, $attribute_taxonomies, [
            'fields' => 'all_with_object_id',
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            set_transient($cache_key, '', HOUR_IN_SECONDS);
            wp_send_json_success('');
        }

        $atributos_usados = [];
        $conteos          = [];
        $seen             = [];

        foreach ($terms as $term) {
            if (! isset($term->taxonomy, $term->slug)) {
                continue;
            }

            $taxonomy  = $term->taxonomy;
            $slug      = $term->slug;
            $object_id = isset($term->object_id) ? (int) $term->object_id : 0;

            if ($object_id && isset($seen[$taxonomy][$slug][$object_id])) {
                continue;
            }
            if ($object_id) {
                $seen[$taxonomy][$slug][$object_id] = true;
            }

            $atributos_usados[$taxonomy][$slug] = $term->name;
            $conteos[$taxonomy][$slug]          = ($conteos[$taxonomy][$slug] ?? 0) + 1;
        }

        foreach ($atributos_usados as $tax => &$valores) {
            uksort($valores, static function ($a, $b) use ($valores) {
                $valA = $valores[$a];
                $valB = $valores[$b];

                if (is_numeric($valA) && is_numeric($valB)) {
                    return (float) $valA <=> (float) $valB;
                }

                return strnatcasecmp($valA, $valB);
            });
        }
        unset($valores);

        $atributos_prioridad   = ['pa_marca', 'pa_gama'];
        $atributos_reordenados = [];

        foreach ($atributos_prioridad as $prioridad) {
            if (isset($atributos_usados[$prioridad])) {
                $atributos_reordenados[$prioridad] = $atributos_usados[$prioridad];
                unset($atributos_usados[$prioridad]);
            }
        }

        foreach ($atributos_usados as $tax => $valores) {
            $atributos_reordenados[$tax] = $valores;
        }

        $atributos_usados = $atributos_reordenados;

        $seleccionados = [];
        foreach ($attribute_taxonomies as $taxonomy) {
            if (isset($_GET[$taxonomy])) {
                $seleccionados[$taxonomy] = array_map(
                    'sanitize_text_field',
                    (array) wp_unslash($_GET[$taxonomy])
                );
            }
        }

        ob_start();
        foreach ($atributos_usados as $tax => $valores) {
            if (empty($valores)) {
                continue;
            }

            echo '<fieldset class="wfp-filter-group">';
            echo '<legend>' . esc_html(wc_attribute_label($tax)) . '</legend>';
            echo '<div class="wfp-pill-list">';

            foreach ($valores as $slug => $nombre) {
                $is_checked = in_array($slug, $seleccionados[$tax] ?? [], true);
                $count      = absint($conteos[$tax][$slug] ?? 0);
                $aria_label = sprintf(
                    _n('%1$s (%2$d resultado)', '%1$s (%2$d resultados)', $count, 'woo-filter-pro'),
                    wp_strip_all_tags($nombre),
                    $count
                );

                echo '<label class="wfp-pill-option">';
                echo '<input type="checkbox" name="' . esc_attr($tax) . '[]" value="' . esc_attr($slug) . '" ' . checked($is_checked, true, false) . ' aria-label="' . esc_attr($aria_label) . '">';
                echo '<span class="wfp-pill-content">';
                echo '<span class="wfp-pill-text">' . esc_html($nombre) . '</span>';
                echo '<span class="wfp-pill-count" aria-hidden="true">' . $count . '</span>';
                echo '</span>';
                echo '</label>';
            }

            echo '</div>';
            echo '</fieldset>';
        }

        $html = ob_get_clean();

        set_transient($cache_key, $html, 12 * HOUR_IN_SECONDS);
        wp_send_json_success($html);
    }

    public static function wfp_contar_atributos_dinamicos() {
        $request = wp_unslash($_POST);

        $categoria       = isset($request['categoria']) ? sanitize_text_field($request['categoria']) : '';
        $taxonomia       = isset($request['taxonomia']) ? sanitize_key($request['taxonomia']) : '';
        $valor_taxonomia = isset($request['valor_taxonomia']) ? sanitize_text_field($request['valor_taxonomia']) : '';

        if ($taxonomia === 'product_cat') {
            $taxonomia = '';
        }

        $filtros = [];
        if (isset($request['filtros'])) {
            $decoded = is_string($request['filtros']) ? json_decode($request['filtros'], true) : $request['filtros'];
            if (is_array($decoded)) {
                foreach ($decoded as $taxonomy => $values) {
                    $taxonomy = sanitize_key($taxonomy);
                    if ($taxonomy === '' || ! taxonomy_exists($taxonomy)) {
                        continue;
                    }

                    $values = array_map('sanitize_text_field', (array) $values);
                    $values = array_values(array_unique(array_filter($values)));

                    if (! empty($values)) {
                        $filtros[$taxonomy] = $values;
                    }
                }
            }
        }

        $attribute_taxonomies = self::get_attribute_taxonomies();
        if (empty($attribute_taxonomies)) {
            wp_send_json_success([]);
        }

        $base_tax_query = ['relation' => 'AND'];

        if ($categoria !== '') {
            $base_tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categoria,
            ];
        }

        if ($taxonomia && $valor_taxonomia !== '' && taxonomy_exists($taxonomia)) {
            $base_tax_query[] = [
                'taxonomy' => $taxonomia,
                'field'    => 'slug',
                'terms'    => $valor_taxonomia,
            ];
        }

        $resultados = [];

        foreach ($attribute_taxonomies as $taxonomy_actual) {
            if (! taxonomy_exists($taxonomy_actual)) {
                continue;
            }

            $tax_query = $base_tax_query;

            foreach ($filtros as $taxonomy => $values) {
                if ($taxonomy === $taxonomy_actual) {
                    continue;
                }

                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $values,
                    'operator' => 'AND',
                ];
            }

            $product_ids = get_posts([
                'post_type'              => 'product',
                'post_status'            => 'publish',
                'posts_per_page'         => -1,
                'fields'                 => 'ids',
                'tax_query'              => $tax_query,
                'wfp_skip_filters'       => true,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'cache_results'          => false,
            ]);

            if (empty($product_ids)) {
                $resultados[$taxonomy_actual] = [];
                continue;
            }

            $terms = wp_get_object_terms($product_ids, $taxonomy_actual, [
                'fields' => 'all_with_object_id',
            ]);

            if (is_wp_error($terms) || empty($terms)) {
                $resultados[$taxonomy_actual] = [];
                continue;
            }

            $counts = [];
            $seen   = [];

            foreach ($terms as $term) {
                $slug      = $term->slug ?? '';
                $object_id = isset($term->object_id) ? (int) $term->object_id : 0;

                if ($slug === '') {
                    continue;
                }

                if ($object_id && isset($seen[$slug][$object_id])) {
                    continue;
                }

                if ($object_id) {
                    $seen[$slug][$object_id] = true;
                }

                $counts[$slug] = ($counts[$slug] ?? 0) + 1;
            }

            $resultados[$taxonomy_actual] = $counts;
        }

        wp_send_json_success($resultados);
    }

    /**
     * Retrieve the list of registered attribute taxonomies (pa_*).
     *
     * @return string[]
     */
    private static function get_attribute_taxonomies() {
        static $taxonomies = null;

        if ($taxonomies !== null) {
            return $taxonomies;
        }

        if (! function_exists('wc_get_attribute_taxonomies')) {
            $taxonomies = [];
            return $taxonomies;
        }

        $taxonomies = [];
        foreach (wc_get_attribute_taxonomies() as $attr) {
            if (! isset($attr->attribute_name)) {
                continue;
            }
            $taxonomy = 'pa_' . $attr->attribute_name;
            $taxonomies[] = sanitize_key($taxonomy);
        }

        return array_values(array_unique($taxonomies));
    }
}
