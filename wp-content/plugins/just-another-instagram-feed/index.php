<?php 
/*
	Plugin Name: Just Another Instagram Feed (JAIF)
	Author: Sebastian Forsberg
	Text Domain: just-another-insta-feed
	Description: A highly customizable Instagram feed plugin that allows you to place any specified feed in the form of widgets, shortcodes or directly as PHP code. Templates exist for every section of output created by Just Another Instagram Plugin allowing you to fully customize the display of your feeds.  
	Version: 0.3
*/

// Version 
if( !defined( 'JAIF_VERSION' ) ) define( 'JAIF_VERSION', '0.2' );

// Main File ( __FILE__ )
if( !defined('JAIF_MAIN') ) define( 'JAIF_MAIN', __FILE__  );

// Root Directory
if( !defined('JAIF_ROOT') ) define( 'JAIF_ROOT', dirname( JAIF_MAIN ) );

// Root URL
if( !defined( 'JAIF_URL' ) ) define ( 'JAIF_URL', plugins_url( '', __FILE__ ) );

// Template Directory
if( !defined('JAIF_TEMPLATE') ) define( 'JAIF_TEMPLATE', JAIF_ROOT . '/templates/' );

// Cache Directory
if( !defined('JAIF_CACHE') ) define( 'JAIF_CACHE', JAIF_ROOT . '/inc/instagram_cache/' );

// Settings Name 
if( !defined('JAIF_SETTINGS') ) define( 'JAIF_SETTINGS', 'jaif_settings' );

// Settings Page Name
if( !defined('JAIF_SETTINGS_PAGE') ) define( 'JAIF_SETTINGS_PAGE', 'just-another-insta-feed' );

/* Includes */
require_once( JAIF_ROOT . '/inc/dencrypt.php' );
require_once( JAIF_ROOT . '/inc/functions.php' );
require_once( JAIF_ROOT . '/inc/instagram.class.php' );
require_once( JAIF_ROOT . '/inc/jaif.php' );
require_once( JAIF_ROOT . '/inc/helpers.php' );
require_once( JAIF_ROOT . '/inc/widget.php' );
require_once( JAIF_ROOT . '/inc/admin.php' );

//Add widget
add_action( 'widgets_init', 'jaif_register_widget' );

//Add shortcode
add_shortcode( 'jaif', 'jaif_shortcode' );

/* Setup Just Another Instagram Feed Object */
$jaif = new jaif();
?>