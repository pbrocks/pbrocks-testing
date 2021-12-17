<?php
/**
 * Initialize plugin with admin menus.
 *
 * @package pbrocks_dictionary
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

/**
 * Basic class for setting up the plugin.
 */
class PBrocks_Dictionary {
	/**
	 * [__construct]
	 */
	public function __construct() {
		add_action( 'add_to_pbrocks_dictionary_dash', array( $this, 'output_data_for_pbrocks_dictionary' ) );
	}
	/**
	 * [output_data_for_pbrocks_dictionary]
	 *
	 * @return void
	 */
	public function output_data_for_pbrocks_dictionary() {
		// phpcs:ignore
		echo '<h3>' . esc_html__( ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ), 'pbrocks-dictionary' ) . '</h3>';
		echo '<div class="add-to-diagnostics-dash" style="background:#ffebee;padding:1rem 2rem;border:.4rem solid #bbb">';
		$this->get_json_data();
		echo '</div>';
	}

	/**
	 * [determine_stars]
	 *
	 * @param  integer $stars Calculate value from input.
	 *
	 * @return string Calculate the number of stars.
	 */
	public function determine_stars( $stars ) {
		$rating = ' ';
		$x      = 0;

		while ( $x < $stars ) {
			$rating .= '★';
			$x++;
		}
		return $rating;
	}

	/**
	 * [get_json_data]
	 *
	 * @return void
	 */
	public function get_json_data() {
		$sample_json_file = plugin_dir_path( dirname( __DIR__ ) ) . 'interview-master/feedback.json';
		if ( file_exists( $sample_json_file ) ) {
			echo '<h4 style="color:green;">JSON file ' . esc_html( $sample_json_file ) . ' exists</h4>';
			$this->decode_json_data( $sample_json_file );
		} else {
			echo '<h4 style="color:purple;">JSON file ' . esc_html( $sample_json_file ) . ' does NOT exist!!</h4>';
		}
	}

	/**
	 * [decode_json_data]
	 *
	 * @param  integer $data Data from JSON input.
	 *
	 * @return void
	 */
	public function decode_json_data( $data ) {
		// Because we are looking for a local file with a self-signed certificate.
		// phpcs:ignore 
		$decoded = json_decode( file_get_contents( $data ) );
		foreach ( $decoded as $key => $grouping ) {
			if ( isset($grouping->rating) && null !== $grouping->rating ) {
				$stars  = $this->determine_stars( round( $grouping->rating / 20 ) );
				$string = $grouping->word . ': ' . $grouping->comment . ' ' . $stars . ' ' . date_i18n( 'n/d/Y', strtotime( $grouping->date ) );
			} else {
				$string = $grouping->word . ': ' . $grouping->comment . ' ' . date_i18n( 'n/d/Y', strtotime( $grouping->date ) );
			}
			$length = strlen( $string );
			$diff   = '';
			if ( $length > 80 ) {
				$string = $grouping->word . ': ' . $grouping->comment . ' ' . $stars;
				$length = strlen( $string );
			}

			if ( $length > 80 ) {
				$diff   = 80 - $length;
				$string = $grouping->word . ': ' . substr( $grouping->comment, 0, $diff ) . ' ' . $stars;
			}
			echo esc_html( $string ) . '<p>';
		}
	}
}
new PBrocks_Dictionary();
