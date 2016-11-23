<?php

/**
 * Register post types.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

function clinic_post_types_init() {

	new CLINIC_Post_Types;

}
add_action( 'plugins_loaded', 'clinic_post_types_init', 100 ); 

class CLINIC_Post_Types {

	function __construct() {

		add_action( 'init', array( $this, 'session' ) );
		add_action( 'init', array( $this, 'service' ) );
		add_action( 'init', array( $this, 'location' ) );
		add_action( 'init', array( $this, 'testimonial' ) );

	}

	function session() {

		$labels = array(
			'name'               => _x( 'Sessions', 'post type general name', 'clinic' ),
			'singular_name'      => _x( 'Session', 'post type singular name', 'clinic' ),
			'menu_name'          => _x( 'Sessions', 'admin menu', 'clinic' ),
			'name_admin_bar'     => _x( 'Session', 'add new on admin bar', 'clinic' ),
			'add_new'            => _x( 'Add New', 'client', 'clinic' ),
			'add_new_item'       => __( 'Add New Session', 'clinic' ),
			'new_item'           => __( 'New Session', 'clinic' ),
			'edit_item'          => __( 'Edit Session', 'clinic' ),
			'view_item'          => __( 'View Session', 'clinic' ),
			'all_items'          => __( 'All Sessions', 'clinic' ),
			'search_items'       => __( 'Search Sessions', 'clinic' ),
			'parent_item_colon'  => __( 'Parent Sessions:', 'clinic' ),
			'not_found'          => __( 'No sessions found.', 'clinic' ),
			'not_found_in_trash' => __( 'No sessions found in Trash.', 'clinic' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'clinic' ),
			'public'             => FALSE,
			'publicly_queryable' => FALSE,
			'show_ui'            => TRUE,
			'show_in_menu'       => TRUE,
			'query_var'          => TRUE,
			'menu_icon'          => 'dashicons-calendar-alt',
			'rewrite'            => array( 'slug' => 'session' ),
			'capability_type'    => 'post',
			'has_archive'        => TRUE,
			'hierarchical'       => TRUE,
			'menu_position'      => null,
			'supports'           => array( FALSE ),
			'show_quick_edit'    => FALSE,
		);

		register_post_type( 'session', $args );

		$out = post_type_exists( 'session' );

		return $out;

	}


	function service() {

		$labels = array(
			'name'               => _x( 'Services', 'post type general name', 'clinic' ),
			'singular_name'      => _x( 'Service', 'post type singular name', 'clinic' ),
			'menu_name'          => _x( 'Services', 'admin menu', 'clinic' ),
			'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'clinic' ),
			'add_new'            => _x( 'Add New', 'client', 'clinic' ),
			'add_new_item'       => __( 'Add New Service', 'clinic' ),
			'new_item'           => __( 'New Service', 'clinic' ),
			'edit_item'          => __( 'Edit Service', 'clinic' ),
			'view_item'          => __( 'View Service', 'clinic' ),
			'all_items'          => __( 'All Services', 'clinic' ),
			'search_items'       => __( 'Search Services', 'clinic' ),
			'parent_item_colon'  => __( 'Parent Services:', 'clinic' ),
			'not_found'          => __( 'No services found.', 'clinic' ),
			'not_found_in_trash' => __( 'No services found in Trash.', 'clinic' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'clinic' ),
			'public'             => FALSE,
			'publicly_queryable' => FALSE,
			'show_ui'            => TRUE,
			'show_in_menu'       => TRUE,
			'query_var'          => TRUE,
			'menu_icon'          => 'dashicons-flag',
			'rewrite'            => array( 'slug' => 'service' ),
			'capability_type'    => 'post',
			'has_archive'        => TRUE,
			'hierarchical'       => TRUE,
			'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail', 'excerpt' )
		);

		register_post_type( 'service', $args );

		$out = post_type_exists( 'service' );

		return $out;

	}

	function location() {

		$labels = array(
			'name'               => _x( 'Locations', 'post type general name', 'clinic' ),
			'singular_name'      => _x( 'Location', 'post type singular name', 'clinic' ),
			'menu_name'          => _x( 'Locations', 'admin menu', 'clinic' ),
			'name_admin_bar'     => _x( 'Location', 'add new on admin bar', 'clinic' ),
			'add_new'            => _x( 'Add New', 'client', 'clinic' ),
			'add_new_item'       => __( 'Add New Location', 'clinic' ),
			'new_item'           => __( 'New Location', 'clinic' ),
			'edit_item'          => __( 'Edit Location', 'clinic' ),
			'view_item'          => __( 'View Location', 'clinic' ),
			'all_items'          => __( 'All Locations', 'clinic' ),
			'search_items'       => __( 'Search Locations', 'clinic' ),
			'parent_item_colon'  => __( 'Parent Locations:', 'clinic' ),
			'not_found'          => __( 'No locations found.', 'clinic' ),
			'not_found_in_trash' => __( 'No locations found in Trash.', 'clinic' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'clinic' ),
			'public'             => FALSE,
			'publicly_queryable' => FALSE,
			'show_ui'            => TRUE,
			'show_in_menu'       => TRUE,
			'query_var'          => TRUE,
			'menu_icon'          => 'dashicons-location',
			'rewrite'            => array( 'slug' => 'location' ),
			'capability_type'    => 'post',
			'has_archive'        => TRUE,
			'hierarchical'       => TRUE,
			'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail', 'excerpt' )
		);

		register_post_type( 'location', $args );

		$out = post_type_exists( 'location' );

		return $out;

	}

	function testimonial() {

		$labels = array(
			'name'               => _x( 'Testimonials', 'post type general name', 'clinic' ),
			'singular_name'      => _x( 'Testimonial', 'post type singular name', 'clinic' ),
			'menu_name'          => _x( 'Testimonials', 'admin menu', 'clinic' ),
			'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'clinic' ),
			'add_new'            => _x( 'Add New', 'client', 'clinic' ),
			'add_new_item'       => __( 'Add New Testimonial', 'clinic' ),
			'new_item'           => __( 'New Testimonial', 'clinic' ),
			'edit_item'          => __( 'Edit Testimonial', 'clinic' ),
			'view_item'          => __( 'View Testimonial', 'clinic' ),
			'all_items'          => __( 'All Testimonials', 'clinic' ),
			'search_items'       => __( 'Search Testimonials', 'clinic' ),
			'parent_item_colon'  => __( 'Parent Testimonials:', 'clinic' ),
			'not_found'          => __( 'No testimonials found.', 'clinic' ),
			'not_found_in_trash' => __( 'No testimonials found in Trash.', 'clinic' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'clinic' ),
			'public'             => FALSE,
			'publicly_queryable' => FALSE,
			'show_ui'            => TRUE,
			'show_in_menu'       => TRUE,
			'query_var'          => TRUE,
			'menu_icon'          => 'dashicons-megaphone',
			'rewrite'            => array( 'slug' => 'location' ),
			'capability_type'    => 'post',
			'has_archive'        => TRUE,
			'hierarchical'       => TRUE,
			'menu_position'      => null,
			'supports'           => array( 'title', 'thumbnail', 'excerpt' )
		);

		register_post_type( 'testimonial', $args );

		$out = post_type_exists( 'testimonial' );

		return $out;

	}

}