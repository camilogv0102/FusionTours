<?php
namespace Brandoon\WooFilterPro;

require_once __DIR__ . '/ajax/class-ajax-handler.php';
require_once __DIR__ . '/shortcodes/class-shortcode-filters.php';
require_once __DIR__ . '/utils/class-taxonomy-helpers.php';
require_once __DIR__ . '/utils/class-translation-handler.php';

class WooFilterPro {
    public function wfp_init() {
        add_action('wp_enqueue_scripts', [ $this, 'wfp_enqueue_assets' ]);

        add_action('wp_ajax_obtener_atributos_por_categoria', [ Ajax_Handler::class, 'wfp_obtener_atributos_por_categoria' ]);
        add_action('wp_ajax_nopriv_obtener_atributos_por_categoria', [ Ajax_Handler::class, 'wfp_obtener_atributos_por_categoria' ]);
        add_action('wp_ajax_contar_atributos_dinamicos', [ Ajax_Handler::class, 'wfp_contar_atributos_dinamicos' ]);
        add_action('wp_ajax_nopriv_contar_atributos_dinamicos', [ Ajax_Handler::class, 'wfp_contar_atributos_dinamicos' ]);
        add_action('pre_get_posts', [ $this, 'wfp_apply_filters_to_query' ]);

        $shortcodes = new Shortcode_Filters();
        $shortcodes->wfp_register();

        Translation_Handler::wfp_init();
    }

    public function wfp_apply_filters_to_query($query) {
        if (! $query instanceof \WP_Query) {
            return;
        }

        // No tocar admin (salvo AJAX), ni feeds, ni REST
        if (is_admin() && ! wp_doing_ajax()) {
            return;
        }
        if (is_feed()) {
            return;
        }
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return;
        }

        if ($query->get('wfp_skip_filters')) {
            return;
        }

        if (! $this->wfp_is_product_query($query)) {
            return;
        }

        $tax_clauses = $this->wfp_build_tax_query_from_request();

        if (! empty($tax_clauses)) {
            $existing = $query->get('tax_query');

            if (! is_array($existing) || empty($existing)) {
                $existing = array('relation' => 'AND');
            } elseif (! isset($existing['relation'])) {
                $existing = array_merge(array('relation' => 'AND'), $existing);
            }

            foreach ($tax_clauses as $clause) {
                $existing[] = $clause;
            }

            $query->set('tax_query', $existing);
        }

        $this->wfp_apply_ordering_to_query($query);
        $this->wfp_apply_pagination_to_query($query);
    }

    private function wfp_is_product_query(\WP_Query $query) {
        $post_type = $query->get('post_type');

        // Main queries “típicas” de tienda
        if (empty($post_type) && $query->is_main_query()) {
            if ( (function_exists('is_shop') && is_shop())
                || (function_exists('is_product_taxonomy') && is_product_taxonomy())
                || is_post_type_archive('product') ) {
                return true;
            }
            // Detectar por tax_query si ya hay una taxonomía de producto
            $tax_query = $query->get('tax_query');
            if (is_array($tax_query)) {
                foreach ($tax_query as $clause) {
                    if (is_array($clause) && isset($clause['taxonomy']) && $this->wfp_is_product_taxonomy($clause['taxonomy'])) {
                        return true;
                    }
                }
            }
            return false;
        }

        // Casos con post_type explícito
        if (is_array($post_type)) {
            return in_array('product', $post_type, true) || in_array('any', $post_type, true);
        }
        return ($post_type === 'product' || $post_type === 'any');
    }

    private function wfp_is_product_taxonomy($taxonomy) {
        if ($taxonomy === 'product_cat' || $taxonomy === 'product_tag') {
            return true;
        }

        return \strpos($taxonomy, 'pa_') === 0;
    }

    private function wfp_build_tax_query_from_request() {
        $clauses = array();

        // Filtro libre "taxonomia=pa_color&amp;valor_taxonomia=rojo" (validado)
        $taxonomia       = isset($_GET['taxonomia']) ? sanitize_text_field( wp_unslash($_GET['taxonomia']) ) : '';
        $valor_taxonomia = isset($_GET['valor_taxonomia']) ? sanitize_text_field( wp_unslash($_GET['valor_taxonomia']) ) : '';

        if ($taxonomia && $valor_taxonomia) {
            $ok = ($taxonomia === 'product_tag' || $taxonomia === 'product_cat' || $this->wfp_is_product_taxonomy($taxonomia));
            if ($ok) {
                $clauses[] = array(
                    'taxonomy' => $taxonomia,
                    'field'    => 'slug',
                    'terms'    => array($valor_taxonomia),
                    'operator' => 'IN',
                );
            }
        }

        // Categorías por parámetro multivalor ?categoria=ropa&amp;categoria=hombre
        $categorias = $this->wfp_sanitize_array_param('categoria');
        if (! empty($categorias)) {
            $grouped_categorias = Taxonomy_Helpers::group_terms_by_top_parent($categorias, 'product_cat');
            foreach ($grouped_categorias as $slugs) {
                if (empty($slugs)) {
                    continue;
                }

                if (count($slugs) === 1) {
                    $clauses[] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $slugs,
                        'operator' => 'IN',
                    );
                    continue;
                }

                $or_clause = array('relation' => 'OR');
                foreach ($slugs as $slug) {
                    $or_clause[] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => array($slug),
                        'operator' => 'IN',
                    );
                }

                if (count($or_clause) > 1) {
                    $clauses[] = $or_clause;
                }
            }
        }

        // Atributos dinámicos: pa_*
        $product_attributes = wc_get_attribute_taxonomies();
        foreach ($product_attributes as $attr) {
            $taxonomy = 'pa_' . $attr->attribute_name;
            $terms    = $this->wfp_sanitize_array_param($taxonomy);
            if (! empty($terms)) {
                $clauses[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $terms,
                    'operator' => 'IN',
                );
            }
        }

        return $clauses;
    }

    private function wfp_sanitize_array_param($param) {
        if (! isset($_GET[$param])) {
            return [];
        }

        $values = (array) $_GET[$param];
        $values = \array_map('\sanitize_text_field', \wp_unslash($values));
        $values = \array_filter($values, function ($value) {
            return $value !== '';
        });

        return \array_values(\array_unique($values));
    }

    private function wfp_apply_ordering_to_query(\WP_Query $query) {
        $orderby = isset($_GET['ordenar']) ? sanitize_text_field( wp_unslash($_GET['ordenar']) ) : '';

        if (! $orderby) return;

        $allowed = array('nombre','precio_asc','precio_desc');
        if (! in_array($orderby, $allowed, true)) return;

        switch ($orderby) {
            case 'nombre':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                // limpiar meta_key si vienes de un orden previo por precio
                $query->set('meta_key', '');
                break;

            case 'precio_asc':
                $query->set('meta_key', '_price');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'ASC');
                break;

            case 'precio_desc':
                $query->set('meta_key', '_price');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
        }
    }

    private function wfp_apply_pagination_to_query(\WP_Query $query) {
        $pagina = isset($_GET['pagina']) ? absint( wp_unslash($_GET['pagina']) ) : 0;

        if ($pagina > 0) {
            $query->set('paged', $pagina);
            return;
        }

        // compatibilidad: si viene ?paged=2 (pretty permalinks /page/2/)
        $paged = get_query_var('paged');
        if ($paged) {
            $query->set('paged', absint($paged));
        }
    }

    public function wfp_enqueue_assets() {
        // URL base a la RAÍZ del plugin (subimos un nivel desde /includes/)
        $plugin_url = trailingslashit( plugins_url('..', __FILE__) );

        // Registros (añade 'jquery' si tus scripts lo usan)
        wp_register_script(
            'woo-filtros-js',
            $plugin_url . 'assets/js/filtros.js',
            array('jquery'),
            null,
            true
        );

        wp_register_script(
            'woo-responsive-js',
            $plugin_url . 'assets/js/responsive.js',
            array('jquery'),
            null,
            true
        );

        wp_register_style('woo-filtros-css', $plugin_url . 'assets/styles/styles.css', array(), null);

        // Carga condicional en páginas que contengan tus shortcodes
        global $post;
        if ($post && isset($post->post_content)) {
            $shortcodes = array('filtro_productos', 'productos_ajax', 'ordenador_productos', 'filtros_responsive');
            foreach ($shortcodes as $shortcode) {
                if (has_shortcode($post->post_content, $shortcode)) {
                    wp_enqueue_script('woo-filtros-js');
                    wp_enqueue_script('woo-responsive-js');
                    wp_localize_script('woo-filtros-js', 'filtroAjax', array(
                        'ajax_url' => admin_url('admin-ajax.php'),
                        'nonce'    => wp_create_nonce('wfp_ajax_products'),
                    ));
                    wp_enqueue_style('woo-filtros-css');
                    break;
                }
            }
        }

        // (Opcional) Si quieres que también cargue en archivo tienda/categorías:
        // if ( function_exists('is_shop') && ( is_shop() || is_product_taxonomy() ) ) {
        //     wp_enqueue_script('woo-filtros-js');
        //     wp_enqueue_script('woo-responsive-js');
        //     wp_localize_script('woo-filtros-js', 'filtroAjax', array(
        //         'ajax_url' => admin_url('admin-ajax.php'),
        //     ));
        //     wp_enqueue_style('woo-filtros-css');
        // }
    }
}
