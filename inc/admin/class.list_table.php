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

class CLINIC_List_Table {

	function __construct() {

		add_action( 'admin_head', array( $this, 'remove_date_drop' ) );

		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );

		add_filter( 'parse_query', array( $this, 'posts_filter' ) );
	


	}

	function remove_date_drop() {

		$screen = get_current_screen();

		#wp_die( 30 );

	    //if ( 'page' == $screen->post_type ){
    	    add_filter( 'months_dropdown_results', '__return_empty_array' );
    	//}

	}




	function restrict_manage_posts(){

	        $values = array(
	            'label' => 'value', 
	            'label1' => 'value1',
	            'label2' => 'value2',
	        );
	        ?>
	        <select name="ADMIN_FILTER_FIELD_VALUE">
	        <option value=""><?php _e('Filter By ', 'wose45436'); ?></option>
	        <option value=""><?php _e('Hello', 'wose45436'); ?></option>
	        </select>
	        <?php
	    

	}


	function posts_filter( $query ){
	    global $pagenow;
	    $type = 'post';
	    if (isset($_GET['post_type'])) {
	        $type = $_GET['post_type'];
	    }
	    if ( 'POST_TYPE' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '') {
	        $query->query_vars['meta_key'] = 'META_KEY';
	        $query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
	    }
	}




}