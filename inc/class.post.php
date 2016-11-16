<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

abstract class CLINIC_Post {

	function __construct( $post_id ) {

		$this -> set_post_id( $post_id );
	
	}

	function set_post_id( $post_id ) {

		$this -> post_id = $post_id;

	}

	function get() {
		return 'hello';
	}

	function get_as_kv() {
		return 'hello';
	}

	function get_meta( $key ) {

		return get_post_meta( $this -> post_id, CLINIC . '-' . $key, TRUE );

	}

	function delete_meta( $key ) {

		return delete_post_meta( $this -> post_id, CLINIC . '-' . $key );

	}

	function update_meta( $key, $new_value ) {

		return update_post_meta( $this -> post_id, CLINIC . '-' . $key, $new_value );

	}		

}