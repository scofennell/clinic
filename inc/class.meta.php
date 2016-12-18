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

		$this -> current_screen = get_current_screen();
		$this -> set_post_type();
		$this -> set_is_calendar_page();
		$this -> set_is_archive_session_admin();
		$this -> set_is_single_session_admin();

	}

	function set_post_type() {
		if( isset( $_REQUEST['post_type'] ) ) {
			$out = $_REQUEST['post_type'];
		} else {
			$out = FALSE;
		}

		$this -> post_type = $out;
	}

	function set_is_calendar_page() {

		$out = FALSE;

		$get_current_screen = get_current_screen();
		$base = $get_current_screen -> base;
		if( $base == 'session_page_calendar' ) {

			$out = TRUE;

		}

		$this -> is_calendar_page = $out;

	}

	function get_is_calendar_page() {

		return $this -> is_calendar_page;

	}	

	function set_is_archive_session_admin() {

		$out = TRUE;

		if( ! is_admin() ) { $out = FALSE; }


		if( isset( $_GET['post_id'] ) ) { $out = FALSE; }

		if ( $this -> post_type != 'session' ) { $out = FALSE; }

	
		if ( $this -> current_screen -> base != 'edit' ) { $out = FALSE; }

		$this -> is_archive_session_admin = $out;


	}

	function get_is_archive_session_admin() {

		return $this -> is_archive_session_admin;

	}	

	function set_is_single_session_admin() {

		$out = TRUE;
		if( ! is_admin() ) { $out = FALSE; }

		if( isset( $_GET['post_id'] ) ) { $out = FALSE; }


		if ( $this -> post_type != 'session' ) { $out = FALSE; }

		if ( $this -> current_screen -> base != 'edit' ) { $out = FALSE; }

		$this -> is_single_session_admin = $out;




	}

	function get_is_single_session_admin() {

		return $this -> is_single_session_admin;

	}		

}