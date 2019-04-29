<?php
/**
 * Plugin Name: GutenbergWebComponents
 * Plugin URI:  https://pragmatic.agency
 * Description:
 * Version:     0.1.0
 * Author:      Pragmatic
 * Author URI:  https://pragmatic.agency
 * Text Domain: gutenberg-web-components
 * Domain Path: /languages
 *
 * @package GutenbergWebComponents
 */

// Useful global constants.
define( 'GUTENBERG_WEB_COMPONENTS_VERSION', '0.1.0' );
define( 'GUTENBERG_WEB_COMPONENTS_URL', plugin_dir_url( __FILE__ ) );
define( 'GUTENBERG_WEB_COMPONENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'GUTENBERG_WEB_COMPONENTS_INC', GUTENBERG_WEB_COMPONENTS_PATH . 'includes/' );

// Include files.
require_once GUTENBERG_WEB_COMPONENTS_INC . 'functions/core.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\GutenbergWebComponents\Core\activate' );
register_deactivation_hook( __FILE__, '\GutenbergWebComponents\Core\deactivate' );

// Bootstrap.
GutenbergWebComponents\Core\setup();

// Require Composer autoloader if it exists.
if ( file_exists( GUTENBERG_WEB_COMPONENTS_PATH . '/vendor/autoload.php' ) ) {
	require_once GUTENBERG_WEB_COMPONENTS_PATH . 'vendor/autoload.php';
}
