<?php

/**
 * Enqueue scripts and styles.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_script_init() {

	new CLINIC_Script;

}
add_action( 'plugins_loaded', 'clinic_script_init', 10 ); 

class CLINIC_Script {

	function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'script' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'style' ) );

	}

	function script() {

		wp_register_script(
			CLINIC . '-script',
			CLINIC_URL . 'js/script.js',
			array( 'jquery' ),
			CLINIC_VERSION,
			TRUE
		);

		$meta = new CLINIC_Meta;
		if( $meta -> get_is_calendar_page() ) {
			wp_enqueue_script( CLINIC . '-script' );
		}

	}

	function style() {

		wp_register_style(
			CLINIC . '-style',
			CLINIC_URL . 'css/style.css',
			array(),
			CLINIC_VERSION
		);

		$meta = new CLINIC_Meta;
		if( $meta -> get_is_calendar_page() ) {
			wp_enqueue_style( CLINIC . '-style' );
		}	
		
	}	

}