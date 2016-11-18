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
		add_action( 'save_post_session', array( $this, 'save_session_who' ) );
		add_action( 'save_post_session', array( $this, 'save_session_when' ) );
		add_action( 'save_post_session', array( $this, 'save_session_where' ) );
		add_action( 'save_post_session', array( $this, 'save_session_what' ) );
		#add_action( 'add_meta_boxes_service', array( $this, 'service' ) );
		#add_action( 'add_meta_boxes_location', array( $this, 'location' ) );
		#add_action( 'add_meta_boxes_testimonial', array( $this, 'testimonial' ) );

	}

	function session() {

		add_meta_box( 
    		'session_who',
        	esc_html__( 'Who', 'clinic' ),
        	array( $this, 'session_who_cb' ),
        	'session',
        	'normal',
        	'default'
    	);

    	add_meta_box( 
    		'session_when',
        	esc_html__( 'When', 'clinic' ),
        	array( $this, 'session_when_cb' ),
        	'session',
        	'normal',
        	'default'
    	);

    	add_meta_box( 
    		'session_where',
        	esc_html__( 'Where', 'clinic' ),
        	array( $this, 'session_where_cb' ),
        	'session',
        	'normal',
        	'default'
    	);	

       	add_meta_box( 
    		'session_what',
        	esc_html__( 'What', 'clinic' ),
        	array( $this, 'session_what_cb' ),
        	'session',
        	'normal',
        	'default'
    	);	 	

	}

	function get_session_who_inputs() {

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

		);

		return $out;

	}

	function session_who_cb( $post ) {

		$inputs = $this -> get_session_who_inputs();

		$post = new CLINIC_Session( $post -> ID );

		$out = $this -> build_meta_inputs( $post, 'session_who', $inputs );

		echo $out;

	}

	function save_session_who( $post_id ) {

		$inputs = $this -> get_session_who_inputs();

		$session = new CLINIC_Session( $post_id );

		return $this -> save_meta_inputs( $post_id, 'session_who', $inputs, $session );

	}

	function get_session_where_inputs() {

		$locations = new CLINIC_Locations;
		
		$out = array(

			'location_ids' => array(
				'label'         => esc_html__( 'Which Locations?', 'clinic' ),
				'type'          => 'checkbox_group',
				'options'       => $locations -> get_as_kv(),
				'sanitization_cb' => 'absint',
			),

		);

		return $out;

	}

	function session_where_cb( $post ) {

		$inputs = $this -> get_session_where_inputs();

		$post = new CLINIC_Session( $post -> ID );

		$out = $this -> build_meta_inputs( $post, 'session_where', $inputs );

		echo $out;

	}

	function save_session_where( $post_id ) {

		$inputs = $this -> get_session_where_inputs();

		$session = new CLINIC_Session( $post_id );

		return $this -> save_meta_inputs( $post_id, 'session_where', $inputs, $session );

	}

	function get_session_what_inputs() {

		$services = new CLINIC_Services;
		
		$out = array(

			'service_ids' => array(
				'label'         => esc_html__( 'Which Services?', 'clinic' ),
				'type'          => 'checkbox_group',
				'options'       => $services -> get_as_kv(),
				'sanitization_cb' => 'absint',
			),

		);

		return $out;

	}

	function session_what_cb( $post ) {

		$inputs = $this -> get_session_what_inputs();

		$post = new CLINIC_Session( $post -> ID );

		$out = $this -> build_meta_inputs( $post, 'session_what', $inputs );

		echo $out;

	}

	function save_session_what( $post_id ) {

		$inputs = $this -> get_session_what_inputs();

		$session = new CLINIC_Session( $post_id );

		return $this -> save_meta_inputs( $post_id, 'session_what', $inputs, $session );

	}	

	function session_when_cb( $post ) {

		$inputs = $this -> get_session_when_inputs();

		$post = new CLINIC_Session( $post -> ID );

		$out = $this -> build_meta_inputs( $post, 'session_when', $inputs );

		echo $out;

	}

	function save_session_when( $post_id ) {

		$inputs = $this -> get_session_when_inputs();

		$session = new CLINIC_Session( $post_id );

		return $this -> save_meta_inputs( $post_id, 'session_when', $inputs, $session );

	}	

	function get_session_when_inputs() {

		$out = array(

			'start' => array(
				'label'           => esc_html__( 'Start', 'clinic' ),
				'type'            => 'touch_time',
				'sanitization_cb' => array( $this, 'date_to_timestamp' ),
				'is_implodable'   => TRUE,
			),

			'end' => array(
				'label'           => esc_html__( 'End', 'clinic' ),
				'type'            => 'touch_time',
				'sanitization_cb' => array( $this, 'date_to_timestamp' ),
				'is_implodable'   => TRUE,
			),

		);

		return $out;

	}

	function save_meta_inputs( $post_id, $meta_box_slug, $inputs, $post ) {

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
				$post -> delete_meta( $input_slug );
				continue;
			}

			if( is_scalar( $_POST[ $input_slug ] ) ) {
			
				$new_value = call_user_func( $input['sanitization_cb'], $_POST[ $input_slug ] );
			
			} elseif( is_array( $_POST[ $input_slug ] ) ) {
			
				if( isset( $input['is_implodable'] ) ) {

					$new_value = call_user_func( $input['sanitization_cb'], $_POST[ $input_slug ] );


				} else {

					$new_value = array_map( $input['sanitization_cb'], $_POST[ $input_slug ] );

				}
			
			}

			if( $old_value === $new_value ) { continue; }

			if( empty( $new_value ) ) {
				$post -> delete_meta( $input_slug );
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

			$post -> update_meta( $input_slug, $new_value );

		}

	}

	function build_meta_inputs( $post, $meta_box_slug, $inputs ) {

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
		$value = $post -> get_meta( $input_slug );

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
			if( is_array( $options ) ) {
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
			}

			if( empty( $inputs ) ) { $inputs = esc_html__( '(Not Applicable)', 'clinic' ); }

			$inputs = "<div class='$class-group'>$inputs</div>";

			$out = "
				<h4 class='$class-label'>$label</h4>
				$inputs
			";

		} elseif( $type == 'touch_time' ) {

			$field = $this -> touch_time( $input_slug, $input, $value );
			
			$out = "
				<h4 class='$class-label' for='$id'>$label</h4>
				$field
			";

		} else {

			$out = "
				<label class='$class-label' for='$id'>$label</label>
				<input class='$class-input' id='$id' name='$name' placeholder='$placeholder' type='$type' value='$value'>
			";

		}

		$out = "<div class='$class $class-$type'>$out</div>";

		return $out;

	}

	function service() {


	}

	function location() {

		
	}

	function testimonial() {


	}

	function touch_time( $input_slug, $input, $value ) {

		$label = $input['label'];

		$out = '';

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		global $wp_locale;

		$minute_label = esc_html__( 'Minute', 'clinic' );			
		$hour_label   = esc_html__( 'Hour', 'clinic' );
		$day_label    = esc_html__( 'Day', 'clinic' );
		$month_label  = esc_html__( 'Month', 'clinic' );
		$year_label   = esc_html__( 'Year', 'clinic' );

		$minute_name = $input_slug . '[mn]';			
		$hour_name   = $input_slug . '[hh]';
		$day_name    = $input_slug . '[jj]';
		$month_name  = $input_slug . '[mm]';
		$year_name   = $input_slug . '[aa]';

	
		$value = absint( $value );
		if( empty( $value ) ) { $value = current_time( 'timestamp' ); }

		$jj = date( 'd', $value );
		$mm = date( 'm', $value );
		$aa = date( 'Y', $value );
		$hh = date( 'H', $value );
		$mn = date( 'i', $value );
		$ss = date( 's', $value );

		$months = '';
		for ( $i = 1; $i < 13; $i = $i + 1 ) {

			$monthnum  = zeroise( $i, 2 );
			$monthtext = $wp_locale -> get_month( $i );
			$selected  = selected( $monthnum, $mm, FALSE );
			$months   .= "<option value='$monthnum' $selected>$monthtext</option>";
		}
		
		$month = "
			<label for='$class-$input_slug-mm'>
				<span class='screen-reader-text'>$month_label</span>
				<select class='$class-mm' name='$month_name' id='$class-$input_slug-mm'>
					$months
				</select>
			</label>
		";

		$day = "
			<label for='$class-$input_slug-jj'>
				<span class='screen-reader-text'>$day_label</span>
				<input class='$class-jj'name='$day_name' type='number' id='$class-$input_slug-jj' value='$jj' size='2' maxlength='2' autocomplete='off' min='1' max='31'>
			</label>
		";
	
		$year = "
			<label for='$class-$input_slug-aa'>
				<span class='screen-reader-text'>$year_label</span>
				<input class='$class-aa' name='$year_name' type='number' id='$class-$input_slug-aa' value='$aa' size='4' maxlength='4' autocomplete='off' min='0' max='3000'>
			</label>
		";

		$hour = "
			<label for='$class-$input_slug-hh'>
				<span class='screen-reader-text'>$hour_label</span>
				<input class='$class-hh' type='number' name='$hour_name' id='$class-$input_slug-hh' value='$hh' size='2' maxlength='2' autocomplete='off' min='0' max='23'>
			</label>
		";

		$minute = "
			<label for='$class-$input_slug-mn'>
				<span class='screen-reader-text'>$minute_label</span>
				<input class='$class-mn' type='number' name='$minute_name' id='$class-$input_slug-mn' value='$mn' size='2' maxlength='2' autocomplete='off' min='0' max='59'>
			</label>
		";

		/* 1: month, 2: day, 3: year, 4: hour, 5: minute */
		$time = sprintf( __( '%1$s %2$s, %3$s @ %4$s:%5$s' ), $month, $day, $year, $hour, $minute );

		$out .= "
			<div class='$class-control'>
				$time
			</div>
		";

		return $out;

	}

	function date_to_timestamp( $date ) {

		$mm = $date['mm'];
		$jj = $date['jj'];
		$aa = $date['aa'];
		$hh = $date['hh'];
		$mn = $date['mn'];

		$date = "$aa-$mm-$jj $hh:$mn:00";

		$out = strtotime( $date );

		return $out;



	}

}