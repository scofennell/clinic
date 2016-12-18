<?php

/**
 * A ckass for building a jqueryUI autosuggest.
 *
 * @package WordPress
 * @subpackage Clinic
 * @since Clinic 0.1
 */

class CLINIC_Autocomplete {

	public function __construct( $selector, $source_url, $current = '' ) {

		//wp_die( 'next step is to extend the wp api to output some autocomplete terms' );

		$this -> enqueue();

		$this -> current    = esc_attr( $current );

		$this -> source_url = esc_url( $source_url );

	}

	function enqueue() {

		wp_enqueue_script( 'jquery-ui-autocomplete' );

	}

	function get() {

		$class = __CLASS__ . '-' . __FUNCTION__;

		$current = $this -> current;

		$out = "
			<input type='text' value='$current' class='$class'>
		";

		return $out;

	}

	/*
	
example output from remote source at https://jqueryui.com/resources/demos/autocomplete/search.php?term=rob	
[{"id":"Erithacus rubecula","label":"European Robin","value":"European Robin"},{"id":"Cercotrichas galactotes","label":"Rufous-Tailed Scrub Robin","value":"Rufous-Tailed Scrub Robin"},{"id":"Irania gutturalis","label":"White-throated Robin","value":"White-throated Robin"},{"id":"Turdus migratorius","label":"American Robin","value":"American Robin"}]

	 */

}