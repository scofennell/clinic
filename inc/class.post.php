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

	function get_meta_as_objects( $key, $obj_cb ) {
	
		$out = array();

		$meta = $this -> get_meta( $key );

		if( ! is_array( $meta ) ) { return FALSE; }

		foreach( $meta as $id ) {

			$out[ $id ] = call_user_func( $obj_cb, $id );

		}

		return $out;

	}

	function get_meta_as_list( $key, $label, $obj_cb, $href_cb ) {
	
		$meta = $this -> get_meta_as_objects( $key, $obj_cb );

		if( ! is_array( $meta ) ) { return FALSE; }

		$formatting = new CLINIC_Formatting( $meta );

		$out = $formatting -> array_to_comma_sep( $label, $href_cb );

		return $out;

	}


}