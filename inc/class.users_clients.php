<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Clients extends CLINIC_Users {

	function set_role() {

		$this -> role = 'client';

	}

}