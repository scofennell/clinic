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
				'label'           => esc_html__( 'Session Timeline', 'clinic' ),
				'type'            => 'timeline',
				'sanitization_cb' => 'sanitize_text_field',
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

		} elseif( $type == 'timeline' ) {

			if( ! is_array( $value ) ) { $value = array( 0, 0 ); }

			$times = array(
				'start' => array(
					'label' => esc_html__( 'Start:', 'clinic' ),
					'value' => $value[0],
				),
				'end' => array(
					'label' => esc_html__( 'End:', 'clinic' ),
					'value' => $value[1],
				),			
			);

			$input = $this -> touch_time( $times );
			
			$out = "
				<h4 class='$class-label' for='$id'>$label</h4>
				$input
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

	function touch_time( $times ) {

		$out = '';

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		global $wp_locale;

		$current_time = current_time( 'timestamp' );
	
		$minute_label = esc_html__( 'Minute', 'clinic' );			
		$hour_label   = esc_html__( 'Hour', 'clinic' );
		$day_label    = esc_html__( 'Day', 'clinic' );
		$month_label  = esc_html__( 'Month', 'clinic' );
		$year_label   = esc_html__( 'Year', 'clinic' );

		foreach( $times as $time_slug => $time ) {

			$label = esc_html( $time['label'] );
			$value = absint( $time['value'] );

			if( empty( $value ) ) { $value = $current_time; }

			$jj = date( 'd', $value );
			$mm = date( 'm', $value );
			$aa = date( 'Y', $value );
			$hh = date( 'H', $value );
			$mn = date( 'i', $value );
			$ss = date( 's', $value );

			$month = "<label><span class='screen-reader-text'>$month_label</span><select id='mm' name='mm'>";
			
			for ( $i = 1; $i < 13; $i = $i +1 ) {
				$monthnum = zeroise($i, 2);
				$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
				$selected = selected( $monthnum, $mm, false );
				$month .= "<option value='$monthnum' data-text='$monthtext' $selected>";
			
				/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
				$month .= sprintf( __( '%1$s-%2$s' ), $monthnum, $monthtext ) . "</option>";
			}
			
			$month .= '</select></label>';

			$day = "
				<label>
					<span class='screen-reader-text'>$day_label</span>
					<input type='number' id='jj' name='jj' value='$jj' size='2' maxlength='2' autocomplete='off'>
				</label>
			";
		
			$year = "
				<label>
					<span class='screen-reader-text'>$year_label</span>
					<input type='number'  id='aa' name='aa' value='$aa' size='4' maxlength='4' autocomplete='off'>
				</label>
			";

			$hour = "
				<label>
					<span class='screen-reader-text'>$hour_label</span>
					<input type='number' id='hh' name='hh' value='$hh' size='2' maxlength='2' autocomplete='off'>
				</label>
			";

			$minute = "
				<label>
					<span class='screen-reader-text'>$minute_label</span>
					<input type='number' id='mn' name='mn' value='$mn' size='2' maxlength='2' autocomplete='off'>
				</label>
			";

			/* 1: month, 2: day, 3: year, 4: hour, 5: minute */
			$time = sprintf( __( '%1$s %2$s, %3$s @ %4$s:%5$s' ), $month, $day, $year, $hour, $minute );

			$hidden_value = '';

			$out .= "
				<div class='$class-control'>
					<label>$label</label>
					$time
					<input type='number' value='$hidden_value' name='$time_slug'>
				</div>
			";

		}

		$out = "
			<div class='$class'>
				$out
			</div>
		";

		return $out;


	}

}