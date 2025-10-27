<?php
// Desactivar emojis
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/*
 // Desactivar dashicons
 add_action( 'wp_enqueue_scripts', function() {
     if ( ! is_user_logged_in() ) {
       wp_deregister_style( 'dashicons' );
     }
 } );

 // Desactivar feeds de redes sociales
 function disable_feed() {
   wp_die( __( 'No hay feed disponible, por favor visita la página principal.' ) );
 }
 add_action('do_feed', 'disable_feed', 1);
 add_action('do_feed_rdf', 'disable_feed', 1);
 add_action('do_feed_rss', 'disable_feed', 1);
 add_action('do_feed_rss2', 'disable_feed', 1);
 add_action('do_feed_atom', 'disable_feed', 1);

 // Desactivar font awesome de elementor
 add_action( 'elementor/frontend/after_register_styles', function() {
   wp_deregister_style( 'elementor-icons' );
   wp_deregister_style( 'elementor-font-awesome' );
 }, 20 );
*/

  // Desactivar google fonts de elementor
//add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );