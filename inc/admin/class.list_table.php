<?php

/**
 *
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_list_table_init() {

	new CLINIC_List_Table;

}
add_action( 'current_screen', 'clinic_list_table_init', 110 ); 

class CLINIC_List_Table {

	function __construct() {

		$this -> meta = new CLINIC_Meta;

		$this -> set_provider_id();
		$this -> set_client_id();		
		$this -> set_start();
		$this -> set_end();

		add_action( 'admin_head', array( $this, 'remove_date_drop' ) );

		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );

		add_filter( 'parse_query', array( $this, 'posts_filter' ) );

    	add_filter('bulk_actions-session', array( $this, 'remove_quick_edit' ) );


	}

	
	function remove_quick_edit( $actions ) {

		unset( $actions['inline'] );
		return $actions;
	
	}

	function set_provider_id() {

		$provider_id = FALSE;

		if( isset( $_GET[ CLINIC . '-provider_id'] ) ) {

			$provider_id = absint( $_GET[ CLINIC . '-provider_id'] );

		}

		$this -> provider_id = $provider_id;

	}

	function set_client_id() {

		$client_id = FALSE;

		if( isset( $_GET[ CLINIC . '-client_id'] ) ) {

			$client_id = absint( $_GET[ CLINIC . '-client_id'] );

		}

		$this -> client_id = $client_id;

	}	

	function set_start() {

		$start = FALSE;

		if( isset(  $_GET[ CLINIC . '-start' ] ) ) {

			$start = absint(  $_GET[ CLINIC . '-start' ] );

		}

		$this -> start = $start;

	}

	function set_end() {

		$end = FALSE;

		if( isset(  $_GET[ CLINIC . '-end'] ) ) {

			$end = absint( $_GET[ CLINIC . '-end' ] );

		}

		$this -> end = $end;

	}	

	function remove_date_drop() {

		if( ! $this -> meta -> get_is_archive_session_admin() ) { return FALSE; }


    	add_filter( 'months_dropdown_results', array( $this, 'months_dropdown_results' ) );

	}

	function months_dropdown_results( $in ) {

		if( ! $this -> meta -> get_is_archive_session_admin() ) { return $in; }

		return array();

	}


	function restrict_manage_posts() {

		if( ! $this -> meta -> get_is_archive_session_admin() ) { return FALSE; }

		$out = '';

		$providers_out = '';
		$providers = new CLINIC_Providers;
		$role_label = $providers -> get_role_label();
		$default  = sprintf( esc_html__( 'Select a %s', 'clinic' ), $role_label );
		$providers_out  .= "<option value=''>$default</option>";
		$providers_out .= $providers -> get_as_options( $this -> provider_id );
		$name = CLINIC . '-provider_id';
		$providers_out = "<select name='$name'>$providers_out</select>";

		$clients_out = '';
		$clients = new CLINIC_Clients;
		$role_label = $clients -> get_role_label();
		$default  = sprintf( esc_html__( 'Select a %s', 'clinic' ), $role_label );
		$clients_out  .= "<option value=''>$default</option>";
		$clients_out .= $clients -> get_as_options( $this -> client_id );
		$name = CLINIC . '-client_id';
		$clients_out = "<select name='$name'>$clients_out</select>";

		echo $providers_out . $clients_out;

	}


	function posts_filter( $query ) {

		if( ! $this -> meta -> get_is_archive_session_admin() ) { return $query; }

		$query -> query_vars['meta_query'] = array();

		if( ! empty( $this -> provider_id ) ) {

			$query -> query_vars['meta_query'][] = array(
				'key'     => CLINIC . '-' . 'provider_ids',
				'value'   => $this -> provider_id,
			);

		}

		if( ! empty( $this -> client_id ) ) {

			$query -> query_vars['meta_query'][] = array(
				'key'     => CLINIC . '-' . 'client_ids',
				'value'   => $this -> client_id,
			);

		}		

		if( $this -> start && $this -> end ) {

			$query -> query_vars['meta_query'][] = array(
				'key'     => CLINIC . '-' . 'start',
				'value'   => absint( $this -> end ),
				'type'    => 'NUMERIC',
				'compare' => '<',
			);

			$query -> query_vars['meta_query'][] = array(
				'key'     => CLINIC . '-' . 'end',
				'value'   => absint( $this -> start ),
				'type'    => 'NUMERIC',
				'compare' => '>',
			);

		}

	    return $query;

	}




}