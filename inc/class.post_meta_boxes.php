<?php

/**
 * Register post types.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_post_meta_boxes_init() {

	new CLINIC_Post_Meta_Boxes;

}
add_action( 'plugins_loaded', 'clinic_post_meta_boxes_init', 110 ); 

class CLINIC_Post_Meta_Boxes {

	function __construct() {

		add_action( 'add_meta_boxes_session', array( $this, 'session' ) );
		#add_action( 'add_meta_boxes_service', array( $this, 'service' ) );
		#add_action( 'add_meta_boxes_location', array( $this, 'location' ) );
		#add_action( 'add_meta_boxes_testimonial', array( $this, 'testimonial' ) );

	}

	function session() {

		add_meta_box( 
    		'session-meta-box',
        	esc_html__( 'Session Meta Box', 'clinic' ),
        	array( $this, 'session_cb' ),
        	'session',
        	'normal',
        	'default'
    	);

	}

	function session_cb() {

		echo 'hello world';

	}

	function service() {

		

	}

	function location() {

		
	}

	function testimonial() {

		

	}

}