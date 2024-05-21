<?php

/**
 * Implement all database configurations here.
 *
 * @link       https://marcel
 * @since      1.0.0
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/customs
 */

/**
 *
 * This class defines all code necessary to run in the plugin's     database
 * @since      1.0.0
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/customs
 * @author     Marcellinus <macquaye@quantumgroupgh.com>
 */

class Qtg_Docs_Mgr_Db {

	/**
	 * The database table version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    
	 */

    protected $qtg_docs_mgr_db_version;


    /**
	 * The database table name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string      $qtg_docs_mgr_table  
	 */

    public $qtg_docs_mgr_table;


    /**
	 * The database properties 
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string      
	 */

    public $file_name;
    public $file_url;
    public $name;
    public $file_year;
    public $attachment_id;


    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

		global $wpdb;

		if(defined('QTG_DOCS_MGR_DB_VERSION')){
			$this->qtg_docs_mgr_db_version = QTG_DOCS_MGR_DB_VERSION;
		}
		else{
			$this->qtg_docs_mgr_db_version = '1.0.0';
		}

		if(defined('QTG_DOCS_MGR_TABLE')){
			$this->qtg_docs_mgr_table = $wpdb->prefix . 'QTG_DOCS_MGR_TABLE';
		}

		else {
			$this->qtg_docs_mgr_table = $wpdb->prefix . 'qtg_docs_mgr_table';
		}
	}

    /**
	 * Function to create tables based on the plugin version.
	 *
	 * @since    1.0.0
	 */


    public function create_tables(){

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        if(get_option('qtg_docs_mgr_db_version') != $this->qtg_docs_mgr_db_version) {

            $wpdb->query("DROP TABLE IF EXISTS {$this->qtg_docs_mgr_table}");

            $table_query = "CREATE TABLE $this->qtg_docs_mgr_table (
 					id int(11) NOT NULL AUTO_INCREMENT,
  					file_name varchar(100) NOT NULL,
  					file_url varchar(500) NOT NULL,
					name varchar (100) NOT NULL,
					file_year year (11) NOT NULL,
					attachment_id bigint(20) UNSIGNED,
  					created_at timestamp NULL DEFAULT current_timestamp(),
  					PRIMARY KEY (id)) $charset_collate;";

			require (ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($table_query);
        }
            return update_option('qtg_docs_mgr_db_version', $this->qtg_docs_mgr_db_version);
    }


    /**
	 * Function to delete tables if the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */

	public function drop_tables() {
		global $wpdb;

		$wpdb->query("DROP TABLES IF EXISTS {$this->qtg_docs_mgr_table}");
		delete_option('qtg_docs_mgr_db_version');
	}

	public function get_upload_by_id($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$this->qtg_docs_mgr_table} WHERE id = {$id}";
        return $wpdb->get_row($sql);
    }

	public function delete_file($id) {
        global $wpdb;

        // Get file information from the database
        $file_info = $this->get_upload_by_id($id);

        // Check if the file exists in the database
        if ($file_info) {
            // Delete the file from the media library
            $attachment_id = $file_info->attachment_id;
            if ($attachment_id) {
                wp_delete_attachment($attachment_id, true);
            }

            // Delete the file record from the database
            $wpdb->delete($this->qtg_docs_mgr_table, array('id' => $id));

            return true;
        }

        return false;
    }

	public function get_file_id_by_attachment_id($attachment_id){
        
		global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT id FROM {$this->qtg_docs_mgr_table} WHERE attachment_id = %d",
            $attachment_id
        );

        return $wpdb->get_var($sql);
    }


    /**
	 * Other functions needed to operate in the plugin.
	 *
	 * @since    1.0.0
	 */

     public function insert_new_upload() {
        global $wpdb;
        return $wpdb->insert("{$this->qtg_docs_mgr_table}", array(
            "file_name" => $this->file_name,
            "file_url" => $this->file_url,
            "name" => $this->name,
            "file_year" => $this->file_year,
			"attachment_id" => $this->attachment_id,
        ));
    }

	public function get_all_uploads() {
        global $wpdb;
        $sql = "SELECT * FROM {$this->qtg_docs_mgr_table}";
        return $wpdb->get_results($sql, 'ARRAY_A');
    }


	public function get_uploads_by_year($year){
		global $wpdb;

		$query = $wpdb->prepare("SELECT * FROM {$this->qtg_docs_mgr_table} WHERE file_year = %d", $year);

		return $wpdb->get_results($query, ARRAY_A);
	}
}