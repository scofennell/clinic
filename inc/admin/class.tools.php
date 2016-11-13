<?php

/**
 * De/Register tools in wp-admin.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_tools_init() {

	new CLINIC_Tools;

}
add_action( 'plugins_loaded', 'clinic_tools_init', 100 ); 

class CLINIC_Tools {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}

	function admin_menu() {

		remove_menu_page( 'tools.php' );

	}

}