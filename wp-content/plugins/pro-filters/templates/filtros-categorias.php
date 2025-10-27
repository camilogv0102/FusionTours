<?php
/**
 * Template for filtro_categorias shortcode.
 *
 * Variables:
 * - $categories (array) Each entry contains:
 *     - parent (WP_Term)
 *     - children (WP_Term[])
 */
?>
<div class="wfp-filtros-categorias wfp-filter-panel">
<?php foreach ($categories as $group): ?>
    <?php if (empty($group['children'])) { continue; } ?>
    <fieldset class="wfp-filter-group">
        <legend><?php echo esc_html($group['parent']->name); ?></legend>
        <div class="wfp-pill-list">
            <?php foreach ($group['children'] as $child): ?>
                <label class="wfp-pill-option">
                    <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($child->slug); ?>" data-term-id="<?php echo esc_attr($child->term_id); ?>" data-root-id="<?php echo esc_attr($group['parent']->term_id); ?>">
                    <span class="wfp-pill-content">
                        <span class="wfp-pill-text"><?php echo esc_html($child->name); ?></span>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </fieldset>
<?php endforeach; ?>
</div>
