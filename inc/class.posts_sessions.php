<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Sessions extends CLINIC_Posts {

	function set_post_type() {

		$this -> post_type = 'session';

	}

	function get_as_ul() {

		$the_query = $this -> query();

		if ( ! $the_query -> have_posts() ) { return FALSE; }
	
		$out = '';
		
		while( $the_query -> have_posts() ) {
			$the_query -> the_post();
			
			$session = new CLINIC_Session( get_the_ID() );

			$timeline = $session -> get_timeline();
			if( ! empty( $timeline ) ) {
				$timeline = sprintf( __( '%s:', 'clinic' ), $timeline );
			}

			$post_title = get_the_title();
			
			$permalink  = esc_url( get_edit_post_link() );
			$out .= "<li>$timeline <a href='$permalink'>$post_title</a></li>";
		}

		wp_reset_postdata();

		$out = "<ul>$out</ul>";

		return $out;

	}

}