<?php

/**
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_template_tag_filters() {

	new CLINIC_Template_Tag_Filters;

}
add_action( 'plugins_loaded', 'clinic_template_tag_filters', 100 ); 

class CLINIC_Template_Tag_Filters {

	function __construct() {

		add_filter( 'the_title', array( $this, 'the_title' ), 999, 1 );

	}

	function the_title( $in ) {

		$out = $in;

		if( empty( $out ) ) {

			$out = esc_html__( '(Untitled)', 'clinic' );

		}

		return $out;

	}

}