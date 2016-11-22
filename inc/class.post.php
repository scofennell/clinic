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

	function add_meta( $key, $new_value, $unique = TRUE ) {

		return add_post_meta( $this -> post_id, CLINIC . '-' . $key, $new_value, $unique );

	}

	function get_meta( $key, $single = TRUE ) {

		return get_post_meta( $this -> post_id, CLINIC . '-' . $key, $single );

	}

	function delete_meta( $key, $prev_value = NULL ) {

		if( is_null( $prev_value ) ) {

			return delete_post_meta( $this -> post_id, CLINIC . '-' . $key );

		} else {

			return delete_post_meta( $this -> post_id, CLINIC . '-' . $key, $prev_value );

		}

	}

	function update_meta( $key, $new_value, $prev_value = NULL ) {

		if( is_null( $prev_value ) ) {

			$out = update_post_meta( $this -> post_id, CLINIC . '-' . $key, $new_value );

		} else {

			$out = update_post_meta( $this -> post_id, CLINIC . '-' . $key, $new_value, $prev_value );

		}

		return $out;

	}		

	function get_meta_as_objects( $key, $obj_cb ) {
	
		$out = array();

		$meta = $this -> get_meta( $key, FALSE );

		if( ! is_array( $meta ) ) { return FALSE; }

		foreach( $meta as $id ) {

			$out[ $id ] = call_user_func( $obj_cb, $id );

		}

		return $out;

	}

	function get_meta_as_list( $key, $label, $obj_cb, $href_cb = FALSE, $before_last = 'and' ) {
	
		$meta = $this -> get_meta_as_objects( $key, $obj_cb );

		if( ! is_array( $meta ) ) { return FALSE; }

		$formatting = new CLINIC_Formatting( $meta );

		$out = $formatting -> array_to_comma_sep( $label, $href_cb, $before_last );

		return $out;

	}


}