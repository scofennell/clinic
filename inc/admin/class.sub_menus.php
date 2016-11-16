<?php

/**
 * Register sub menus.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_sub_menus_init() {

	new CLINIC_Sub_Menus;

}
add_action( 'plugins_loaded', 'clinic_sub_menus_init', 100 ); 

class CLINIC_Sub_Menus {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'session' ) );
	
	}

	function session() {

		$calendar = new CLINIC_Calendar;

		add_submenu_page(
			'edit.php?post_type=session',
			esc_html__( 'Calendar', 'clinic' ),
			esc_html__( 'Calendar', 'clinic' ),
			'edit_posts',
			'calendar',
			array( $calendar, 'the_page' )
		);

	}

}