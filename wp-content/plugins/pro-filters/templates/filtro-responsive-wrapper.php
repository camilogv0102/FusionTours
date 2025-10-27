<?php
/**
 * Template for filtros_responsive shortcode.
 *
 * Variables:
 * - $filtro (string)
 */
?>
<div id="filtro-offcanvas-overlay"></div>
<div id="filtro-responsive-wrapper">
  <div class="fila-filtrar-mobile">
    <span id="contador-productos-mobile"></span>
    <button id="abrir-filtros-mobile" class="abrir-filtros"><?php esc_html_e('Filtrar', 'woo-filter-pro'); ?></button>
  </div>
  <div id="filtro-offcanvas" class="cerrado">
    <div class="contenido-filtros">
      <?php echo $filtro; ?>
      <button id="aplicar-filtros-mobile" class="aplicar-filtros"><?php esc_html_e('Aplicar filtros', 'woo-filter-pro'); ?></button>
    </div>
  </div>
</div>
