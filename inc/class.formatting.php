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

	function array_to_comma_sep( $value ) {

		$in  = $this -> in;
		$out = '';

		if( ! is_array( $in ) ) { return FALSE; }

		$count = count( $in );
		$i = 0;
		foreach( $in as $k => $v ) {

			$i++;
			$out .= $v -> $value;
			if( $i < ( $count - 1 ) ) {
				$out .= esc_html__( ', ', 'clinic' );
			} elseif( $i < $count ) {
				$out .= esc_html__( ' and ', 'clinic' );	
			}

		}

		return $out;

	}

}