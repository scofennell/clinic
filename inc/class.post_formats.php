<?php

/**
 * Register post types, taxonomies, roles, etc...
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_post_formats_init() {

	new CLINIC_Post_Formats;

}
add_action( 'plugins_loaded', 'clinic_post_formats_init', 100 ); 

class CLINIC_Post_Formats {

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );
	
	}

	function init() {

		remove_post_type_support( 'post', 'post-formats' );
		remove_post_type_support( 'page', 'post-formats' );

	}

}