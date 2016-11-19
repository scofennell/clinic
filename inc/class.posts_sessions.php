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
		
		while( $the_query -> have_posts() ) {
			$the_query -> the_post();
			
			$session = new CLINIC_Session( get_the_ID() );

			$timeline = $session -> get_timeline( $timestamp );

			$maybe_starts_days_before = 'started_this_day';
			if( $session -> starts_days_before( $timestamp ) ) {
				$maybe_starts_days_before = 'starts_days_before';
			}

			$maybe_ends_days_ahead = 'ends_this_day';
			if( $session -> ends_days_ahead( $timestamp ) ) {
				$maybe_ends_days_ahead = 'ends_days_ahead';
			}			
			
			$details = $session -> get_details();

			$toggle = '';
			if( ! empty( $details ) ) {
				$toggle = "<a class='$shows_hides' href='#'>&#9661;</a>";
			}

			$permalink  = esc_url( get_edit_post_link() );
			$out .= "
				<li class='$show_hide $class-li $class-li-$maybe_starts_days_before $class-li-$maybe_ends_days_ahead'>
					<h4 class='$class-title'>
						<a href='$permalink'>$timeline</a>
						$toggle
					</h4>
					$details
				</li>
			";
		}

		wp_reset_postdata();

		$out = "<ul class='$class'>$out</ul>";

		return $out;

	}

}