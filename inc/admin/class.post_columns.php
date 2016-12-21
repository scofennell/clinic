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
		add_action( 'manage_posts_custom_column', array( $this, 'session_content' ), 10, 2 );
		add_action( 'manage_session_posts_custom_column', array( $this, 'session_content' ), 10, 2 );

		add_filter( 'manage_edit-session_sortable_columns', array( $this, 'session_sortable_columns' ) );

		/* Only run our customization on the 'edit.php' page in the admin. */
		add_action( 'load-edit.php', array( $this, 'session_load' ) );

		add_filter( 'post_row_actions', array( $this, 'disable_quick_edit' ), 10, 2 );
		
		add_filter( 'bulk_actions-edit-session', array( $this, 'bulk_actions' ) );

		add_action( 'pre_get_posts', array( $this, 'default_order' ), 9 );

	}

	function default_order( $query ) {

		if( ! $this -> is_post_type( 'session' ) ) { return FALSE; }

		$orderby = $query->get( 'orderby');

		if( $orderby == 'menu_order title' ) {
			$query->set( 'meta_key', CLINIC . '-start' );
            $query->set( 'orderby',  'meta_value_num' );
            $query->set( 'order',  'DESC' );
		}

	}

	function is_post_type( $post_type ) {

		if( ! is_admin() ) { return FALSE; }

		if ( ! isset( $_REQUEST['post_type'] ) ) { return FALSE; }
		if( $_REQUEST['post_type'] != $post_type ) { return FALSE; }
	
		return TRUE;

	}

    function bulk_actions( $actions ){
        unset( $actions[ 'edit' ] );
        return $actions;
    }

	function disable_quick_edit( $actions = array(), $post = null ) {

		if( ! $this -> is_post_type( 'session' ) ) { return $actions; }

	    // Remove the Quick Edit link
	    if ( isset( $actions['inline hide-if-no-js'] ) ) {
	        unset( $actions['inline hide-if-no-js'] );
	    }

	    // Return the set of links without Quick Edit
	    return $actions;

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
	
		if( ! $this -> is_post_type( 'session' ) ) { return FALSE; }

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

		if( ! $this -> is_post_type( 'session' ) ) { return FALSE; }

		$vars = array( $this, 'sort_columns' );

		add_filter( 'request', $vars );
	
	}

	/* Sorts the movies. */
	function sort_columns( $vars ) {

		global $post;

		/* Check if we're viewing the 'session' post type. */
		if( ! $this -> is_post_type( 'session' ) ) { return $vars; }	

		/*if( $vars['orderby'] == 'menu_order title' ) {

			$vars = array_merge(
				$vars,
				array(
					'meta_key' => CLINIC . '-start',
					'orderby'  => 'meta_value_num'
				)
			);

		} else*/if( $vars['orderby'] == 'start' ) {

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
			$vars = $vars;
	
		} elseif ( $vars['orderby'] == 'providers' ) {

			/* Merge the query vars with our custom variables. */
			$vars = $vars;

		} elseif( $vars['orderby'] == 'locations') {

			/* Merge the query vars with our custom variables. */
			$vars = $vars;
	
		} elseif ( $vars['orderby'] == 'services' ) {

			/* Merge the query vars with our custom variables. */
			$vars = $vars;

		}

		return $vars;
	
	}

}