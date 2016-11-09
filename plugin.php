<?php

/**
 * A boilerplate for starting new plugins.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 * 
 * Plugin Name: Clinic
 * Plugin URI: http://www.scottfennell.com
 * Description: Provides tools for managing a medical clinic.
 * Author: Scott Fennell
 * Version: 0.1
 * Author URI: http://www.scottfennell.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
	
// Peace out if you're trying to access this up front.
if( ! defined( 'ABSPATH' ) ) { exit; }

// Watch out for plugin naming collisions.
if( defined( 'CLINIC' ) ) { exit; }

// A slug for our plugin.
define( 'CLINIC', 'clinic' );

// Establish a value for plugin version to bust file caches.
define( 'CLINIC_VERSION', '0.1' );

// A constant to define the paths to our plugin folders.
define( 'CLINIC_FILE', __FILE__ );
define( 'CLINIC_PATH', trailingslashit( plugin_dir_path( CLINIC_FILE ) ) );

// A constant to define the urls to our plugin folders.
define( 'CLINIC_URL', trailingslashit( plugin_dir_url( CLINIC_FILE ) ) );

require_once( CLINIC_PATH . 'inc/class.bootstrap.php' );