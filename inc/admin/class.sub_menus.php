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

		$view = FALSE;
		if( isset( $_GET['view'] ) ) {
			$view = sanitize_text_field( $_GET['view'] );
		}

		$year = FALSE;
		if( isset( $_GET['year'] ) ) {
			$year = absint( $_GET['year'] );
		}

		$month = FALSE;
		if( isset( $_GET['month'] ) ) {
			$month = absint( $_GET['month'] );
		}

		$week = FALSE;
		if( isset( $_GET['week'] ) ) {
			$week = absint( $_GET['week'] );
		}

		$day = FALSE;
		if( isset( $_GET['day'] ) ) {
			$day = absint( $_GET['day'] );
		}								

		$calendar = new CLINIC_Calendar( $view, $year, $month, $week, $day );

		add_submenu_page(
			'edit.php?post_type=session',
			$calendar -> get_page_title(),
			esc_html__( 'Calendar', 'clinic' ),
			'edit_posts',
			'calendar',
			array( $calendar, 'the_page' )
		);

	}

}