<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

abstract class CLINIC_Posts {

	function __construct( $args = array() ) {

		$this -> set_post_type();
		$this -> set_post_type_object();
		$this -> set_args( $args );
		$this -> set_posts_per_page();
		
		$this -> set_start_year();
		$this -> set_start_month();
		$this -> set_start_day();
		$this -> set_start_datetime();
		$this -> set_start_timestamp();

		$this -> set_interval();

		$this -> set_end_timestamp();
		$this -> set_end_year();
		$this -> set_end_month();
		$this -> set_end_day();
		$this -> set_end_datetime();
	
	}

	function get_post_type() {

		return $this -> post_type;

	}

	function set_post_type_object() {

		$out = get_post_type_object( $this -> get_post_type() );

		//wp_die( var_dump( get_post_type_object( 'sessions' ) ) );

		$this -> post_type_object = $out;

	}

	function get_post_type_object() {
		return $this -> post_type_object;
	}

	function get_args() {

		return $this -> args;

	}

	function set_args( $args ) {

		$this -> args = $args;

	}

	function get_start_year() {

		return $this -> start_year;

	}

	function set_start_year() {

		if( isset( $this -> args['year'] ) ) {
			$this -> start_year = absint( $this -> args['year'] );
		} else {
			$this -> start_year = FALSE;
		}

	}

	function get_start_month() {

		return $this -> start_month;

	}


	function set_start_month() {

		if( isset( $this -> args['month'] ) ) {
			$this -> start_month = absint( $this -> args['month'] );
		} else {
			$this -> start_month = FALSE;
		}

	}

	function get_start_day() {

		return $this -> start_day;

	}


	function set_start_day() {

		if( isset( $this -> args['day'] ) ) {
			$this -> start_day = absint( $this -> args['day'] );
		} else {
			$this -> start_day = FALSE;
		}

	}	

	function get_end_year() {

		return $this -> end_year;

	}

	function set_end_year() {

		$end_timestamp = $this -> get_end_timestamp();

		$out = date( 'Y', $end_timestamp );

		$this -> end_year = $out;

	}

	function get_end_month() {

		return $this -> end_month;

	}


	function set_end_month() {

		$end_timestamp = $this -> get_end_timestamp();

		$out = date( 'm', $end_timestamp );

		$this -> end_month = $out;
	}

	function get_end_day() {

		return $this -> end_day;

	}


	function set_end_day() {

		$end_timestamp = $this -> get_end_timestamp();

		$out = date( 'j', $end_timestamp );

		$this -> end_day = $out;

	}	

	function get_end_timestamp() {

		return $this -> end_timestamp;

	}

	function set_end_timestamp() {

		$start_timestamp = $this -> get_start_timestamp();

		$interval = $this -> get_interval();

		$end_timestamp = $start_timestamp + $interval;

		$this -> end_timestamp = $end_timestamp;

	}

	function get_posts_per_page() {

		return $this -> posts_per_page;

	}

	function set_posts_per_page() {

		if( isset( $this -> args['posts_per_page'] ) ) {
			$this -> posts_per_page = absint( $this -> args['posts_per_page'] );
		} else {
			$this -> posts_per_page = FALSE;
		}

	}		

	function get() {
		return 'hello';
	}

	function get_as_kv() {
		
		$the_query = $this -> query();

		if( ! $the_query -> have_posts() ) { return FALSE; }

		$out = '';
		
		while( $the_query -> have_posts() ) {
			
			$the_query -> the_post();
			
			$out[ get_the_id() ] = get_the_title();
		
		}

		wp_reset_postdata();

		return $out;

	}

	function get_as_ul() {

		$the_query = $this -> query();

		if ( ! $the_query -> have_posts() ) { return FALSE; }


		$out = '';
		
		while( $the_query -> have_posts() ) {
			$the_query -> the_post();
			
			$post_title = get_the_title();
			
			$permalink  = esc_url( get_edit_post_link() );
			$out .= "<li><a href='$permalink'>$post_title</a></li>";
		}

		wp_reset_postdata();

		$out = "<ul>$out</ul>";

		return $out;

	}

	function get_start_datetime() {

		return $this -> start_datetime;

	}

	function set_start_datetime() {

		$out = '';

		$start_year = $this -> get_start_year();
		if( ! empty( $start_year ) ) { $out .= $start_year; }

		$start_month = $this -> get_start_month();
		if( ! empty( $start_month ) ) { $out .= "-$start_month"; }

		$start_day = $this -> get_start_day();			
		if( ! empty( $start_day ) ) { $out .= "-$start_day"; }

		$this -> start_datetime = $out;

	}

	function get_end_datetime() {

		return $this -> end_datetime;

	}

	function set_end_datetime() {

		$strtotime = $this -> get_end_timestamp();

		$out = date( 'Y-m-j', $strtotime );

		$this -> end_datetime = $out;

	}	

	function get_interval() {

		return $this -> interval;

	}

	function set_interval() {

		$interval = DAY_IN_SECONDS;

		$start_year  = $this -> get_start_year();
		$start_month = $this -> get_start_month();
		$start_day   = $this -> get_start_day();				

		if( ! empty( $start_day ) ) {

			$interval = DAY_IN_SECONDS;

		} elseif( ! empty( $start_month ) ) {

			$interval = MONTH_IN_SECONDS;	

		} elseif( ! empty( $start_year ) ) {

			$interval = YEAR_IN_SECONDS;	

		}

		$this -> interval = $interval;

	}

	function get_start_timestamp() {

		return $this -> start_timestamp;

	}

	function set_start_timestamp() {

		$this -> start_timestamp = strtotime( $this -> get_start_datetime() );

	}	

	function query() {

		$args = array(
			'post_type'      => $this -> get_post_type(),
			'posts_per_page' => $this -> get_posts_per_page(),
		);

		if( ! empty( $this -> get_start_datetime() ) ) {

			$start = $this -> get_start_timestamp();
			$end   = $this -> get_end_timestamp();

			$args['orderby']    = 'meta_value_num';
			$args['order']      = 'ASC';
			$args['meta_query'] = array(
				/*array(
					'key'     => CLINIC . '-' . 'start',
					'value'   => array( absint( $start_of_day ), absint( $end_of_day ) ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				),*/
				array(
					'key'     => CLINIC . '-' . 'start',
					'value'   => absint( $end ),
					'type'    => 'NUMERIC',
					'compare' => '<',
				),
				array(
					'key'     => CLINIC . '-' . 'end',
					'value'   => absint( $start ),
					'type'    => 'NUMERIC',
					'compare' => '>',
				),
			);

		}

		$out = new WP_Query( $args );

		/*if( $this -> day == 20 ) {
			wp_die(
				var_dump(

					$args,
					$out

				)
			);
		}*/

		return $out;

	}	

}