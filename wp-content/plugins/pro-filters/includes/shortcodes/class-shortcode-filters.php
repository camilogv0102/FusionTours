<?php
namespace Brandoon\WooFilterPro;

class Shortcode_Filters {
    /**
     * Flag used to temporarily boost "destacado" tagged products.
     *
     * @var bool
     */
    private static $prioritize_destacado = false;

    public function wfp_register() {
        add_shortcode('filtro_productos', [ $this, 'wfp_filtro_productos_shortcode' ]);
        add_shortcode('productos_ajax', [ $this, 'wfp_ajax_product_loop' ]);
        add_shortcode('ordenador_productos', [ $this, 'wfp_ordenador_productos_shortcode' ]);
        add_shortcode('filtros_responsive', [ $this, 'wfp_filtro_responsive_wrapper_shortcode' ]);
        add_shortcode('filtro_categorias', [ $this, 'wfp_filtro_categorias_shortcode' ]);

        add_action('wp_ajax_wfp_fetch_products', [ $this, 'wfp_ajax_products_endpoint' ]);
        add_action('wp_ajax_nopriv_wfp_fetch_products', [ $this, 'wfp_ajax_products_endpoint' ]);
    }

    private function wfp_enqueue_dependencies() {
        if (! wp_script_is('woo-filtros-js', 'enqueued')) {
            wp_enqueue_script('woo-filtros-js');
            wp_localize_script('woo-filtros-js', 'filtroAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wfp_ajax_products'),
            ]);
        }
        if (! wp_script_is('woo-responsive-js', 'enqueued')) {
            wp_enqueue_script('woo-responsive-js');
        }
        if (! wp_style_is('woo-filtros-css', 'enqueued')) {
            wp_enqueue_style('woo-filtros-css');
        }
    }

    private function wfp_get_template($template_name, $context = []) {
        $template_path = locate_template('woo-filter-pro/' . $template_name, false, false);
        if (! $template_path) {
            $template_path = plugin_dir_path(dirname(__DIR__)) . 'templates/' . $template_name;
        }
        if (! file_exists($template_path)) {
            $message = sprintf(
                esc_html__( 'Template "%s" not found.', 'woo-filter-pro' ),
                esc_html( $template_name )
            );
            return '<div class="wfp-template-missing">' . $message . '</div>';
        }
        if (! empty($context)) {
            extract($context, EXTR_SKIP);
        }
        ob_start();
        include $template_path;
        return ob_get_clean();
    }

    public function wfp_filtro_categorias_shortcode($atts = []) {
        $atts       = shortcode_atts(['hide_empty' => true], $atts, 'filtro_categorias');
        $hide_empty = filter_var($atts['hide_empty'], FILTER_VALIDATE_BOOLEAN);

        $parents = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => $hide_empty,
            'parent'     => 0,
        ]);

        if (empty($parents) || is_wp_error($parents)) {
            return '';
        }

        $groups = [];
        foreach ($parents as $parent) {
            $children = get_terms([
                'taxonomy'   => 'product_cat',
                'hide_empty' => $hide_empty,
                'parent'     => $parent->term_id,
            ]);

            if (! empty($children) && ! is_wp_error($children)) {
                $groups[] = [
                    'parent'   => $parent,
                    'children' => $children,
                ];
            }
        }

        if (empty($groups)) {
            return '';
        }

        $context = ['categories' => $groups];

        return $this->wfp_get_template('filtros-categorias.php', $context);
    }

    public function wfp_filtro_productos_shortcode($modo_mobile = false) {
        $this->wfp_enqueue_dependencies();

        $context = [
            'modo_mobile' => $modo_mobile,
        ];

        return $this->wfp_get_template('filtro-productos.php', $context);
    }

    public function wfp_ajax_product_loop($atts = []) {
        $this->wfp_enqueue_dependencies();

        $request = $_GET;

        if ( empty($request['categoria']) && is_product_category() ) {
            $current_term = get_queried_object();
            if ( isset($current_term->slug) && isset($current_term->taxonomy) ) {
                $request['categoria'] = [ $current_term->slug ];
                $request['taxonomia'] = $current_term->taxonomy;
                $_GET['categoria']    = [ $current_term->slug ];
                $_GET['taxonomia']    = $current_term->taxonomy;
            }
        }

        $payload = $this->wfp_generate_products_payload($request);

        return $payload['html'] ?? '';
    }

    public function wfp_ajax_products_endpoint() {
        check_ajax_referer('wfp_ajax_products', 'nonce');

        $request = isset($_POST) ? $_POST : [];
        $payload = $this->wfp_generate_products_payload($request);

        wp_send_json_success([
            'html'      => $payload['html'] ?? '',
            'count'     => isset($payload['count']) ? (int) $payload['count'] : 0,
            'max_pages' => isset($payload['max_pages']) ? (int) $payload['max_pages'] : 0,
        ]);
    }

    public function wfp_ordenador_productos_shortcode() {
        $this->wfp_enqueue_dependencies();
        return $this->wfp_get_template('ordenador-productos.php');
    }

    public function wfp_filtro_responsive_wrapper_shortcode() {
        $this->wfp_enqueue_dependencies();
        $context = [
            'filtro' => $this->wfp_filtro_productos_shortcode(true),
        ];
        return $this->wfp_get_template('filtro-responsive-wrapper.php', $context);
    }

    private function wfp_generate_products_payload(array $request) {
        $request = wp_unslash($request);

        $paged   = isset($request['pagina']) ? max(1, intval($request['pagina'])) : 1;
        $orderby = isset($request['ordenar']) ? sanitize_text_field($request['ordenar']) : '';

        $args = [
            'post_type'        => 'product',
            'posts_per_page'   => 50,
            'paged'            => $paged,
            'post_status'      => 'publish',
            'wfp_skip_filters' => true,
        ];

        switch ( $orderby ) {
            case 'nombre':
                $args['orderby'] = 'title';
                $args['order']   = 'ASC';
                break;
            case 'precio_asc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'ASC';
                break;
            case 'precio_desc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;
        }

        $tax_query    = [ 'relation' => 'AND' ];
        $current_term = is_tax() ? get_queried_object() : null;
        $current_term_signature = '';

        if (
            $current_term && isset($current_term->taxonomy)
            && $current_term->taxonomy !== 'product_cat'
            && isset($current_term->slug)
            && $current_term->slug !== 'todas'
        ) {
            $current_term_signature = $current_term->taxonomy . ':' . $current_term->slug;
            $tax_query[] = [
                'taxonomy' => $current_term->taxonomy,
                'field'    => 'slug',
                'terms'    => $current_term->slug,
            ];
        }

        $category_signature = [];
        if ( ! empty($request['categoria']) ) {
            $categorias = array_map('sanitize_text_field', (array) $request['categoria']);
            $grouped_categorias = Taxonomy_Helpers::group_terms_by_top_parent($categorias, 'product_cat');
            foreach ($grouped_categorias as $root_id => $slugs) {
                if (empty($slugs)) {
                    continue;
                }

                $slugs = array_values(array_unique($slugs));
                sort($slugs, SORT_STRING);
                $category_signature[] = $root_id . ':' . implode(',', $slugs);

                if (count($slugs) === 1) {
                    $tax_query[] = [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $slugs,
                        'operator' => 'IN',
                    ];
                    continue;
                }

                $or_clause = ['relation' => 'OR'];
                foreach ($slugs as $slug) {
                    $or_clause[] = [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => [$slug],
                        'operator' => 'IN',
                    ];
                }

                if (count($or_clause) > 1) {
                    $tax_query[] = $or_clause;
                }
            }
        }

        sort($category_signature, SORT_STRING);

        $attribute_signature = [];
        $product_attributes  = wc_get_attribute_taxonomies();
        foreach ( $product_attributes as $attr ) {
            $taxonomy = 'pa_' . $attr->attribute_name;
            if ( empty($request[$taxonomy]) ) {
                continue;
            }

            $values = array_map('sanitize_text_field', (array) $request[$taxonomy]);
            $values = array_values(array_unique($values));
            sort($values, SORT_STRING);

            $attribute_signature[] = $taxonomy . ':' . implode(',', $values);

            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $values,
                'operator' => 'IN',
            ];
        }

        sort($attribute_signature, SORT_STRING);

        $cache_context = [
            'paged'      => $paged,
            'orderby'    => $orderby,
            'term'       => $current_term_signature,
            'categorias' => $category_signature,
            'atributos'  => $attribute_signature,
        ];

        $cache_key = $this->wfp_build_ajax_cache_key($cache_context);
        if ($cache_key) {
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                if (is_array($cached) && isset($cached['html'])) {
                    return [
                        'html'      => (string) $cached['html'],
                        'count'     => isset($cached['count']) ? (int) $cached['count'] : 0,
                        'max_pages' => isset($cached['max_pages']) ? (int) $cached['max_pages'] : 0,
                    ];
                }

                if (is_string($cached)) {
                    return [
                        'html'      => $cached,
                        'count'     => 0,
                        'max_pages' => 0,
                    ];
                }
            }
        }

        if ( count($tax_query) > 1 ) {
            $args['tax_query'] = $tax_query;
        }

        add_filter('posts_clauses', [self::class, 'wfp_prioritize_destacado'], 20, 2);
        self::$prioritize_destacado = true;

        $loop = new \WP_Query($args);

        self::$prioritize_destacado = false;
        remove_filter('posts_clauses', [self::class, 'wfp_prioritize_destacado'], 20);
        $count = $loop->found_posts;

        $context = [
            'loop'  => $loop,
            'paged' => $paged,
            'count' => $count,
        ];

        $output = $this->wfp_get_template('productos-ajax.php', $context);
        \wp_reset_postdata();

        $payload = [
            'html'      => $output,
            'count'     => $count,
            'max_pages' => (int) $loop->max_num_pages,
        ];

        if ($cache_key && $output !== '') {
            $ttl = apply_filters('wfp_ajax_products_cache_ttl', 5 * MINUTE_IN_SECONDS, $cache_context);
            set_transient($cache_key, $payload, absint($ttl));
        }

        return $payload;
    }

    public static function wfp_prioritize_destacado($clauses, $query) {
        if (! self::$prioritize_destacado || ! ($query instanceof \WP_Query)) {
            return $clauses;
        }

        if (is_admin() || $query->get('post_type') !== 'product') {
            return $clauses;
        }

        global $wpdb;
        $boost_clause = " (SELECT COUNT(*) FROM {$wpdb->term_relationships} tr
            INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
            INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
            WHERE tr.object_id = {$wpdb->posts}.ID
              AND tt.taxonomy = 'product_tag'
              AND t.slug = 'destacado') DESC";

        if (! empty($clauses['orderby'])) {
            $clauses['orderby'] = $boost_clause . ', ' . $clauses['orderby'];
        } else {
            $clauses['orderby'] = $boost_clause;
        }

        return $clauses;
    }

    private function wfp_build_ajax_cache_key(array $context) {
        $context = array_filter($context, function ($value) {
            if ($value === '' || $value === null) {
                return false;
            }
            if (is_array($value) && empty($value)) {
                return false;
            }
            return true;
        });

        if (empty($context)) {
            return '';
        }

        $payload = wp_json_encode($context);
        if (! $payload) {
            return '';
        }

        return 'wfp_ajax_' . md5($payload);
    }
}
