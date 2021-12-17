<?php
/**
 * Plugin Name: PBrocks Dictionary TESTING
 * Plugin URI:  https://github.com/pbrocks/pbrocks-dictionary
 * Description: Basis for adding functionality to a WP site.
 * Version:     1.0.2
 * Author:      PBrocks
 * Author URI:  https://github.com/pbrocks
 * License:     GPL3
 * Text Domain: pbrocks-dictionary
 * Domain Path: /languages
 *
 * @package PBrocks_Dictionary
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

register_activation_hook( __FILE__, 'pbrocks_dictionary_welcome_install' );
add_action( 'admin_init', 'pbrocks_dictionary_welcome', 11 );
add_action( 'plugins_loaded', 'pbrocks_dictionary_php_initialization' );
/**
 * [pbrocks_dictionary_php_initialization]
 *
 * Initialize php files
 *
 * @return void
 */
function pbrocks_dictionary_php_initialization() {
	// Include all php files in /inc directory.
	if ( file_exists( __DIR__ . '/inc' ) && is_dir( __DIR__ . '/inc' ) ) {
		foreach ( glob( __DIR__ . '/inc/*.php' ) as $filename ) {
			require $filename;
		}
	}

	/**
	 * Include all php files in /inc/classes directory.
	 */
	if ( file_exists( __DIR__ . '/inc/classes' ) && is_dir( __DIR__ . '/inc/classes' ) ) {
		foreach ( glob( __DIR__ . '/inc/classes/*.php' ) as $filename ) {
			require $filename;
		}
	}
}

add_action( 'plugins_loaded', 'pbrocks_dictionary_load_textdomain' );
/**
 * [pbrocks_dictionary_load_textdomain]
 *
 * Setup WordPress localization support.
 *
 * @return void
 */
function pbrocks_dictionary_load_textdomain() {
	load_plugin_textdomain( 'pbrocks-dictionary', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pbrocks_dictionary_plugin_action_links' );
/**
 * [pbrocks_dictionary_plugin_action_links]
 *
 * Show action links on the plugin screen.
 *
 * @param    mixed $links Plugin Action links.
 *
 * @return    array
 */
function pbrocks_dictionary_plugin_action_links( $links ) {
	$action_links = array(
		'getting_started' => '<a href="' . esc_url( admin_url( 'index.php?page=pbrocks-dictionary.php' ) ) . '" title="' . esc_attr__( 'Get started with pbrocks-dictionary', 'pbrocks-dictionary' ) . '">' . esc_html__( 'Go to Admin Page', 'pbrocks-dictionary' ) . '</a>',
	);
	return array_merge( $action_links, $links );
}

/**
 * [pbrocks_dictionary_welcome_install description]
 *
 * @return void
 */
function pbrocks_dictionary_welcome_install() {
	set_transient( 'pbrocks_dictionary_activated', true, 30 );
}

/**
 * [pbrocks_dictionary_welcome]
 *
 * Check the plugin activated transient exists if does then redirect.
 *
 * @return void
 */
function pbrocks_dictionary_welcome() {
	if ( ! get_transient( 'pbrocks_dictionary_activated' ) ) {
		return;
	}

	delete_transient( 'pbrocks_dictionary_activated' );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page' => 'pbrocks-dictionary.php',
			),
			admin_url( 'index.php' )
		)
	);
	exit;
}
