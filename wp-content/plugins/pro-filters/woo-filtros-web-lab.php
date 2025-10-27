<?php
/**
 * Plugin Name: Woo Filter Pro by Brandoon
 * Description: Sistema avanzado de filtros y orden para WooCommerce con AJAX.
 * Version: 1.0.2
 * Author: Brandoon
 * Prefix: wfp_
 * Text Domain: woo-filter-pro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (! defined('WFP_PLUGIN_FILE')) {
    define('WFP_PLUGIN_FILE', __FILE__);
}

if (! defined('WFP_PLUGIN_DIR')) {
    define('WFP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (! defined('WFP_PLUGIN_BASENAME')) {
    define('WFP_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

require_once __DIR__ . '/includes/WooFilterPro.php';

add_action('plugins_loaded', function() {
    $plugin = new \Brandoon\WooFilterPro\WooFilterPro();
    $plugin->wfp_init();
});
