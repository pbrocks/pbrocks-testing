<?php
/**
 * Class PBrocks_Dictionary_Test
 *
 * @package PBrocks_Dictionary
 */

/**
 * WordPress PBrocks_Dictionary plugin tests
 */
class PBrocks_Dictionary_Test extends WP_UnitTestCase {

	/**
	 * Test hook creation.
	 */
	public function test_construct() {
		$menu_hook = new PBrocks_Dictionary();
		$check_exist = has_action( 'add_to_pbrocks_dictionary_dash', array( $menu_hook, 'output_data_for_pbrocks_dictionary' ) );
		$exists = ( 10 === $check_exist );
		$this->assertTrue( $exists );
	}

	/**
	 * [get_json_data]
	 *
	 * @return void
	 */
	public function test_bad_path_json_data() {
		$sample_json_file = plugin_dir_path( dirname( __DIR__ ) ) . 'interview-master/feedback.json';
		$exists = file_exists( $sample_json_file );
		$this->assertFalse( $exists );
	}

	/**
	 * [get_json_data]
	 *
	 * @return void
	 */
	public function test_get_json_data() {
		$sample_json_file = plugin_dir_path(  __DIR__ ) . 'interview-master/feedback.json';
		$exists = file_exists( $sample_json_file );
		$this->assertTrue( $exists );
	}
}
