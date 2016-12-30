<?php

/**
 * A class for extending the wp rest api.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

new CLINIC_Api;

class CLINIC_Api {

	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	
	} 

	function rest_api_init() {
		register_rest_route( 
			CLINIC . '/v1', '/autocomplete/(?<object_type>\w+)',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'my_awesome_func' ),
				'args' => array(
					'term' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_scalar( $param );
						}
					),
					'object_type' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_scalar( $param );
						}
					),
				),
			)
		);
	}

	function my_awesome_func( WP_REST_Request $request ) {
		
		$term        = $request['term'];
		$object_type = $request['object_type'];

		$out = NULL;

		if( post_type_exists( $object_type ) ) {

			$args = array(
				'post_type' => $object_type,
				's'         => $term,
			);

			$query = new WP_Query( $args );

			if ( $query -> have_posts() ) {
				$out = array();
				while ( $query -> have_posts() ) {
					$query -> the_post();
					$out[] = array(
						'id'    => get_the_id(),
						'label' => get_the_title(),
						'value' => get_the_title(),					
					);
				}
				wp_reset_postdata();
			}

		} elseif( is_object( get_role( $object_type ) ) ) {

			$args = array(
				'role'   => $object_type,
				'search' => '*' . $term . '*',
			);

			$query = new WP_User_Query( $args );


			if ( $query -> results ) {
				$out = array();
				foreach ( $query -> results as $user ) {

					$out[] = array(
						'id'    => $user -> ID,
						'label' => $user -> display_name,
						'value' => $user -> display_name,					
					);
				}


			}

		}

		$out = $out;

		return $out;
	
	}

	/*
	
example output from remote source at https://jqueryui.com/resources/demos/autocomplete/search.php?term=rob	
[{"id":"Erithacus rubecula","label":"European Robin","value":"European Robin"},{"id":"Cercotrichas galactotes","label":"Rufous-Tailed Scrub Robin","value":"Rufous-Tailed Scrub Robin"},{"id":"Irania gutturalis","label":"White-throated Robin","value":"White-throated Robin"},{"id":"Turdus migratorius","label":"American Robin","value":"American Robin"}]

	 */

}