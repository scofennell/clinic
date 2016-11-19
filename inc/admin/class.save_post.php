<?php

/**
 * Do some stuff when you save a post.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_save_post_init() {

	new CLINIC_Save_Post;

}
add_action( 'plugins_loaded', 'clinic_save_post_init', 110 ); 

class CLINIC_Save_Post {

	function __construct() {

		// save_post_{post_type}
		add_action( 'save_post_session', array( $this, 'keywordify' ) );

	}

	function keywordify( $post_id ) {

		// If this is a revision, get real post ID.
		$parent_id = wp_is_post_revision( $post_id );
		if( $parent_id ) {
			$post_id = $parent_id;
		}

		$post = get_post( $post_id );

		$post -> post_title = $this -> get_keywords( $post_id );

		// Unhook this function so it doesn't loop infinitely
		remove_action( 'save_post_session', array( $this, __FUNCTION__ ) );

		wp_update_post( $post );

		// re-hook this function
		add_action( 'save_post_session', array( $this, __FUNCTION__ ) );

	}

	function get_keywords( $post_id ) {

		$session = new CLINIC_Session( $post_id );

		return $session -> get_keywords();

	}

}