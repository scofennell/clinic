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
		$this -> set_args( $args );
		$this -> set_year();
		$this -> set_month();
		$this -> set_day();
		$this -> set_posts_per_page();
		$this -> set_datetime();
		$this -> set_timestamp();
	
	}

	function get_post_type() {

		return $this -> post_type;

	}

	function get_args() {

		return $this -> args;

	}

	function set_args( $args ) {

		$this -> args = $args;

	}

	function get_year() {

		return $this -> year;

	}

	function set_year() {

		if( isset( $this -> args['year'] ) ) {
			$this -> year = absint( $this -> args['year'] );
		} else {
			$this -> year = FALSE;
		}

	}

	function get_month() {

		return $this -> month;

	}


	function set_month() {

		if( isset( $this -> args['month'] ) ) {
			$this -> month = absint( $this -> args['month'] );
		} else {
			$this -> month = FALSE;
		}

	}

	function get_day() {

		return $this -> day;

	}


	function set_day() {

		if( isset( $this -> args['day'] ) ) {
			$this -> day = absint( $this -> args['day'] );
		} else {
			$this -> day = FALSE;
		}

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

	function get_datetime() {

		return $this -> datetime;

	}

	function set_datetime() {

		$flag = TRUE;

		$year = $this -> get_year();
		if( empty( $year ) ) { $flag = FALSE; }

		$month = $this -> get_month();
		if( empty( $month ) ) { $flag = FALSE; }

		$day = $this -> get_day();			
		if( empty( $day ) ) { $flag = FALSE; }

		if( $flag ) {
			$this -> datetime = "$year-$month-$day";
		} else {
			$this -> datetime = FALSE;	
		}

	}

	function get_timestamp() {

		return $this -> timestamp;

	}

	function set_timestamp() {

		$this -> timestamp = strtotime( $this -> get_datetime() );

	}	

	function query() {

		$args = array(
			'post_type'      => $this -> get_post_type(),
			'posts_per_page' => $this -> get_posts_per_page(),
		);
		
		if( ! empty( $this -> get_datetime() ) ) {

			$start_of_day = $this -> get_timestamp();
			$end_of_day   = $start_of_day + DAY_IN_SECONDS;	

			$args['meta_key']   = 'start';
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
					'value'   => absint( $end_of_day ),
					'type'    => 'NUMERIC',
					'compare' => '<',
				),
				array(
					'key'     => CLINIC . '-' . 'end',
					'value'   => absint( $start_of_day ),
					'type'    => 'NUMERIC',
					'compare' => '>',
				),
			);
		
		}

		return new WP_Query( $args );

	}	

}