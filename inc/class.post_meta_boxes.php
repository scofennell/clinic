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

		$out = $this -> build_meta_inputs( $post, $inputs, 'session' );

		echo $out;

	}

	function save_session(  $post_id ) {

		$inputs = $this -> get_session_inputs();

		return $this -> save_meta_inputs( $post_id, 'session', $inputs );

	}

	function save_meta_inputs( $post_id, $meta_box_slug, $inputs ) {



		if( ! isset( $_POST[ $meta_box_slug ] ) ) { return $post_id; }

		if( ! wp_verify_nonce( $_POST[ $meta_box_slug ], $meta_box_slug ) ) { return $post_id; }
	
		if( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		
		if ( is_multisite() ) {
			if( ms_is_switched() ) { return $post_id; }
		} 

		if( defined( 'DOING_AUTOSAVE' ) ) {
			if( DOING_AUTOSAVE ) { return $post_id; }
		}

		$get_post_custom = get_post_custom( $post_id );

		foreach( $inputs as $input_slug => $input ) {

			$old_value = $get_post_custom[ $input_slug ];

			if( ! isset( $_POST[ $input_slug ] ) ) {
				delete_post_meta( $post_id, $input_slug );
				continue;
			}

			if( is_scalar( $_POST[ $input_slug ] ) ) {
				$new_value = call_user_func( $input['sanitization_cb'], $_POST[ $input_slug ] );
			} elseif( is_array( $_POST[ $input_slug ] ) ) {
				$new_value = array_map( $input['sanitization_cb'], $_POST[ $input_slug ] );
			}

			if( $old_value === $new_value ) { continue; }

			if( empty( $new_value ) ) {
				delete_post_meta( $post_id, $input_slug );
				continue;
			}

			/*wp_die(
				var_dump(
					array(
						$post_id,
						$meta_box_slug,
						$new_value,
						$old_value,
						$get_post_custom,
						$inputs,
						$_POST,
					)
				)
			);*/

			update_post_meta( $post_id, $input_slug, $new_value );

		}

	}

	function get_session_inputs() {

		$clients = new CLINIC_Clients;
		$providers = new CLINIC_Providers;

		$out = array(

			'client_ids' => array(
				'label'         => esc_html__( 'Which Clients?', 'clinic' ),
				'type'          => 'checkbox_group',
				'options'       => $clients -> get_as_kv(),
				'sanitization_cb' => 'absint',
			),

			'provider_ids' => array(
				'label'         => esc_html__( 'Which Providers?', 'clinic' ),
				'type'          => 'checkbox_group',
				'options'       => $providers -> get_as_kv(),
				'sanitization_cb' => 'absint',
			),

			'time_window' => array(
				'label' => esc_html__( 'Session Timeline', 'clinic' ),
				'type'  => wp_die( https://developer.wordpress.org/reference/functions/touch_time/ )
			),

		);

		return $out;

	}

	function build_meta_inputs( $post, $inputs, $meta_box_slug ) {

		$out = wp_nonce_field( $meta_box_slug, $meta_box_slug, TRUE, FALSE );

		foreach( $inputs as $input_slug => $input ) {

			$out .= $this -> build_meta_input( $post, $input_slug, $input );

		}

		return $out;

	}

	function build_meta_input( $post, $input_slug, $input ) {

		$out = '';

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );
		$id    = esc_attr( $input_slug );
		$label = esc_html( $input['label'] );
		$name  = esc_attr( $input_slug );
		$type  = esc_attr( $input['type'] );
		$value = get_post_meta( $post -> ID, $input_slug, TRUE );

		$options = '';
		if( isset( $input['options'] ) ) {
			$options = $input['options'];
		}

		$placeholder = '';
		if( isset( $input['placeholder'] ) ) {
			$placeholder = esc_attr( $input['placeholder'] );
		}

		if( $type == 'checkbox_group' ) {

			$name_square = $name . '[]';

			$inputs = '';
			foreach( $options as $option_k => $option_v ) {

				$checked = '';
				if( is_array( $value ) ) {
					if( in_array( $option_k, $value ) ) {
						$checked = 'checked';
					}
				}

				$inputs .= "
					<div class='$class-group-input'>	
						<input $checked id='$id-$option_k' name='$name_square' type='checkbox' value='$option_k'>
						<label for='$id-$option_k'>$option_v</label>
					</div>
				";

			}

			if( empty( $inputs ) ) { $inputs = esc_html__( '(Not Applicable)', 'clinic' ); }

			$inputs = "<div class='$class-group'>$inputs</div>";

			$out = "
				<h4 class='$class-label'>$label</h4>
				$inputs
			";

		} else {

			$out = "
				<label class='$class-label' for='$id'>$label</label>
				<input class='$class-input' id='$id' name='$name' placeholder='$placeholder' type='$type' value='$value'>
			";

		}

		$out = "<div class='$class'>$out</div>";

		return $out;

	}

	function service() {


	}

	function location() {

		
	}

	function testimonial() {


	}

}