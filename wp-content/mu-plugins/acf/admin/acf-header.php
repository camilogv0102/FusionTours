<?php
/**
 * Plugin Name: Admin – ACF Sticky Tabs
 */
if (!defined('ABSPATH')) exit;

add_action('admin_head', function () { ?>
<style>
/* 1) Asegurar que el área de scroll no corte el sticky */
#wpbody-content { overflow: visible; }

.acf-tab-wrap.-top{
    position: sticky !important;
    top: 24px !important;
    padding: 20px;
    z-index: 99;
}


/* 5) (Opcional) Evitar recortes dentro del metabox */
.postbox .inside { overflow: visible; }
</style>
<?php });