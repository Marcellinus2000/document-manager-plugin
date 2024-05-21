<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://marcel
 * @since             1.0.0
 * @package           Qtg_Docs_Mgr
 *
 * @wordpress-plugin
 * Plugin Name:       QTG Docs Manager
 * Plugin URI:        https://www.quantumterminals.com
 * Description:       This is a file management plugin for Quantum Terminals Group
 * Version:           1.0.0
 * Author:            Marcellinus
 * Author URI:        https://marcel/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       qtg-docs-mgr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'QTG_DOCS_MGR_VERSION', '1.0.0' );
define('QTG_DOCS-MGR-TABLE', '1.0.0');
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-qtg-docs-mgr-activator.php
 */
function activate_qtg_docs_mgr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-qtg-docs-mgr-activator.php';
	$activator = new Qtg_Docs_Mgr_Activator;
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-qtg-docs-mgr-deactivator.php
 */
function deactivate_qtg_docs_mgr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-qtg-docs-mgr-deactivator.php';
	$deactivator = new Qtg_Docs_Mgr_Deactivator();
	$deactivator->deactivate();
}

function delete_all_tables()
{
	require_once plugin_dir_path(__FILE__) . 'customs/class-qtg-docs-mgr-db-configs.php';
	$qtg_db_config = new Qtg_Docs_Mgr_Db();
	$qtg_db_config->drop_tables();
}

register_activation_hook( __FILE__, 'activate_qtg_docs_mgr' );
register_deactivation_hook( __FILE__, 'deactivate_qtg_docs_mgr' );
register_uninstall_hook(__FILE__, 'delete_all_tables');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-qtg-docs-mgr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_qtg_docs_mgr() {

	$plugin = new Qtg_Docs_Mgr();
	$plugin->run();

}
run_qtg_docs_mgr();
