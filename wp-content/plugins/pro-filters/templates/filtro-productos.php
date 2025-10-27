<?php
/**
 * Simplified product filter template.
 *
 * Shows top-level categories grouped with their descendants presented as
 * selectable checkboxes without conditional logic or dynamic loading.
 *
 * Variables:
 * - $modo_mobile (bool)
 */

if (!function_exists('wfp_render_categoria_checkboxes')) {
    /**
     * Render the category hierarchy as pill-based checkbox groups.
     */
    function wfp_render_categoria_checkboxes($parent = 0, $level = 0, $root_id = 0) {
        static $selected_slugs = null;
        static $selected_by_root = [];

        if ($level === 0 && $parent === 0) {
            $selected_slugs = array_map('sanitize_text_field', (array) ($_GET['categoria'] ?? []));
            $selected_slugs = array_values(array_unique($selected_slugs));
            $selected_by_root = [];
        }

        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $parent,
        ]);

        if (empty($terms) || is_wp_error($terms)) {
            return '';
        }

        $html = '';

        foreach ($terms as $term) {
            if ($level === 0) {
                $children_html = wfp_render_categoria_checkboxes($term->term_id, $level + 1, $term->term_id);

                if ($children_html === '') {
                    continue;
                }

                $html .= '<fieldset class="wfp-filter-group">';
                $html .= '<legend>' . esc_html($term->name) . '</legend>';
                $html .= '<div class="wfp-pill-list">' . $children_html . '</div>';
                $html .= '</fieldset>';
                continue;
            }

            $root = $root_id ?: $parent ?: $term->term_id;
            $checked = in_array($term->slug, $selected_slugs ?? [], true);

            if ($checked) {
                if (isset($selected_by_root[$root]) && $selected_by_root[$root] !== $term->slug) {
                    $checked = false;
                } else {
                    $selected_by_root[$root] = $term->slug;
                }
            }

            $html .= '<label class="wfp-pill-option">';
            $html .= '<input type="checkbox" name="categoria[]" value="' . esc_attr($term->slug) . '" data-root-id="' . esc_attr($root) . '" ' . checked($checked, true, false) . '>';
            $html .= '<span class="wfp-pill-content">';
            $html .= '<span class="wfp-pill-text">' . esc_html($term->name) . '</span>';
            $html .= '</span>';
            $html .= '</label>';

            $descendants_html = wfp_render_categoria_checkboxes($term->term_id, $level + 1, $root);
            if ($descendants_html !== '') {
                $html .= $descendants_html;
            }
        }

        if ($level === 0) {
            return $html !== '' ? '<div class="wfp-filter-groups">' . $html . '</div>' : '';
        }

        return $html;
    }
}
?>

<form
    id="<?php echo $modo_mobile ? 'filtro-productos-mobile' : 'filtro-productos'; ?>"
    class="wfp-filter-panel"
    data-ajax="true"
    data-mode="<?php echo $modo_mobile ? 'mobile' : 'desktop'; ?>"
>
    <?php
    $current_term = is_tax() ? get_queried_object() : null;
    if ($current_term && isset($current_term->taxonomy) && $current_term->taxonomy !== 'product_cat') {
        echo '<input type="hidden" name="taxonomia" value="' . esc_attr($current_term->taxonomy) . '">';
        echo '<input type="hidden" name="valor_taxonomia" value="' . esc_attr($current_term->slug) . '">';
    }
    ?>
    <fieldset class="wfp-filtro-categorias">
        <legend><?php esc_html_e('Categorías', 'woo-filter-pro'); ?></legend>
        <?php echo wfp_render_categoria_checkboxes(); ?>
    </fieldset>
    <div id="<?php echo $modo_mobile ? 'filtros-atributos-mobile' : 'filtros-atributos'; ?>"></div>
</form>
