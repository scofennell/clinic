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

	function get_start_time( $format = FALSE ) {

		if( ! $format ) { $format = get_option( 'time_format' ); }

		$start = absint( $this -> get_meta( 'start' ) );

		if( empty( $start ) ) { return FALSE; }

		$out = date( $format, $start );

		$out = "<time>$out</time>";

		return $out;

	}

	function get_end_time( $format = FALSE ) {

		if( ! $format ) { $format = get_option( 'time_format' ); }
		
		$end = absint( $this -> get_meta( 'end' ) );

		if( empty( $end ) ) { return FALSE; }

		$out = date( $format, $end );

		$out = "<time>$out</time>";

		return $out;

	}

	function get_timeline( $format = FALSE ) {

		if( ! $format ) { $format = get_option( 'time_format' ); }

		$start_time = $this -> get_start_time( $format );
		$end_time   = $this -> get_end_time( $format );

		if( ! empty( $start_time ) && ! empty( $end_time ) ) {
			$out = sprintf( __( '%s - %s', 'clinic' ), $start_time, $end_time );
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

		$ids = $this -> get_meta( 'client_ids' );

		return $ids;

	}

	function get_provider_ids() {

		$ids = $this -> get_meta( 'provider_ids' );	

		return $ids;

	}	

}