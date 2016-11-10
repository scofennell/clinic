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

}