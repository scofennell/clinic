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
		add_action( 'save_post_session', array( $this, 'save_session' ) );
		#add_action( 'add_meta_boxes_service', array( $this, 'service' ) );
		#add_action( 'add_meta_boxes_location', array( $this, 'location' ) );
		#add_action( 'add_meta_boxes_testimonial', array( $this, 'testimonial' ) );

	}

	function session() {

		add_meta_box( 
    		'session-meta-box',
        	esc_html__( 'Session Info', 'clinic' ),
        	array( $this, 'session_cb' ),
        	'session',
        	'normal',
        	'default'
    	);

	}

	function session_cb( $post ) {

		$inputs = $this -> get_session_inputs();

		$out = $this -> build_meta_inputs( $post, $inputs, __FUNCTION__ );

		echo $out;

	}

	function save_session() {

		$inputs = $this -> get_session_inputs();

		return $this -> save_meta_inputs( $post_id, $post, $update, 'session', $inputs );

	}

	function save_meta_inputs( $post_id, $post, $update, $meta_box_slug, $inputs ) {

		if( ! isset( $_POST[ $meta_box_slug ] ) ) { return $post_id; }
		if( ! wp_verify_nonce( $_POST[ $meta_box_slug ], $meta_box_slug ) ) { return $post_id; }
		if( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		if( defined( 'DOING_AUTOSAVE' ) ) {
			if( DOING_AUTOSAVE ) {
				return $post_id;
			}
		}

		foreach( $inputs as $input_slug => $input ) {

		}

		if( isset( $_POST['hello'] ) ) {
		    update_post_meta( $post_id, 'hello', sanitize_text_field( $_POST['hello'] ) );
		} else {
			delete_post_meta( $post_id, 'hello' );
		}

	}

	function get_session_inputs() {

		$out = array(

			'client_ids' => array(
				'type'    => 'checkbox_group',
				'options' => call_user_func( array( 'CLINIC_Clients', 'get_as_kv' ) ),
			),

			'provider_ids' => array(
				'type'    => 'checkbox_group',
				'options' => call_user_func( array( 'CLINIC_Providers', 'get_as_kv' ) ),
			),

		);

		return $out;

	}

	function build_meta_inputs( $post, $inputs, $meta_box_slug ) {

		$out = '';

		$nonce = wp_nonce_field( $meta_box_slug, $meta_box_slug, TRUE, FALSE );

		foreach( $inputs as $input_slug => $input ) {

			$out .= $this -> build_meta_input( $post, $input_slug, $input );

		}

		return $out;

	}

	function build_meta_input( $post, $input_slug, $input ) {

		$out = 'hello';

		return $out;

	}

	function service() {


	}

	function location() {

		
	}

	function testimonial() {


	}

}