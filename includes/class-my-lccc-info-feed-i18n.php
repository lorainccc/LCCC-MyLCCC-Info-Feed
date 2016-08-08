<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.lorainccc.edu
 * @since      1.0.0
 *
 * @package    My_Lccc_Info_Feed
 * @subpackage My_Lccc_Info_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    My_Lccc_Info_Feed
 * @subpackage My_Lccc_Info_Feed/includes
 * @author     David Brattoli <dbrattoli@lorainccc.edu>
 */
class My_Lccc_Info_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'my-lccc-info-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
