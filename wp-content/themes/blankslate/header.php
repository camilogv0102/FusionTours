<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php blankslate_schema_type(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="fusion-wrapper hfeed">
<?php get_template_part( 'template-parts/site', 'header' ); ?>
<div id="container" class="fusion-container">
<main id="content" role="main">
