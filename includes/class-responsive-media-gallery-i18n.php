<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/includes
 * @author     vsourz Dizital <mehul@vsourz.com>
 */
class Responsive_Media_Gallery_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'responsive-media-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
