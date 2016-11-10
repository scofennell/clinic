<?php

/**
 * Register post types, taxonomies, roles, etc...
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

register_activation_hook( CLINIC_FILE, array( 'CLINIC_Register', 'client' ) );
register_deactivation_hook( CLINIC_FILE, array( 'CLINIC_Register', 'client' ) );

register_activation_hook( CLINIC_FILE, array( 'CLINIC_Register', 'provider' ) );
register_deactivation_hook( CLINIC_FILE, array( 'CLINIC_Register', 'provider' ) );

function clinic_roles_init() {
	new CLINIC_Roles;
}
add_action( 'plugins_loaded', 'clinic_roles_init' );

class CLINIC_Roles {

	function __construct() {

		add_filter( 'editable_roles', array( $this, 'editable_roles' ) );

	}

	function editable_roles( $roles ) {

		unset( $roles['subscriber'] );
		unset( $roles['contributor'] );
		unset( $roles['author'] );
		unset( $roles['editor'] );

		return $roles;

	}

	static function client() {

		$current_filter = current_filter();
		if( $current_filter == 'activate_clinic/plugin.php' ) {

			$out = add_role(
				'client',
				esc_html__( 'Client', 'clinic' ),
				array(
					'read'         => TRUE, 
					'edit_posts'   => FALSE,
					'delete_posts' => FALSE,
				)
			);

		} else {

			$out = remove_role( 'client' );

		}

		return $out;

	}

	static function provider() {

		$current_filter = current_filter();
		if( $current_filter == 'activate_clinic/plugin.php' ) {

			$out = add_role(
				'provider',
				esc_html__( 'Provider', 'clinic' ),
				array(
					'read'         => TRUE, 
					'edit_posts'   => TRUE,
					'delete_posts' => TRUE,
				)
			);

		} else {

			$out = remove_role( 'provider' );

		}

		return $out;

	}

}