<?php

/**
 * Register post columns.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_post_columns_init() {

	new CLINIC_Post_Columns;

}
add_action( 'plugins_loaded', 'clinic_post_columns_init', 110 ); 

class CLINIC_Post_Columns {

	function __construct() {

		add_filter( 'manage_session_posts_columns', array( $this, 'session_columns' ) );

		// manage_{$post_type}_posts_custom_column
		add_action( 'manage_session_posts_custom_column', array( $this, 'session_content' ), 10, 2 );

		add_filter( 'manage_edit-session_sortable_columns', array( $this, 'session_sortable_columns' ) );

		/* Only run our customization on the 'edit.php' page in the admin. */
		add_action( 'load-edit.php', array( $this, 'session_load' ) );

	}

	function session_columns() {

		$columns = array(
			'cb'        => '<input type="checkbox">',
			'start'     => esc_html__( 'Start', 'clinic' ),
			'end'       => esc_html__( 'End', 'clinic' ),
			'clients'   => esc_html__( 'Clients', 'clinic' ),
			'providers' => esc_html__( 'Providers', 'clinic' ),
			'locations' => esc_html__( 'Locations', 'clinic' ),
			'services'  => esc_html__( 'Services', 'clinic' ),			
		);

		return $columns;

	}

	function session_content( $column, $post_id ) {
	
		global $post;

		$session = new CLINIC_Session( $post_id );

		if( $column == 'start' ) {

			echo $session -> get_start_datetime();

		} elseif( $column == 'end' ) {

			echo $session -> get_end_datetime();

		} elseif( $column == 'clients' ) {

			echo $session -> get_meta_as_list( 'client_ids', 'display_name', 'get_userdata', 'get_edit_user_link', 'comma' );

		} elseif( $column == 'providers' ) {

			echo $session -> get_meta_as_list( 'provider_ids', 'display_name', 'get_userdata', 'get_edit_user_link', 'comma' );

		} elseif( $column == 'locations' ) {

			echo $session -> get_meta_as_list( 'location_ids', 'post_title', 'get_post', 'get_edit_post_link', 'comma' );

		} elseif( $column == 'services' ) {

			echo $session -> get_meta_as_list( 'service_ids', 'post_title', 'get_post', 'get_edit_post_link', 'comma' );

		}

	}

	function session_sortable_columns( $columns ) {

		$columns['start']     = 'start';
		$columns['end']       = 'end';
		$columns['clients']   = 'clients';
		$columns['providers'] = 'providers';
		$columns['locations'] = 'locations';
		$columns['services']  = 'services';		

		return $columns;
	
	}

	function session_load() {
		add_filter( 'request', array( $this, 'sort_columns' ) );
	}

	/* Sorts the movies. */
	function sort_columns( $vars ) {

		/* Check if we're viewing the 'session' post type. */
		if ( ! isset( $vars['post_type'] ) ) { return $vars; }
		if( $vars['post_type'] != 'session' ) { return $vars; }
		if( ! isset( $vars['orderby'] ) ) { return $vars; }		

		if( $vars['orderby'] == 'menu_order title' ) {

			$vars = array_merge(
				$vars,
				array(
					'meta_key' => CLINIC . '-start',
					'orderby'  => 'meta_value_num'
				)
			);

		} elseif( $vars['orderby'] == 'start' ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => CLINIC . '-start',
					'orderby'  => 'meta_value_num'
				)
			);
	
		} elseif ( $vars['orderby'] == 'end' ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => CLINIC . '-end',
					'orderby'  => 'meta_value_num'
				)
			);

		} elseif( $vars['orderby'] == 'clients') {

			/* Merge the query vars with our custom variables. */
			$vars = FALSE;
	
		} elseif ( $vars['orderby'] == 'providers' ) {

			/* Merge the query vars with our custom variables. */
			$vars = FALSE;

		} elseif( $vars['orderby'] == 'locations') {

			/* Merge the query vars with our custom variables. */
			$vars = FALSE;
	
		} elseif ( $vars['orderby'] == 'services' ) {

			/* Merge the query vars with our custom variables. */
			$vars = FALSE;

		}

		return $vars;
	
	}

}