<?php
/*
Plugin Name: Aloha Dude jQuery
Description: Quick adaptation of a plugin from the <strong>Hello, Dolly</strong> plugin. Loads JS files properly as outlined in the WordPress Codex.
Author: Hawaii Dude
Version: 1.0.1
*/

// Now we set that function up to execute when the admin_notices action is called
add_action( 'wp_enqueue_scripts', 'aloha_dude' );

function aloha_dude() {

	wp_register_script( 'dude-jquery', plugins_url( 'dude-jquery.js', __FILE__ ), array( 'jquery' ), null, true );
	wp_enqueue_script( 'dude-jquery' );

	wp_register_script( 'jquery.ui.touch-punch.min', '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquery.ui.touch-punch.min' );
}

// add_action( 'admin_head', 'dolly_css' );
function dolly_css() {
	echo "
	<style type='text/css'>
	#dolly {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}
