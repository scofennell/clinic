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
//add_action( 'plugins_loaded', 'clinic_list_table_init', 110 );
add_action( 'current_screen', 'clinic_list_table_init', 110 ); 

class CLINIC_List_Table {

	function __construct() {

		$this -> set_provider_id();
		$this -> set_start();
		$this -> set_end();

		add_action( 'admin_head', array( $this, 'remove_date_drop' ) );

		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );

		add_filter( 'parse_query', array( $this, 'posts_filter' ) );
	
		$screen = get_current_screen();
		$this -> post_type = $screen -> post_type;
		$this -> base = $screen -> base;

	}

	function set_provider_id() {

		$provider_id = FALSE;

		if( isset( $_GET[ CLINIC . '-provider_id'] ) ) {

			$provider_id = absint( $_GET[ CLINIC . '-provider_id'] );

		}

		$this -> provider_id = $provider_id;

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

		if ( $this -> post_type != 'session' ) { return FALSE; }

    	add_filter( 'months_dropdown_results', array( $this, 'months_dropdown_results' ) );

	}

	function months_dropdown_results( $in ) {

		if ( $this -> post_type != 'session' ) { return $in; }

		return array();

	}


	function restrict_manage_posts() {

		if ( $this -> post_type != 'session' ) { return FALSE; }

		$providers = new CLINIC_Providers;

		$role_label = $providers -> get_role_label();
		$default  = sprintf( esc_html__( 'Select a %s', 'clinic' ), $role_label );
		$out  = "<option value=''>$default</option>";

		$out .= $providers -> get_as_options( $this -> provider_id );

		$name = CLINIC . '-provider_id';

		$out = "<select name='$name'>$out</select>";

		echo $out;

	}


	function posts_filter( $query ) {

		if( ! is_admin() ) { return $query; }
		if ( $this -> post_type != 'session' ) { return $query; }
		if ( $this -> base != 'edit' ) { return $query; }

		if( ! empty( $this -> provider_id ) ) {

			$query -> query_vars['meta_key']     = CLINIC . '-' . 'provider_ids';
			$query -> query_vars['meta_value']   = $this -> provider_id;

		}

		if( $this -> start && $this -> end ) {

			//week 44:
			//1477 958400
			//1478 563199

			//post 105:
			//1479 764700
			//1479 768300


			#$query -> query_vars['meta_key']   = CLINIC . '-' . 'start';
			#$query -> query_vars['meta_value'] = $this -> start;
			#$query -> query_vars['meta_compare']    = '>';

			$query -> query_vars['meta_query'] = array(
				array(
					'key'     => CLINIC . '-' . 'start',
					'value'   => absint( $this -> end ),
					'type'    => 'NUMERIC',
					'compare' => '<',
				),
				array(
					'key'     => CLINIC . '-' . 'end',
					'value'   => absint( $this -> start ),
					'type'    => 'NUMERIC',
					'compare' => '>',
				),
			);


		}


		/*$mq = array(
			array(
				'key'     => CLINIC . '-' . 'provider_idsaaa',
				'value'   => $this -> provider_id,
				'compare' => 'LIKE',
			),
		);*/


		#$query -> query_vars['meta_query'] = $mq;
		#$query -> meta_query = $mq;

		#$query -> query_vars['meta_compare'] = 'IN';

	    return $query;

	}




}