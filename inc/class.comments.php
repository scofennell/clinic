<?php

/**
 * De/register comments.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_comments_init() {

	new CLINIC_Comments;

}
add_action( 'plugins_loaded', 'clinic_comments_init', 100 ); 

class CLINIC_Comments {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'init', array( $this, 'init' ) );

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
	
	}

	function admin_menu() {

		remove_menu_page( 'edit-comments.php' );

	}

	function init() {

		/*remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
		remove_meta_box( 'commentsdiv', 'post', 'normal' );
		remove_meta_box( 'commentsdiv', 'page', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );*/

		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );

		remove_post_type_support( 'post', 'trackbacks' );
		remove_post_type_support( 'page', 'trackbacks' );

	}

	function after_setup_theme() {

		if( '' != get_option( 'default_ping_status' ) ) {
			update_option( 'default_ping_status', '' );
		}

		if( '' != get_option( 'default_comment_status' ) ) {
			update_option( 'default_comment_status', '' );
		}

	}

}