<?php
/**
 * Compatibility loader for legacy paths.
 *
 * Some installations referenced the AJAX handler from
 * includes/shortcodes/ajax/class-ajax-handler.php. Keep that path working by
 * delegating to the canonical location under includes/ajax/.
 */

$ajax_handler = dirname(__DIR__, 1) . '/../ajax/class-ajax-handler.php';

if (file_exists($ajax_handler)) {
    require_once $ajax_handler;
}
