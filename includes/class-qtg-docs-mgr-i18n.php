<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://marcel
 * @since      1.0.0
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/includes
 * @author     Marcellinus <macquaye@quantumgroupgh.com>
 */
class Qtg_Docs_Mgr_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'qtg-docs-mgr',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
