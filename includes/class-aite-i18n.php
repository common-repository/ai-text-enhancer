<?php
/**
 * Define the internationalization functionality
 *
 * @package    AITE
 */

/**
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class AITE_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ai-text-enhancer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
