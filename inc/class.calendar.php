<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Calendar {

	function __construct( $view, $year, $month, $week, $day ) {

		wp_die( 'theres no reason the whole calendar constructor should be called on every page load.' );

		$sessions = new CLINIC_Sessions();
		$obj = $sessions -> get_post_type_object();
		$this -> session_labels = $obj -> labels;

		$this -> set_page();
		$this -> set_post_type();		

		$date_format = $this -> set_date_format();

		$this -> set_week_begins();
		$this -> set_view( $view );
		$this -> set_year( $year );
		$this -> set_week( $week );
		$this -> set_month( $month );
		$this -> set_day( $day );
		$this -> set_timestamp();
		$this -> set_datetime();

		$this -> set_start_of_week_ts();
		$this -> set_end_of_week_ts();
		$this -> set_start_of_week();
		$this -> set_end_of_week();		

		$this -> set_page_title();
		$this -> set_page_subtitle();

	}

	function get_page() {
		return $this -> page;
	}

	function set_page() {
		$out = FALSE;
		if( isset( $_GET['page'] ) ) {
			$out = $_GET['page'];
		}
		$this -> page = $out;
	}

	function get_post_type() {
		return $this -> post_type;
	}

	function set_post_type() {
		$this -> post_type = $_GET['post_type'];
	}

	function get_date_format() {
		return $this -> date_format;
	}

	function set_date_format() {
		$this -> date_format = get_option( 'date_format' );
	}

	function get_datetime() {
		return clone $this -> datetime;
	}

	function set_datetime() {
		$this -> datetime = DateTime::createFromFormat( 'U', $this -> get_timestamp() );
	}

	function set_week_begins() {
		$this -> week_begins = absint( get_option( 'start_of_week' ) );
	}

	function get_week_begins() {

		return $this -> week_begins;

	}

	function set_view( $view ) {

		if( empty( $view ) ) { $view = 'month'; }

		$view = sanitize_text_field( $view );
		$this -> view = $view;

	}

	function get_view() {
		return $this -> view;
	}

	function set_year( $year ) {

		if( empty( $year ) ) {
			$year = date( 'Y' );
		}

		$year = absint( $year );
		$this -> year = $year;

	}

	function get_year() {
		return $this -> year;
	}

	function set_month( $month ) {

		if( empty( $month ) ) {

			$week = $this -> get_week();
			if( ! empty( $week ) ) {

				$year = $this -> get_year();

				$month = date( 'm', strtotime( $year . "W" . $week ) );

			} else {

				$month = date( 'm' );

			}
		
		}

		$month = absint( $month );
		$this -> month = $month;

	}

	function get_month() {
		return $this -> month;
	}

	function set_week( $week ) {

		if( empty( $week ) ) {
			$week = date( 'W' );
		}

		if( strlen( $week ) == 1 ) {
			$week = '0' . $week;
		}
		$this -> week = $week;

	}

	function get_week() {
		return $this -> week;
	}

	function set_day( $day ) {

		if( empty( $day ) ) {

			$week = $this -> get_week();
			if( ! empty( $week ) ) {

				$year = $this -> get_year();

				$day = date( 'd', strtotime( $year . "W" . $week ) );

			} else {

				$day = date( 'j' );

			}
		
		}

		$day = absint( $day );
		$this -> day = $day;

	}

	function get_day() {
		return $this -> day;
	}

	function set_timestamp() {

		$year  = $this -> get_year();
		$month = $this -> get_month();
		$week  = $this -> get_week();
		$day   = $this -> get_day();

		$timestamp = strtotime( "$year-$month-$day" );

		$this -> timestamp = $timestamp;

	}	

	function get_timestamp() {

		return $this -> timestamp;

	}

	function set_start_of_week_ts() {

		$year = $this -> get_year();

		$week = $this -> get_week();

		$week_begins = $this -> get_week_begins();

		$date = $year . 'W' . $week . '-' . $week_begins;

	
		$this -> start_of_week_ts = strtotime( $date );


	}

	function get_start_of_week_ts() {
		
		return $this -> start_of_week_ts;

	}

	function set_end_of_week_ts() {

		$year = $this -> get_year();
		$week = $this -> get_week();		

		$this -> end_of_week_ts = strtotime( $year . 'W' . $week ) + WEEK_IN_SECONDS - 1;

	}

	function get_end_of_week_ts() {
		
		return $this -> end_of_week_ts;

	}	

	function set_start_of_week() {

		$start_of_week_ts = $this -> get_start_of_week_ts();

		$this -> start_of_week = date( $this -> get_date_format() , $start_of_week_ts );

	}

	function get_start_of_week() {
		
		return $this -> start_of_week;

	}

	function set_end_of_week() {

		$end_of_week_ts = $this -> get_end_of_week_ts();

		$this -> end_of_week = date( $this -> get_date_format(), $end_of_week_ts );

	}

	function get_end_of_week() {
		
		return $this -> end_of_week;

	}	


	function set_page_title() {

		$ts = $this -> get_timestamp();

		$view = $this -> get_view();

		if( $view == 'month' ) {

			$title = sprintf( esc_html__( '%s', 'clinic' ), date( 'F, Y', $ts ) );

			$year_of_next_month = $this -> get_year_of_next_month();
			$month_of_next_month = $this -> get_month_of_next_month();
			$next_month_href = $this -> get_month_href( $year_of_next_month, $month_of_next_month );
			$next_month_link = $this -> get_arrow_link( $next_month_href, 'next' );
			
			$year_of_prev_month = $this -> get_year_of_prev_month();
			$month_of_prev_month = $this -> get_month_of_prev_month();
			$prev_month_href = $this -> get_month_href( $year_of_prev_month, $month_of_prev_month );
			$prev_month_link = $this -> get_arrow_link( $prev_month_href, 'prev' );

			$this -> page_title = $prev_month_link . $title . $next_month_link;

		} elseif( $view == 'week' ) {

			$start_of_week = $this -> get_start_of_week();
			$end_of_week = $this -> get_end_of_week();			
			$title = sprintf( esc_html__( 'Week %d (%s - %s)', 'clinic' ), $this -> get_week(), $start_of_week, $end_of_week );

			$year_of_next_week = $this -> get_year_of_next_week();
			$week_of_next_week = $this -> get_week_of_next_week();
			$next_week_href = $this -> get_week_href( $year_of_next_week, $week_of_next_week );
			$next_week_link = $this -> get_arrow_link( $next_week_href, 'next' );
			
			$year_of_prev_week = $this -> get_year_of_prev_week();
			$week_of_prev_week = $this -> get_week_of_prev_week();
			$prev_week_href = $this -> get_week_href( $year_of_prev_week, $week_of_prev_week );
			$prev_week_link = $this -> get_arrow_link( $prev_week_href, 'prev' );

			$this -> page_title = $prev_week_link . $title . $next_week_link;

		} elseif( $view == 'day' ) {

			$title = sprintf( esc_html__( '%s', 'clinic' ), date( $this -> get_date_format(), $ts ) );

			$year_of_next_day  = $this -> get_year_of_next_day();
			$month_of_next_day = $this -> get_month_of_next_day();
			$day_of_next_day   = $this -> get_day_of_next_day();
			$next_day_href     = $this -> get_day_href( $year_of_next_day, $month_of_next_day, $day_of_next_day );
			$next_day_link     = $this -> get_arrow_link( $next_day_href, 'next' );
			
			$year_of_prev_day  = $this -> get_year_of_prev_day();
			$month_of_prev_day = $this -> get_month_of_prev_day();
			$day_of_prev_day   = $this -> get_day_of_prev_day();
			$prev_day_href     = $this -> get_day_href( $year_of_prev_day, $month_of_prev_day, $day_of_prev_day );
			$prev_day_link     = $this -> get_arrow_link( $prev_day_href, 'prev' );

			$this -> page_title = $prev_day_link . $title . $next_day_link;

		} else {

			$this -> page_title = esc_html__( 'Calendar', 'clinic' );

		}

	}		

	function get_page_title() {

		return $this -> page_title;

	}

	function set_page_subtitle() {

		$view = $this -> get_view();

		$out = '';

		$count = $this -> get_count_for_month();

		$single = $this -> session_labels -> singular_name;
		$plural = $this -> session_labels -> name;
		$sessions_label = _n(
			$single,
			$plural,
			$count,
			'clinic'
		);

		$out = sprintf( esc_html__( '%d %s', 'clinic' ), $count, $sessions_label );

		$this -> page_subtitle = $out;

	}		

	function get_page_subtitle() {

		return $this -> page_subtitle;

	}	

	function the_page() {

		echo $this -> get_page_content();

	}

	function get_page_content() {

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$title    = "<h1 class='wp-heading-inline $class-title'>" . $this -> get_page_title() . '</h1>';
		$subtitle = "<p class='$class-subtitle'>" . $this -> get_page_subtitle() . '</p>';

		$view = $this -> get_view();

		if( $view == 'month' ) {

			$content = $this -> get_month_content();

		} elseif( $view == 'week' ) {

			$content = $this -> get_week_content();

		} elseif( $view == 'day' ) {

			$content = $this -> get_day_content();

		}

		$navigation = $this -> get_navigation();

		$hr = '<hr class="wp-header-end">';

		$out = "
			<div class='wrap $class $class-$view'>
				$title
				$subtitle
				$hr
				<div class='$class-body'>
					$content
				</div>
				$navigation
			</div>
		";

		return $out;

	}

	function get_month_content( $initial = true, $echo = true ) {
		
		$class = sanitize_html_class( CLINIC . '-' . __FUNCTION__ );

		global $wp_locale;
		global $wpdb;

		$thismonth = $this -> get_month();
		$thisyear  = $this -> get_year();
		
		$unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
		$last_day = date( 't', $unixmonth );

		// week_begins = 0 stands for Sunday
		$week_begins = $this -> get_week_begins();
		
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

			$body .= $this -> get_for_day( $day, $thismonth, $thisyear, 'compact' );
			
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

	function get_week_content() {
		
		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$out = '';

		$start_of_week = $this -> get_start_of_week_ts();

		for ( $i = 0; $i <= 6; ++$i ) {

			$ts = $start_of_week + ( DAY_IN_SECONDS * $i );

			$day   = date( 'j', $ts );
			$month = date( 'm', $ts );
			$year  = date( 'Y', $ts );						

			$day_ts = strtotime( "$year-$month-$day" );

			$date = date( $this -> get_date_format(), $day_ts );

			$sessions = $this -> get_for_day( $day, $month, $year );

			if( empty( $sessions ) ) {
				$sessions = esc_html__( '(None)', 'clinic' );
			}

			$day_href = $this -> get_day_href( $year, $month, $day );

			$out .= "
				<li class='$class-li'>
					<h4 class='$class-title'><a href='$day_href'>$date</a></h4>
					$sessions
				</li>
			";


		}

		if( ! empty( $out ) ) {
			$out = "<ul class='$class'>$out</ul>";
		}

		return $out;

	}

	function get_day_content() {
		
		$out = $this -> get_for_day();

		return $out;

	}

	function get_navigation() {

		$name  = $this -> session_labels -> name;
		$title = sprintf( esc_html__( 'Which %s?', 'clinic' ), $name );

		$which_year      = $this -> get_year_nav();
		$which_month     = $this -> get_month_nav();
		$which_providers = $this -> get_provider_nav();
		$which_clients   = $this -> get_client_nav();
		$which_locations = $this -> get_location_nav();
		$which_services  = $this -> get_service_nav();

		$class = __CLASS__ . '-' . __FUNCTION__;

		$submit = get_submit_button( esc_html__( 'Go', 'clinic' ), 'primary', 'submit', FALSE );
		$submit = "
			<div class='$class-submit'>$submit</div>
		";
		
		$action = $this -> get_base_href();

		$post_type = $this -> get_post_type();
		$page      = $this -> get_page();

		$hidden = "
			<input name='post_type' value='$post_type' type='hidden'>
			<input name='page'      value='$page'      type='hidden'>			
		";

		$out = "
			<form action='$action' method='GET' class='$class'>
				$hidden
				<h4 class='$class-title'>$title</h4>
				<fieldset class='$class-fieldset'>
					$which_year
					$which_month
					$which_providers
					$which_clients
					$which_locations
					$which_services				
				</fieldset>
				$submit	
			</form>
		";

		return $out;

	}

	function get_nav_control( $content, $slug, $label ) {

		$class = __CLASS__ . '-' . __FUNCTION__;

		$out = "
			<div class='$class'>
				<label class='$class-label' for='$slug'>$label</label>
				$content
			</div>
		";

		return $out;

	}

	function get_years_of_sessions() {
		



	}

	function get_year_nav() {

		$current = $this -> get_year();

		$slug = 'year';

		$content = "
			<input type='number' value='$current' min='0' max='9999' name='$slug'>
		";

		$label = esc_html__( 'Year', 'clinic' );

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}

	function get_month_nav() {
		
		$current = $this -> get_month();

		$slug = 'month';

		$i = 0;
		$end   = 12;
		while ( $i < $end ) {
			$i++;
			$date = DateTime::createFromFormat( '!m', $i );
			$m    = $date -> format( 'F' );
			$options_arr[ $i ] = $m;
		}

		$formatting = new CLINIC_Formatting( $options_arr );
		$options = $formatting -> array_to_options( $current );

		$content = "
			<select id='$slug' name='$slug'>
				$options
			</select>
		";
		$label = esc_html__( 'Month', 'clinic' );

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}

	function get_provider_nav() {

		$slug = 'provider';
		$label = esc_html__( 'Provider', 'clinic' );

		$autosuggest = new CLINIC_Autocomplete( '/', '' );
		$content = $autosuggest -> get();

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}

	function get_client_nav() {

		$content = 'hello';
		$slug = 'client';
		$label = esc_html__( 'Client', 'clinic' );

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}	

	function get_location_nav() {

		$content = 'hello';
		$slug = 'location';
		$label = esc_html__( 'Location', 'clinic' );

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}		

	function get_service_nav() {

		$content = 'hello';
		$slug = 'service';
		$label = esc_html__( 'Service', 'clinic' );

		$out = $this -> get_nav_control( $content, $slug, $label );
		return $out;

	}			



	function get_for_day( $day = FALSE, $month = FALSE, $year = FALSE, $format = 'verbose' ) {	

		if( empty( $year ) )  { $year  = $this -> year; }
		if( empty( $month ) ) { $month = $this -> month; }
		if( empty( $day ) )   { $day   = $this -> day; }

		$posts_per_page = 5;

		$args = array(
			'day'            => $day,
			'month'          => $month,
			'year'           => $year,
			'posts_per_page' => $posts_per_page,
		);
		$sessions = new CLINIC_Sessions( $args );

		$out = $sessions -> get_as_ul( $format );
		
		if( $format == 'compact' ) {
			$the_query = $sessions -> query();
			$found_posts = $the_query -> found_posts;
			if( $found_posts > $posts_per_page ) {
				$href = $this -> get_day_href( $year, $month, $day );
				$sessions_label = strtolower( $this -> session_labels -> name );
				$view_all = sprintf( esc_html__( '&hellip; view all %d %s', 'clinic' ), $found_posts, $sessions_label );
				$out .= "<div><a href='$href'><i>$view_all</i></a></div>";
			}
		}

		return $out;

	}

	function get_base_href() {
		
		$out = admin_url( '/edit.php' );
		$out = add_query_arg( array( 'post_type' => 'session' ), $out );
		$out = add_query_arg( array( 'page' => 'calendar' ), $out );

		return $out;

	}

	function get_month_href( $year, $month ) {

		$out = $this -> get_base_href();
		$out = add_query_arg( array( 'view' => 'month' ), $out );
		$out = add_query_arg( array( 'year' => $year ), $out );
		$out = add_query_arg( array( 'month' => $month ), $out );

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

	function get_count_for_month() {

		$month = $this -> month;
		$year  = $this -> year;		

		$args = array(
			'month'          => $month,
			'year'           => $year,
		);
		$sessions = new CLINIC_Sessions( $args );
		$the_query = $sessions -> query();
		return absint( $the_query -> found_posts );

	}

	function get_arrow_link( $href, $direction ) {

			$arrow_class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

			if( $direction == 'next' ) {
				
				$right_or_left = 'right';

			} elseif( $direction == 'prev' ) {
		
				$right_or_left = 'left';
		
			}

			$out = "<a class='$arrow_class $arrow_class-$direction' href='$href'><span class='dashicons dashicons-arrow-$right_or_left'></span></a>";

			return $out;

	}
	
	function  get_year_of_next_month() {

		$datetime = $this -> get_datetime();
		$datetime = $datetime -> modify( "+1 month" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}

	function  get_month_of_next_month() {

		$datetime = $this -> get_datetime();
		$datetime = $datetime -> modify( "+1 month" );
		$out = $datetime -> format( 'm' );
		return $out;

	}
	
	function  get_year_of_prev_month() {

		$datetime = $this -> get_datetime();
		$datetime -> modify( "-1 month" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}
	
	function  get_month_of_prev_month() {

		$datetime = $this -> get_datetime();
		$datetime -> modify( "-1 month" );
		$out = $datetime -> format( 'm' );
		return $out;


	}

	function  get_year_of_next_week() {

		$datetime = $this -> get_datetime();
		$datetime = $datetime -> modify( "+1 week" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}

	function  get_week_of_next_week() {

		$datetime = $this -> get_datetime();
		$datetime = $datetime -> modify( "+1 week" );
		$out = $datetime -> format( 'W' );
		return $out;

	}
	
	function  get_year_of_prev_week() {

		$datetime = $this -> get_datetime();

		$datetime -> modify( "-1 week" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}
	
	function  get_week_of_prev_week() {

		$week = $this -> get_week();
		if( $week == 1 ) {
			$out = 52;
		} else {
			$datetime = $this -> get_datetime();
			$datetime -> modify( "-1 week" );
			$out = $datetime -> format( 'W' );
		}

		return $out;


	}

	function get_year_of_next_day() {

		$datetime = $this -> get_datetime();
		$datetime -> modify( "+1 day" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}

	function get_month_of_next_day() {
		$datetime = $this -> get_datetime();
		$datetime -> modify( "+1 day" );
		$out = $datetime -> format( 'm' );
		return $out;
		
	}	

	function get_day_of_next_day() {
		$datetime = $this -> get_datetime();
		$datetime -> modify( "+1 day" );
		$out = $datetime -> format( 'j' );
		return $out;
		
	}

	function get_year_of_prev_day() {
		$datetime = $this -> get_datetime();
		$datetime -> modify( "-1 day" );
		$out = $datetime -> format( 'Y' );
		return $out;

	}

	function get_month_of_prev_day() {
		$datetime = $this -> get_datetime();
		$datetime -> modify( "-1 day" );
		$out = $datetime -> format( 'm' );
		return $out;
		
	}	

	function get_day_of_prev_day() {
		$datetime = $this -> get_datetime();
		$datetime -> modify( "-1 day" );
		$out = $datetime -> format( 'j' );
		return $out;
		
	}	

}