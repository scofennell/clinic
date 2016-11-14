<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Sessions {

	function the_page() {

		echo $this -> get_page();

	}

	function get_page() {

		$out = '';

		$out = 'hello world';

		$out = "
			<div class='wrap'>
				$out
			</div>
		";

		return $out;

	}

	function the_calendar_page() {

		echo $this -> get_calendar_page();

	}

	function get_calendar_page() {

		$out = '';

		$out = $this -> get_calendar();

		$out = "
			<div class='wrap'>
				$out
			</div>
		";

		return $out;

	}	

	function get_calendar( $initial = true, $echo = true ) {
		
		global $wp_locale;
		global $wpdb;

		$thismonth = gmdate( 'm' );
		$thisyear  = gmdate( 'Y' );
		
		$unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
		$last_day = date( 't', $unixmonth );

		// week_begins = 0 stands for Sunday
		$week_begins = absint( get_option( 'start_of_week' ) );
		
		$ts = current_time( 'timestamp' );
	
		$myweek = array();

		for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
			$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
		}

		$head = '';

		foreach ( $myweek as $wd ) {
			$day_name = $initial ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
			$wd = esc_attr( $wd );
			$head .= "<th scope='col' title='$wd'>$day_name</th>";
		}

		$body = '';

		// See how much we should pad in the beginning
		$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
		if ( 0 != $pad ) {
			$body .= "<td colspan='$pad' class='pad'>&nbsp;</td>";
		}

		$newrow = false;
		$daysinmonth = (int) date( 't', $unixmonth );

		for ( $day = 1; $day <= $daysinmonth; ++$day ) {
			if ( isset($newrow) && $newrow ) {
				$body .= "</tr><tr>";
			}
			$newrow = false;

			if ( $day == gmdate( 'j', $ts ) &&
			
				$thismonth == gmdate( 'm', $ts ) &&
				$thisyear == gmdate( 'Y', $ts ) ) {
				$body .= '<td id="today">';
			
			} else {
			
				$body .= '<td>';
			}
			
			$body .= $day;

			$body .= $this -> get_for_day( $day, $thismonth, $thisyear );
			
			$body .= '</td>';

			if ( 6 == calendar_week_mod( date( 'w', mktime(0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
				$newrow = true;
			}
		
		}

		$pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins );
		if ( $pad != 0 && $pad != 7 ) {
			$body .= "<td class='pad' colspan='$pad'>&nbsp;</td>";
		}

		$out = "
			<table>
				<thead>
					<tr>
						$head
					</tr>
				<tbody>
					<tr>
						$body
					</tr>
				</tbody>
			</table>
		";

		return $out;

	}

	function get_for_day( $day, $month, $year ) {

		$start_of_day = strtotime( "$year-$month-$day" );
		$end_of_day   = $start_of_day + DAY_IN_SECONDS;		

		$args = array(
			'post_type'  => 'session',
			'meta_key'   => 'start',
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
			'meta_query' => array(
				array(
					'key'     => 'start',
					'value'   => array( $start_of_day, $end_of_day ),
					'compare' => 'BETWEEN',
				),
			),
		);

		$query = new WP_Query( $args );

		$found_posts = $query -> found_posts;
		if( empty( $found_posts ) ) { return FALSE; }

		$out = '';
		$posts = $query -> posts;

		foreach( $posts as $post ) {
			$post_title = wp_kses_post( $post -> post_title );
			$out .= "<li>$post_title</li>";
		}

		$out = "<ul>$out</ul>";


		return $out;

	}

}