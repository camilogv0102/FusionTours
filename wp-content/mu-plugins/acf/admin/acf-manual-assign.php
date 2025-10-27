<?php
/**
 * Plugin Name: ACF – Asignación por página (Dropdown, MU)
 * Description: Pestaña "Asignar ACF" con desplegable de un solo grupo. Inyecta una regla de ubicación 'manual_assign' a todos los grupos para que funcionen sin editar su código.
 * Author: SNC DESIGNS
 * Version: 2.0.0
 */

if ( ! defined('ABSPATH') ) exit;

/* --------------------------------------------------------
 * 1) Metabox: dropdown (una sola opción)
 *    Guarda la key del grupo en _acf_manual_group (string)
 * -------------------------------------------------------- */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'acf_manual_assign',
        'Asignar ACF',
        'acf_manual_assign_metabox_cb',
        ['page'], // añade más post types si quieres
        'side',
        'high'
    );
});

function acf_manual_assign_metabox_cb($post) {
    wp_nonce_field('acf_manual_assign_save', 'acf_manual_assign_nonce');

    $selected = (string) get_post_meta($post->ID, '_acf_manual_group', true);

    if ( ! function_exists('acf_get_field_groups') ) {
        echo '<p style="color:#a00">ACF no está activo.</p>';
        return;
    }

    $groups = acf_get_field_groups();
    echo '<p>Elige el grupo ACF que quieres mostrar en esta página.</p>';
    echo '<select name="acf_manual_group" style="width:100%">';
    echo '<option value="">— Sin asignar —</option>';
    foreach ($groups as $g) {
        $key   = esc_attr($g['key']);
        $title = esc_html($g['title']);
        $sel   = selected($selected, $key, false);
        echo "<option value='{$key}' {$sel}>{$title}</option>";
    }
    echo '</select>';
}

add_action('save_post_page', function ($post_id) {
    if ( ! isset($_POST['acf_manual_assign_nonce']) || ! wp_verify_nonce($_POST['acf_manual_assign_nonce'], 'acf_manual_assign_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    $value = isset($_POST['acf_manual_group']) ? sanitize_text_field($_POST['acf_manual_group']) : '';
    update_post_meta($post_id, '_acf_manual_group', $value);
});

/* --------------------------------------------------------
 * 2) Regla de ubicación personalizada: manual_assign
 *    - Valores: lista de grupos (key => title)
 *    - Match: compara con _acf_manual_group de la página
 * -------------------------------------------------------- */
add_filter('acf/location/rule_types', function ($choices) {
    $choices['Página']['manual_assign'] = 'Asignación manual (dropdown)';
    return $choices;
});

add_filter('acf/location/rule_values/manual_assign', function ($choices) {
    if ( function_exists('acf_get_field_groups') ) {
        foreach (acf_get_field_groups() as $g) {
            $choices[$g['key']] = $g['title'];
        }
    }
    return $choices;
});

add_filter('acf/location/rule_match/manual_assign', function ($match, $rule, $screen) {
    $post_id = isset($screen['post_id']) ? (int) $screen['post_id'] : 0;
    if ( ! $post_id ) return false;

    $assigned = (string) get_post_meta($post_id, '_acf_manual_group', true);
    $is_equal = hash_equals((string)$rule['value'], $assigned);

    return ($rule['operator'] === '==') ? $is_equal : ! $is_equal;
}, 10, 3);

/* --------------------------------------------------------
 * 3) Inyección automática de la regla manual_assign a TODOS
 *    los grupos ACF (no necesitas editar cada grupo).
 *    Agregamos un OR: manual_assign == <key del grupo>
 * -------------------------------------------------------- */
add_filter('acf/load_field_group', function ($group) {
    // Asegura el array de location
    if ( empty($group['location']) || ! is_array($group['location']) ) {
        $group['location'] = [];
    }

    // Verifica si ya existe una regla manual_assign para este grupo
    $already = false;
    foreach ($group['location'] as $or_group) {
        foreach ((array)$or_group as $rule) {
            if ( isset($rule['param'], $rule['value']) && $rule['param'] === 'manual_assign' && $rule['value'] === $group['key'] ) {
                $already = true; break 2;
            }
        }
    }

    if ( ! $already ) {
        $group['location'][] = [[
            'param'    => 'manual_assign',
            'operator' => '==',
            'value'    => $group['key'],
        ]];
    }

    return $group;
});