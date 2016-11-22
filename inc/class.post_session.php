<?php

/**
 * 
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Session extends CLINIC_Post {

	function get_attendees() {

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$out = '';

		$clients = $this -> get_clients();
		$formatting = new CLINIC_Formatting( $clients );
		$clients_out = $formatting -> array_to_comma_sep( 'display_name' );

		$providers = $this -> get_providers();
		$formatting = new CLINIC_Formatting( $providers );
		$providers_out = $formatting -> array_to_comma_sep( 'display_name' );
	
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

	function get_start_time( $format = FALSE, $wrap = TRUE ) {

		if( ! $format ) { $format = get_option( 'time_format' ); }

		$start = absint( $this -> get_meta( 'start' ) );

		if( empty( $start ) ) { return FALSE; }

		$out = date( $format, $start );

		if( $wrap ) {

			$out = "<time>$out</time>";

		}

		return $out;

	}

	function get_end_time( $format = FALSE, $wrap = TRUE ) {

		if( ! $format ) { $format = get_option( 'time_format' ); }
		
		$end = absint( $this -> get_meta( 'end' ) );

		if( empty( $end ) ) { return FALSE; }

		$out = date( $format, $end );
		
		if( $wrap ) {

			$out = "<time>$out</time>";

		}

		return $out;

	}

	function get_start_date( $format = FALSE, $wrap = TRUE ) {

		if( ! $format ) { $format = get_option( 'date_format' ); }

		$start = absint( $this -> get_meta( 'start' ) );

		if( empty( $start ) ) { return FALSE; }

		$out = date( $format, $start );

		if( $wrap ) {

			$out = "<time>$out</time>";

		}

		return $out;

	}

	function get_end_date( $format = FALSE, $wrap = TRUE ) {

		if( ! $format ) { $format = get_option( 'date_format' ); }
		
		$end = absint( $this -> get_meta( 'end' ) );

		if( empty( $end ) ) { return FALSE; }

		$out = date( $format, $end );

		if( $wrap ) {

			$out = "<time>$out</time>";

		}

		return $out;

	}

	function get_start_datetime( $date_format = FALSE, $time_format = FALSE ) {

		if( ! $date_format ) { $date_format = get_option( 'date_format' ); }
		if( ! $time_format ) { $time_format = get_option( 'time_format' ); }
		$start = absint( $this -> get_meta( 'start' ) );
		if( empty( $start ) ) { return FALSE; }
		$out = date( "$date_format $time_format", $start );

		$out = "<time>$out</time>";

		return $out;

	}

	function get_end_datetime( $date_format = FALSE, $time_format = FALSE ) {

		if( ! $date_format ) { $date_format = get_option( 'date_format' ); }
		if( ! $time_format ) { $time_format = get_option( 'time_format' ); }
		$end = absint( $this -> get_meta( 'end' ) );
		if( empty( $end ) ) { return FALSE; }
		$out = date( "$date_format $time_format", $end );

		$out = "<time>$out</time>";

		return $out;

	}	

	function get_timeline( $timestamp = FALSE, $format = FALSE ) {

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		if( ! $format ) { $format = get_option( 'time_format' ); }

		$start_time = $this -> get_start_time( $format );
		$starts_days_before = $this -> starts_days_before( $timestamp );
		if( $timestamp ) {
			if( $starts_days_before ) {
				$start_time = "<span class='$class-left'>&larr;</span>";
			}
		}

		$end_time = $this -> get_end_time( $format );
		$ends_days_ahead = $this -> ends_days_ahead( $timestamp );
		if( $timestamp ) {
			if( $ends_days_ahead ) {
				$end_time = "<span class='$class-right'>&rarr;</span>";
			}
		}

		if( ! $starts_days_before && ! $ends_days_ahead ) {
			$out = sprintf( __( '%s - %s', 'clinic' ), $start_time, $end_time );
		} elseif( $starts_days_before || $ends_days_ahead ) {
			$out = "$start_time $end_time";
		} else {
			$out = $start_time;
		}

		return $out;

	}

	function get_clients() {

		$out = array();

		$ids = $this -> get_client_ids();

		if( is_array( $ids ) ) {

			foreach( $ids as $id ) {

				$out[ $id ] = get_user_by( 'ID', $id );

			}

		}

		return $out;

	}

	function get_providers() {

		$out = array();

		$ids = $this -> get_provider_ids();

		if( is_array( $ids ) ) {

			foreach( $ids as $id ) {

				$out[ $id ] = get_user_by( 'ID', $id );

			}

		}

		return $out;

	}

	function get_client_ids() {

		$ids = $this -> get_meta( 'client_ids', FALSE );

		return $ids;

	}

	function get_provider_ids() {

		$ids = $this -> get_meta( 'provider_ids', FALSE );	

		return $ids;

	}	

	function starts_days_before( $timestamp ) {

		$start_day_ts = $this -> get_start_day_timestamp();

		if( $start_day_ts < $timestamp ) { return TRUE; }

		return FALSE;

	}

	function ends_days_ahead( $timestamp ) {

		$end_day_ts = $this -> get_end_day_timestamp();

		if( $end_day_ts > $timestamp ) { return TRUE; }

		return FALSE;

	}


	function get_start_day() {

		$start = $this -> get_meta( 'start' );

		$out = date( 'Y-m-j', $start );

		return $out;

	}

	function get_end_day() {

		$end = $this -> get_meta( 'end' );

		$out = date( 'Y-m-j', $end );

		return $out;

	}

	function get_start_day_timestamp() {

		$start_day = $this -> get_start_day();

		$out = strtotime( $start_day );

		return $out;

	}

	function get_end_day_timestamp() {

		$end_day = $this -> get_end_day();

		$out = strtotime( $end_day );

		return $out;
	}	

	function get_details() {

		$details = '';

		$class = sanitize_html_class( __CLASS__ . '-' . __FUNCTION__ );

		$shown_hidden = sanitize_html_class( CLINIC . '-shown_hidden' );

		$clients   = $this -> get_meta_as_list( 'client_ids', 'display_name', 'get_userdata', 'get_edit_user_link' );
		if( ! empty( $clients ) ) {
			$client_label = "<span class='$class-meta_label'>" . esc_html__( 'Clients' ) . '</span>';
			$clients   = sprintf( esc_html__( '%s: %s', 'clinic' ), $client_label, $clients );
			$details .= "<li class='$class-detail'>$clients</li>";
		}

		$providers = $this -> get_meta_as_list( 'provider_ids', 'display_name', 'get_userdata', 'get_edit_user_link' );
		if( ! empty( $providers ) ) {
			$provider_label = "<span class='$class-meta_label'>" . esc_html__( 'Providers' ) . '</span>';
			$providers = sprintf( esc_html__( '%s: %s', 'clinic' ), $provider_label, $providers );
			$details .= "<li class='$class-detail'>$providers</li>";
		}

		$locations = $this -> get_meta_as_list( 'location_ids', 'post_title', 'get_post', 'get_edit_post_link' );
		if( ! empty( $locations ) ) {
			$location_label = "<span class='$class-meta_label'>" . esc_html__( 'Locations' ) . '</span>';
			$locations = sprintf( esc_html__( '%s: %s', 'clinic' ), $location_label, $locations );
			$details .= "<li class='$class-detail'>$locations</li>";
		}
		
		$services  = $this -> get_meta_as_list( 'service_ids', 'post_title', 'get_post', 'get_edit_post_link' );
		if( ! empty( $services ) ) {
			$services_label = "<span class='$class-meta_label'>" . esc_html__( 'Services' ) . '</span>';
			$services  = sprintf( esc_html__( '%s: %s', 'clinic' ), $services_label, $services );
			$details .= "<li class='$class-detail'>$services</li>";
		}

		if( ! empty( $details ) ) {
			$details = "<ul class='$class-details $shown_hidden'>$details</ul>";
		}

		return $details;

	}

	function get_keywords() {

		$out = '';

		$array = array();

		$array[]= $this -> get_start_date( 'F', FALSE );
		$array[]= $this -> get_meta_as_list( 'client_ids', 'display_name', 'get_userdata' );
		$array[]= $this -> get_meta_as_list( 'provider_ids', 'display_name', 'get_userdata' );
		$array[]= $this -> get_meta_as_list( 'location_ids', 'post_title', 'get_post' );
		$array[]= $this -> get_meta_as_list( 'service_ids', 'post_title', 'get_post' );	

		$out = implode( ' | ', $array );

		return $out;
		
	}

}