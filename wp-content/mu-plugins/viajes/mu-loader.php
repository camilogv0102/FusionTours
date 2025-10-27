<?php
/**
 * Plugin Name: MU Loader – Manaslu
 * Description: Carga archivos MU desde subdirectorios de /mu-plugins.
 */

if (!defined('ABSPATH')) exit;

$should_ignore = function ($path) {
    $path = str_replace('\\', '/', $path);
    return strpos($path, '/_viajes-hidden/') !== false;
};

// 1) Requiere archivos PHP de primer nivel en subcarpetas (p.ej. /extras/*.php)
$dirs = glob(__DIR__ . '/*', GLOB_ONLYDIR);
if ($dirs) {
    foreach ($dirs as $dir) {
        if ($should_ignore($dir)) {
            continue;
        }
        foreach (glob($dir . '/*.php') as $file) {
            if ($should_ignore($file)) {
                continue;
            }
            require_once $file;
        }
    }
}

// 2) (Opcional) carga recursiva (sub-subcarpetas):
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__, FilesystemIterator::SKIP_DOTS)
);
foreach ($iterator as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'php' && dirname($file) !== __DIR__) {
        if ($should_ignore($file->getPathname())) {
            continue;
        }
        require_once $file->getPathname();
    }
}
