<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Locations extends CLINIC_Posts {
	
	function set_post_type() {

		$this -> post_type = 'location';

	}

}