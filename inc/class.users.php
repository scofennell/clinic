<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

abstract class CLINIC_Users {

	function __construct() {

		$this -> set_role();
	
	}

	function get_role() {

		return $this -> role;

	}

	function get_role_obj() {

		return get_role( $this -> get_role() );

	}

	function get_role_label() {

		global $wp_roles;
    	$out = translate_user_role( $wp_roles->roles[ $this -> get_role() ]['name'] );
    	return $out;

	}	

	function get() {
		return 'hello';
	}

	function get_as_kv() {
		
		$args = array(
			'role' => $this -> get_role(),
		);

		$users = get_users( $args );

		foreach( $users as $user ) {

			$out[ $user -> ID ] = $user -> display_name;

		}

		return $out;

	}

	function get_as_options( $current = FALSE ) {
		
		$out = '';

		$array = $this -> get_as_kv();

		foreach( $array as $value => $label ) {

			$selected = '';
			if( $current ) {
				$selected = selected( $value, $current, FALSE );
			}

			$out .= "<option $selected value='$value'>$label</option>";

		}

		return $out;

	}		

}