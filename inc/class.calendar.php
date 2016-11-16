<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Calendar {

	function __construct() {

	}

	function the_page() {

		echo $this -> get_page();

	}

	function get_page() {

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
		
		$class = sanitize_html_class( CLINIC . '-' . __FUNCTION__ );

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
			$head .= "<th scope='col' title='$wd' class='$class-th'>$day_name</th>";
		}

		$body = '';

		// See how much we should pad in the beginning
		$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
		if ( 0 != $pad ) {
			$body .= "<td colspan='$pad' class='$class-td $class-td-pad'>&nbsp;</td>";
		}

		$newrow = false;
		$daysinmonth = (int) date( 't', $unixmonth );

		for ( $day = 1; $day <= $daysinmonth; ++$day ) {
			if ( isset($newrow) && $newrow ) {
				$body .= "</tr><tr class='$class-tr'>";
			}
			$newrow = false;

			if ( $day == gmdate( 'j', $ts ) &&
			
				$thismonth == gmdate( 'm', $ts ) &&
				$thisyear == gmdate( 'Y', $ts ) ) {
				$body .= "<td class='$class-td $class-td-day $class-td-today'>";
			
			} else {
			
				$body .= "<td class='$class-td $class-td-day'>";
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
			$body .= "<td class='$class-td-pad $class-td' colspan='$pad'>&nbsp;</td>";
		}

		$out = "
			<table class='$class'>
				<thead class='$class-thead'>
					<tr>
						$head
					</tr>
				<tbody class='$class-tbody'>
					<tr class='$class-tr'>
						$body
					</tr>
				</tbody>
			</table>
		";

		return $out;

	}

	function get_for_day( $day, $month, $year ) {	

		$posts_per_page = 5;

		$args = array(
			'day'            => $day,
			'month'          => $month,
			'year'           => $year,
			'posts_per_page' => $posts_per_page,
		);
		$sessions = new CLINIC_Sessions( $args );

		$out = $sessions -> get_as_ul();
		
		$the_query = $sessions -> query();
		$found_posts = $the_query -> found_posts;
		if( $found_posts > $posts_per_page ) {
			$href = '';
			$view_all = sprintf( esc_html__( '&hellip; view all %d sessions', 'clinic' ), $found_posts );
			$out .= "<div><a href='$href'><i>$view_all</i></a></div>";
		}

		return $out;

	}

}