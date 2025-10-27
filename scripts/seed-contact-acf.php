<?php
if (php_sapi_name() !== 'cli') {
    exit("CLI only\n");
}
$docroot = getcwd();
require_once $docroot . '/wp-load.php';

if (!function_exists('get_field') || !function_exists('update_field')) {
    fwrite(STDERR, "ACF not loaded\n");
    exit(1);
}

$jsonFile = $argv[1] ?? '';
if (!$jsonFile || !file_exists($jsonFile)) {
    fwrite(STDERR, "JSON file missing\n");
    exit(1);
}
$data = json_decode(file_get_contents($jsonFile), true);
if (!is_array($data)) {
    fwrite(STDERR, "Invalid JSON\n");
    exit(1);
}

function seed_fields($postId, $data) {
    $hero = blankslate_contact_default_hero_image();
    if (!empty($data['contacto_hero_image_path'])) {
        $upload_dir = wp_upload_dir();
        $hero['url'] = $hero['link'] = trailingslashit($upload_dir['baseurl']) . ltrim($data['contacto_hero_image_path'], '/');
    }
    update_field('contacto_hero_imagen', $hero, $postId);

    update_field('contacto_hero_titulo', $data['contacto_hero_titulo'] ?? 'CONTÁCTANOS', $postId);
    update_field('contacto_form_shortcode', $data['contacto_form_shortcode'] ?? '[elementor-template id="67"]', $postId);
    $infoBlocks = $data['contacto_info'] ?? [];
    if (function_exists('blankslate_contact_group_from_info_blocks')) {
        $infoBlocks = blankslate_contact_group_from_info_blocks($infoBlocks);
    }
    update_field('contacto_info_grupos', $infoBlocks, $postId);

    $socialLinks = $data['contacto_social'] ?? [];
    if (function_exists('blankslate_contact_group_from_social_links')) {
        $socialLinks = blankslate_contact_group_from_social_links($socialLinks);
    }
    update_field('contacto_social', $socialLinks, $postId);
}

$pages = get_posts([
    'post_type' => 'page',
    'post_status' => ['publish','draft','pending','future'],
    'meta_key' => '_wp_page_template',
    'meta_value' => 'template-contact.php',
    'posts_per_page' => -1,
]);

if (empty($pages)) {
    fwrite(STDOUT, "No contact pages found\n");
    exit(0);
}

foreach ($pages as $page) {
    seed_fields($page->ID, $data);
    fwrite(STDOUT, "Seeded ACF for page {$page->ID}\n");
}
