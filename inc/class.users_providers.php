<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Providers extends CLINIC_Users {

	function set_role() {

		$this -> role = 'provider';

	}
	
}