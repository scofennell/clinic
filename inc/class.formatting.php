<?php

/**
 * A class for formatting strings and arrays.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Formatting {

	function __construct( $in ) {

		$this -> in = $in;

	}

	function array_to_options( $current ) {

		$out = '';

		foreach( $this -> in as $k => $v ) {
			$selected = selected( $k, $current, FALSE );
			$out .= "<option $selected value='$k'>$v</option>";
		}

		return $out;

	}

	function array_to_comma_sep( $label_field, $href_cb, $before_last = 'and' ) {

		$in  = $this -> in;
		$out = '';

		if( ! is_array( $in ) ) { return FALSE; }

		$count = count( $in );
		$i = 0;
		foreach( $in as $k => $v ) {

			$i++;

			$label = '';
			if( is_object( $v ) ) {
				$label = $v -> $label_field;
			}

			if( ! empty( $href_cb ) ) {
				$href = esc_url( call_user_func( $href_cb, $k ) );
				$out .= "<a href='$href'>" . $label . '</a>';
			} else {
				$out .= $label;	
			}

			if( $i < ( $count - 1 ) ) {
				$out .= esc_html__( ', ', 'clinic' );
			} elseif( $i < $count ) {

				if( $before_last == 'and' ) {

					$out .= esc_html__( ' and ', 'clinic' );	
	
				} elseif( $before_last == 'comma' ) {
					
					$out .= esc_html__( ', ', 'clinic' );	
	
				}

			}

		}

		return $out;

	}

}