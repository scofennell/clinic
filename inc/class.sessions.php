<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Sessions {

	function __construct() {

	}

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

		if ( ! $query -> have_posts() ) { return FALSE; }
	
		$out = '';
		
		while ( $query -> have_posts() ) {
			$query->the_post();
			
			$post_title = get_the_title();
			if( empty( $post_title ) ) {
				$post_title = $this -> get_attendees( get_the_ID() );
			}
			$permalink  = esc_url( get_permalink() );
			$out .= "<li><a href='$permalink'>$post_title</a></li>";
		}

		$out = "<ul>$out</ul>";


		return $out;

	}

	function get_attendees( $post_id ) {

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$out = '';

		$clients_out = '';
		$clients = $this -> get_session_clients( $post_id );
		if( is_array( $clients ) ) {

			$count = count( $clients );
			$i = 0;
			foreach( $clients as $client_id => $client ) {

				$i++;
				$clients_out .= $client -> display_name;
				if( $i < ( $count - 1 ) ) {
					$clients_out .= esc_html__( ', ', 'clinic' );
				} elseif( $i < $count ) {
					$clients_out .= esc_html__( ' and ', 'clinic' );	
				}

			}

		}

		$providers_out = '';
		$providers = $this -> get_session_providers( $post_id );
		if( is_array( $providers ) ) {
			
			$count = count( $providers );
			$i = 0;
			foreach( $providers as $provider_id => $provider ) {
				$i++;
				$providers_out .= $provider -> display_name;
				if( $i < ( $count - 1 ) ) {
					$providers_out .= esc_html__( ', ', 'clinic' );
				} elseif( $i < $count ) {
					$providers_out .= esc_html__( ' and ', 'clinic' );	
				}

			}

		}		

		if( ! empty( $providers_out ) && ! ( empty( $clients_out ) ) ) {
			$out = sprintf( esc_html__( '%s | %s', 'clinic' ), $clients_out, $providers_out );
		} else {
			$out = $providers_out.$clients_out;
		}

		if( ! empty( $out ) ) {
			$out = "<div class='$class'>$out</div>";
		}

		return $out;

	}

	function get_session_clients( $post_id ) {

		$out = array();

		$ids = $this -> get_session_client_ids( $post_id );

		if( is_array( $ids ) ) {

			foreach( $ids as $id ) {

				$out[ $id ] = get_user_by( 'ID', $id );

			}

		}

		return $out;

	}

	function get_session_providers( $post_id ) {

		$out = array();

		$ids = $this -> get_session_provider_ids( $post_id );

		if( is_array( $ids ) ) {

			foreach( $ids as $id ) {

				$out[ $id ] = get_user_by( 'ID', $id );

			}

		}

		return $out;

	}

	function get_session_client_ids( $post_id ) {

		$ids = get_post_meta( $post_id, 'client_ids', TRUE );

		return $ids;

	}

	function get_session_provider_ids( $post_id ) {

		$ids = get_post_meta( $post_id, 'provider_ids', TRUE );		

		return $ids;

	}	

}