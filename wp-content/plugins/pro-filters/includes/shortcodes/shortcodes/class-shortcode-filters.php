<?php
/**
 * Compatibility loader for legacy paths.
 *
 * Older versions referenced
 * includes/shortcodes/shortcodes/class-shortcode-filters.php directly.
 * Keep that path working by delegating to the canonical class file.
 */

$shortcode_file = dirname(__DIR__) . '/class-shortcode-filters.php';

if (file_exists($shortcode_file)) {
    require_once $shortcode_file;
}
