<?php
/**
 * Plugin Name: MU Loader – Manaslu
 * Description: Carga archivos MU desde subdirectorios de /mu-plugins.
 */

if (!defined('ABSPATH')) exit;

$ignore_paths = [];
$hidden_dir   = __DIR__ . '/_viajes-hidden';
if (is_dir($hidden_dir)) {
    $ignore_paths[] = realpath($hidden_dir);
}

$should_ignore = function ($path) use ($ignore_paths) {
    if (empty($ignore_paths)) {
        return false;
    }
    $real_path = realpath($path);
    if (!$real_path) {
        return false;
    }
    foreach ($ignore_paths as $ignore) {
        if (strpos($real_path, $ignore) === 0) {
            return true;
        }
    }
    return false;
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

// 2) (Opcional) si quieres cargar recursivo (sub-subcarpetas):

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
