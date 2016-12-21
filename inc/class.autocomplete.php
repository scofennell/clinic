<?php

/**
 * A ckass for building a jqueryUI autosuggest.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Autocomplete {

	public function __construct( $object_type, $current = '' ) {

		$this -> enqueue();

		$this -> localize();

		$this -> current     = esc_attr( $current );

		$this -> object_type = sanitize_key( $object_type );

	}

	function enqueue() {

		wp_enqueue_script( 'jquery-ui-autocomplete' );

	}

	function localize() {

		$source = get_rest_url( NULL, 'clinic/v1/autocomplete/' );

		$data = array(
			'source' => $source,
		);

		wp_localize_script( CLINIC . '-script', __CLASS__, $data );

	}

	function get() {

		$class = __CLASS__ . '-' . __FUNCTION__;

		$current = $this -> current;
		$object_type = $this -> object_type;

		$out = "
			<input data-object_type='$object_type' type='text' value='$current' class='$class'>
		";

		return $out;

	}

	/*
	
example output from remote source at https://jqueryui.com/resources/demos/autocomplete/search.php?term=rob	
[{"id":"Erithacus rubecula","label":"European Robin","value":"European Robin"},{"id":"Cercotrichas galactotes","label":"Rufous-Tailed Scrub Robin","value":"Rufous-Tailed Scrub Robin"},{"id":"Irania gutturalis","label":"White-throated Robin","value":"White-throated Robin"},{"id":"Turdus migratorius","label":"American Robin","value":"American Robin"}]

	 */

}