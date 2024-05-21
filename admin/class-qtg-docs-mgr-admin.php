<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://marcel
 * @since      1.0.0
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/admin
 * @author     Marcellinus <macquaye@quantumgroupgh.com>
 */
class Qtg_Docs_Mgr_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Enqueuing all plugin's CSS

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/qtg-docs-mgr-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Enqueuing all plugin's scripts

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/qtg-docs-mgr-admin.js', array( 'jquery' ), $this->version, false );

	}

	//Creating plugin Menu

	public function qtg_docs_mgr_menu (){

		add_menu_page("QTG Document Manager", "QTG Docs Mgr", "manage_options", "qtg-docs-mgr", array(&$this, "qtg_docs_mgr"), "dashicons-media-text", 22);

		add_submenu_page("qtg-docs-mgr", "Quantum Terminals Document Manager", "Dashboard", "manage_options", "qtg-docs-mgr", array(&$this, "qtg_docs_mgr_dashboard"));

		add_submenu_page("qtg-docs-mgr", "New File Upload", "File Upload", "manage_options", "file-upload", array(&$this, "file_upload"));
	}


	public function qtg_docs_mgr(){

		ob_start();
		include_once(PLUGIN_PATH ."admin/partials/qtg-docs-mgr-admin-display.php");
		$temp = ob_get_contents();
		ob_end_clean();

		echo ($temp);
	}

	public function qtg_docs_mgr_dashboard(){

		
	}

	public function file_upload() {
		ob_start();
		include_once(PLUGIN_PATH . "admin/partials/qtg-docs-mgr-file-upload.php");
		$temp = ob_get_contents();
		ob_end_clean();

		echo ($temp);
	}

}