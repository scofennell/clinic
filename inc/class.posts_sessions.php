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

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$timestamp = $this -> get_timestamp();

		$the_query = $this -> query();

		if ( ! $the_query -> have_posts() ) { return FALSE; }
	
		$out = '';
		
		$show_hide    = sanitize_html_class( CLINIC . '-show_hide' );
		$shows_hides  = sanitize_html_class( CLINIC . '-shows_hides' );
		$shown_hidden = sanitize_html_class( CLINIC . '-shown_hidden' );

		while( $the_query -> have_posts() ) {
			$the_query -> the_post();
			
			$session = new CLINIC_Session( get_the_ID() );

			$timeline = $session -> get_timeline( $timestamp );
			if( ! empty( $timeline ) ) {
				$timeline = sprintf( __( '%s:', 'clinic' ), $timeline );
			}

			$maybe_starts_days_before = 'started_this_day';
			if( $session -> starts_days_before( $timestamp ) ) {
				$maybe_starts_days_before = 'starts_days_before';
			}

			$maybe_ends_days_ahead = 'ends_this_day';
			if( $session -> ends_days_ahead( $timestamp ) ) {
				$maybe_ends_days_ahead = 'ends_days_ahead';
			}			

			$post_title = get_the_title();
			
			$clients   = esc_html__( 'Clients: %s', 'clinic' );
			$providers = esc_html__( 'Clients: %s', 'clinic' );
			$locations = esc_html__( 'Clients: %s', 'clinic' );
			$services  = esc_html__( 'Clients: %s', 'clinic' );

			$permalink  = esc_url( get_edit_post_link() );
			$out .= "
				<li class='$show_hide $class-li $class-li-$maybe_starts_days_before $class-li-$maybe_ends_days_ahead'>
					$timeline
					<h4 class='$class-title'><a href='$permalink'>$post_title</a><a class='$shows_hides' href='#'>&#9660;</a></h4>
					<ul class='$class-details $shown_hidden'>
						<li class='$class-detail'>$clients</li>
						<li class='$class-detail'>$providers</li>
						<li class='$class-detail'>$locations</li>
						<li class='$class-detail'>$services</li>
					</ul>
				</li>
			";
		}

		wp_reset_postdata();

		$out = "<ul class='$class'>$out</ul>";

		return $out;

	}

}