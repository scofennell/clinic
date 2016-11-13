<?php

new Clinic_Bootstrap;

class Clinic_Bootstrap {

	function __construct() {

		$this -> load();

		$this -> load_admin();
	
	}
	
	/**
	 * If this plugin does not have all of its dependencies, it refuses to load its files.
	 * 
	 * @return boolean Returns FALSE if it's missing dependencies, else TRUE.
	 */
	function load() {

		#if( ! defined( 'LXB_ZENDESK' ) ) { return FALSE; }

		// For each php file in the inc/ folder, require it.
		foreach( glob( CLINIC_PATH . 'inc/*.php' ) as $filename ) {

			require_once( $filename );

		}

		return TRUE;

	}

	function load_admin() {

		if( ! is_admin() ) { return FALSE; }

		// For each php file in the inc/ folder, require it.
		foreach( glob( CLINIC_PATH . 'inc/admin/*.php' ) as $filename ) {

			require_once( $filename );

		}

		return TRUE;

	}

}