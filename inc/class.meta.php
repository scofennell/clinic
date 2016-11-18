<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Meta {

	function __construct() {

		$this -> set_is_settings_page();

	}

	function set_is_settings_page() {

		$out = FALSE;

		$get_current_screen = get_current_screen();
		$base = $get_current_screen -> base;
		if( $base == 'session_page_calendar' ) {

			$out = TRUE;

		}

		$this -> is_settings_page = $out;

	}

	function get_is_settings_page() {

		return $this -> is_settings_page;

	}	

}