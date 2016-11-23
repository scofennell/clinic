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

		$this -> set_view();

	}

	function set_view() {

		$view = 'month';

		if( isset( $_GET['view'] ) ) {

			if( ! empty( $_GET['view'] ) ) {

				$view = sanitize_text_field( $_GET['view'] );

			}

		}

		$this -> view = $view;

	}

	function the_page() {

		echo $this -> get_page();

	}

	function get_page() {

		if( $this -> view == 'day' ) {

			$out = $this -> get_day();

		} elseif( $this -> view == 'week' ) {

			$out = $this -> get_week();

		} else {

			$out = $this -> get_month();

		}

		$out = "
			<div class='wrap'>
				$out
			</div>
		";

		return $out;

	}	

	function get_day() {

		return 'hello day';

	}

	function get_week() {

		return 'hello week';

	}	

	function get_month( $initial = true, $echo = true ) {
		
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

		$week_label = esc_html__( 'Wk.', 'clinic' );
		$head = "<th class='$class-th-week'>$week_label</th>";

		foreach ( $myweek as $wd ) {
			$day_name = $initial ? $wp_locale -> get_weekday_initial( $wd ) : $wp_locale -> get_weekday_abbrev( $wd );
			$wd = esc_attr( $wd );
			$head .= "<th scope='col' title='$wd' class='$class-th'>$day_name</th>";
		}

		$week_ts = strtotime( "$thisyear-$thismonth-1" );
		$week_number = date( 'W', $week_ts );
		$week_href = $this -> get_week_href( $thisyear, $week_number );
	
		$body = "<td class='$class-td-week'><a href='$week_href'>$week_number</td>";	

		// See how much we should pad in the beginning
		$pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
		if ( 0 != $pad ) {
			$body .= "<td colspan='$pad' class='$class-td $class-td-pad'>&nbsp;</td>";
		}

		$newrow = false;
		$daysinmonth = (int) date( 't', $unixmonth );

		for ( $day = 1; $day <= $daysinmonth; ++$day ) {

			$day_ts = strtotime( "$thisyear-$thismonth-$day" );


			if ( isset($newrow) && $newrow ) {

				$week_number = date( 'W', $day_ts );
				$week_href = $this -> get_week_href( $thisyear, $week_number );


				$body .= "</tr><tr class='$class-tr'>";
				$body .= "<td class='$class-td-week'><a href='$week_href'>$week_number</a></td>";	
			}
			$newrow = false;

			if ( $day == gmdate( 'j', $ts ) &&
			
				$thismonth == gmdate( 'm', $ts ) &&
				$thisyear == gmdate( 'Y', $ts ) ) {
				$body .= "<td class='$class-td $class-td-day $class-td-today'>";
			
			} else {
			
				$body .= "<td class='$class-td $class-td-day'>";
			}
			
			$day_href = $this -> get_day_href( $thisyear, $thismonth, $day );
			$body .= "<a class='$class-date' href='$day_href'>$day</a>";

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

		$navigation = $this -> get_navigation();

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
			$navigation
		";

		return $out;

	}

	function get_navigation() {

		$title = esc_html__( 'Which Sessions?', 'clinic' );

		$which_year      = $this -> get_year_nav();
		$which_month     = $this -> get_month_nav();
		$which_providers = $this -> get_provider_nav();
		$which_clients   = $this -> get_client_nav();
		$which_locations = $this -> get_location_nav();
		$which_services  = $this -> get_service_nav();

		$submit = get_submit_button( esc_html__( 'Go', 'clinic' ) );
		
		$out = "
			<form>
				<h4>$title</h4>
				$which_year
				$which_month
				$which_providers
				$which_clients
				$which_locations
				$which_services				
				$submit	
			</form>
		";

		return $out;

	}

	function get_year_nav() {

		$out = 'hello';
		return $out;

	}

	function get_month_nav() {

		$out = 'hello';
		return $out;

	}

	function get_provider_nav() {

		$out = 'hello';
		return $out;

	}

	function get_client_nav() {

		$out = 'hello';
		return $out;

	}	

	function get_location_nav() {

		$out = 'hello';
		return $out;

	}		

	function get_service_nav() {

		$out = 'hello';
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

	function get_base_href() {
		
		$out = admin_url( '/edit.php' );
		$out = add_query_arg( array( 'post_type' => 'session' ), $out );
		$out = add_query_arg( array( 'page' => 'calendar' ), $out );

		return $out;

	}

	function get_week_href( $year, $week ) {

		$out = $this -> get_base_href();
		$out = add_query_arg( array( 'view' => 'week' ), $out );
		$out = add_query_arg( array( 'year' => $year ), $out );
		$out = add_query_arg( array( 'week' => $week ), $out );

		return $out;

	}

	function get_day_href( $year, $month, $day ) {

		$out = $this -> get_base_href();
		$out = add_query_arg( array( 'view'  => 'day' ), $out );
		$out = add_query_arg( array( 'year'  => $year ), $out );
		$out = add_query_arg( array( 'month' => $month ), $out );
		$out = add_query_arg( array( 'day'   => $day ), $out );

		return $out;

	}



}