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
			'title'     => esc_html__( 'Title', 'clinic' ),
			'start'     => esc_html__( 'Start', 'clinic' ),
			'end'       => esc_html__( 'End', 'clinic' ),
			'clients'   => esc_html__( 'Clients', 'clinic' ),
			'providers' => esc_html__( 'Providers', 'clinic' ),
		);

		return $columns;

	}

	function session_content( $column, $post_id ) {
	
		global $post;

		$session = new CLINIC_Session( $post_id );

		if( $column == 'start' ) {

			echo $session -> get_meta( 'start' );

		} elseif( $column == 'end' ) {

			echo $session -> get_meta( 'end' );

		} elseif( $column == 'clients' ) {

			echo 'clients';

		} elseif( $column == 'providers' ) {

			echo 'providers';

		}

	}

	function session_sortable_columns( $columns ) {

		$columns['start']     = 'start';
		$columns['end']       = 'end';
		$columns['clients']   = 'clients';
		$columns['providers'] = 'providers';

		return $columns;
	
	}

	function session_load() {
		add_filter( 'request', array( $this, 'sort_columns' ) );
	}

	/* Sorts the movies. */
	function sort_columns( $vars ) {

		/* Check if we're viewing the 'movie' post type. */
		if ( ! isset( $vars['post_type'] ) ) { return $vars; }
		if( $vars['post_type'] != 'session' ) { return $vars; }
		if ( ! isset( $vars['orderby'] ) ) { return $vars; }

		if( $vars['orderby'] == 'start') {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'start',
					'orderby'  => 'meta_value_num'
				)
			);
	
		} elseif ( $vars['orderby'] == 'end' ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'end',
					'orderby'  => 'meta_value_num'
				)
			);

		}

		return $vars;
	
	}

}