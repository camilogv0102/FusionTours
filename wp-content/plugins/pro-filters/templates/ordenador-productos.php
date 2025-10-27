<?php
/**
 * Template for ordenador_productos shortcode.
 */
?>
<div id="ordenador-productos">
  <span id="contador-productos"></span>
  <div class="ordenador-select">
    <label for="ordenar"><?php esc_html_e('Ordenar por:', 'woo-filter-pro'); ?></label>
    <select id="ordenar">
      <option value=""><?php esc_html_e('Por defecto', 'woo-filter-pro'); ?></option>
      <option value="nombre"><?php esc_html_e('Nombre (A-Z)', 'woo-filter-pro'); ?></option>
      <option value="precio_asc"><?php esc_html_e('Precio (menor a mayor)', 'woo-filter-pro'); ?></option>
      <option value="precio_desc"><?php esc_html_e('Precio (mayor a menor)', 'woo-filter-pro'); ?></option>
    </select>
  </div>
</div>
