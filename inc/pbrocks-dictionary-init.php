<?php
/**
 * Initialize plugin with admin menus.
 *
 * @package PBrocks_Dictionary
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

if ( is_multisite() ) {
	add_action( 'network_admin_menu', 'pbrocks_dictionary' );
}
/**
 * [pbrocks_dictionary]
 *
 * Add a page to the dashboard menu.
 *
 * @since 1.0.0
 *
 * @return array
 */
add_action( 'admin_menu', 'pbrocks_dictionary' );
/**
 * [pbrocks_dictionary]
 *
 * Create the admin menu.
 *
 * @return void
 */
function pbrocks_dictionary() { 	// phpcs:ignore
	$slug  = preg_replace( '/_+/', '-', __FUNCTION__ );
	$label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
	add_dashboard_page( $label, $label, 'manage_options', $slug . '.php', 'pbrocks_dictionary_page' );
}

/**
 * [pbrocks_dictionary_page]
 *
 * @since 1.0.0
 *
 * @return void
 */
function pbrocks_dictionary_page() {
	global $wpdb;
	echo '<div class="wrap">';
	// phpcs:ignore
	echo '<h2>' . esc_html__( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) ) . '</h2>';
	$screen         = get_current_screen();
	$site_theme     = wp_get_theme();
	$site_prefix    = $wpdb->prefix;
	$prefix_message = '$site_prefix = ' . esc_html( $site_prefix );
	if ( is_multisite() ) {
		$network_prefix  = $wpdb->base_prefix;
		$prefix_message .= '<br>$network_prefix = ' . esc_html( $network_prefix );
		$blog_id         = get_current_blog_id();
		$prefix_message .= '<br>$site_prefix = ' . esc_html( $network_prefix . $blog_id ) . '_';
	}

	do_action( 'add_to_pbrocks_dictionary_dash' );

	echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . esc_html( $screen->id ) . '</span></h4>';
	echo 'Your WordPress version is ' . esc_html( get_bloginfo( 'version' ) );

	$current_theme = wp_get_theme();
	echo '<h4>' . esc_html( $prefix_message ) . '</h4>';
	echo '<h4>Theme is ' . sprintf(
		// translators: Placeholder is theme info.
		esc_html__( '%1$s and is version %2$s', 'pbrocks-dictionary' ),
		esc_html( $current_theme->get( 'Name' ) ),
		esc_html( $current_theme->get( 'Version' ) )
	) . '</h4>';
	echo '<h4>Templates found in ' . esc_url( get_template_directory() ) . '</h4>';
	echo '<h4>Stylesheet found in ' . esc_url( get_stylesheet_directory() ) . '</h4>';
	echo '</div>';
}
