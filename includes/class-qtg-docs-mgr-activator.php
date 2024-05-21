<?php

/**
 * Fired during plugin activation
 *
 * @link       https://marcel
 * @since      1.0.0
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/includes
 * @author     Marcellinus <macquaye@quantumgroupgh.com>
 */

class Qtg_Docs_Mgr_Activator {
	
	public $tableLoader;
	

	public function __construct(){

		require_once plugin_dir_path(dirname(__FILE__)). 'customs/class-qtg-docs-mgr-db-configs.php';

		$this->tableLoader = new Qtg_Docs_Mgr_Db();
	}

	public function activate() {
		$this->tableLoader->create_tables();
	}
};