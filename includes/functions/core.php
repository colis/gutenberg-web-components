<?php
/**
 * Core plugin functionality.
 *
 * @package GutenbergWebComponents
 */

namespace GutenbergWebComponents\Core;

use \WP_Error as WP_Error;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );
	add_action( 'enqueue_block_assets', $n( 'custom_elements_scripts' ) );
	add_action( 'enqueue_block_editor_assets', $n( 'blocks_scripts' ) );

	// Editor styles. add_editor_style() doesn't work outside of a theme.
	add_filter( 'mce_css', $n( 'mce_css' ) );
	// Hook to allow async or defer on asset loading.
	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );

	do_action( 'gutenberg_web_components_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'gutenberg-web-components' );
	load_textdomain( 'gutenberg-web-components', WP_LANG_DIR . '/gutenberg-web-components/gutenberg-web-components-' . $locale . '.mo' );
	load_plugin_textdomain( 'gutenberg-web-components', false, plugin_basename( GUTENBERG_WEB_COMPONENTS_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'gutenberg_web_components_init' );
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}


/**
 * The list of knows contexts for enqueuing scripts/styles.
 *
 * @return array
 */
function get_enqueue_contexts() {
	return [ 'admin', 'frontend', 'shared', 'blocks', 'custom-elements' ];
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string|WP_Error URL
 */
function script_url( $script, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in GutenbergWebComponents script loader.' );
	}

	return GUTENBERG_WEB_COMPONENTS_URL . "dist/js/${script}.js";

}

/**
 * Generate an URL to a stylesheet, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $stylesheet Stylesheet file name (no .css extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string URL
 */
function style_url( $stylesheet, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in GutenbergWebComponents stylesheet loader.' );
	}

	return GUTENBERG_WEB_COMPONENTS_URL . "dist/css/${stylesheet}.css";

}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'gutenberg_web_components_shared',
		script_url( 'shared', 'shared' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

	wp_enqueue_script(
		'gutenberg_web_components_frontend',
		script_url( 'frontend', 'frontend' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts() {

	wp_enqueue_script(
		'gutenberg_web_components_shared',
		script_url( 'shared', 'shared' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

	wp_enqueue_script(
		'gutenberg_web_components_admin',
		script_url( 'admin', 'admin' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

}

/**
 * Enqueue scripts for custom elements.
 *
 * @return void
 */
function custom_elements_scripts() {

	wp_enqueue_script(
		'gutenberg_web_components_custom_elements',
		script_url( 'custom-elements', 'custom-elements' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

}

/**
 * Enqueue scripts for gutenberg blocks.
 *
 * @return void
 */
function blocks_scripts() {

	wp_enqueue_script(
		'gutenberg_web_components_blocks',
		script_url( 'blocks', 'blocks' ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api', 'wp-editor', 'gutenberg_web_components_custom_elements' ],
		GUTENBERG_WEB_COMPONENTS_VERSION,
		true
	);

}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	wp_enqueue_style(
		'gutenberg_web_components_shared',
		style_url( 'shared-style', 'shared' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION
	);

	if ( is_admin() ) {
		wp_enqueue_style(
			'gutenberg_web_components_admin',
			style_url( 'admin-style', 'admin' ),
			[],
			GUTENBERG_WEB_COMPONENTS_VERSION
		);
	} else {
		wp_enqueue_style(
			'gutenberg_web_components_frontend',
			style_url( 'style', 'frontend' ),
			[],
			GUTENBERG_WEB_COMPONENTS_VERSION
		);
	}

}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles() {

	wp_enqueue_style(
		'gutenberg_web_components_shared',
		style_url( 'shared-style', 'shared' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION
	);

	wp_enqueue_style(
		'gutenberg_web_components_admin',
		style_url( 'admin-style', 'admin' ),
		[],
		GUTENBERG_WEB_COMPONENTS_VERSION
	);

}

/**
 * Enqueue editor styles. Filters the comma-delimited list of stylesheets to load in TinyMCE.
 *
 * @param string $stylesheets Comma-delimited list of stylesheets.
 * @return string
 */
function mce_css( $stylesheets ) {
	if ( ! empty( $stylesheets ) ) {
		$stylesheets .= ',';
	}

	return $stylesheets . GUTENBERG_WEB_COMPONENTS_URL . ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
			'assets/css/frontend/editor-style.css' :
			'dist/css/editor-style.min.css' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$script_execution = wp_scripts()->get_data( $handle, 'script_execution' );

	if ( ! $script_execution ) {
		return $tag;
	}

	if ( 'async' !== $script_execution && 'defer' !== $script_execution ) {
		return $tag; // _doing_it_wrong()?
	}

	// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
	foreach ( wp_scripts()->registered as $script ) {
		if ( in_array( $handle, $script->deps, true ) ) {
			return $tag;
		}
	}

	// Add the attribute if it hasn't already been added.
	if ( ! preg_match( ":\s$script_execution(=|>|\s):", $tag ) ) {
		$tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );
	}

	return $tag;
}
