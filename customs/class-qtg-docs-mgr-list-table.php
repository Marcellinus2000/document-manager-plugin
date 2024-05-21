<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class QTG_Docs_Mgr_List_Table extends WP_List_Table
{

    public $db_instance;

    public function __construct()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'customs/class-qtg-docs-mgr-db-configs.php';
        $this->db_instance = new Qtg_Docs_Mgr_Db();

        parent::__construct([
            // singular name of the listed records
            'singular' => __('File Upload', 'sp'),

            // plural name of the listed records
            'plural' => __('File Uploads', 'sp'),

            'ajax' => true // should this table support ajax?
        ]);

    }

    /**
     * Retrieve all data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */


    public function get_all_files($per_page = 5, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$this->db_instance->qtg_docs_mgr_table}";


        if (!empty($_REQUEST['s'])) {
            $search = esc_sql($_REQUEST['s']);
            $sql .= " WHERE name LIKE '%{$search}%'";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Delete a File record.
     *
     * @param int $id customer ID
     */

     public function delete_file($id)
    {
        // global $wpdb;
        // return $wpdb->delete(
        //     "{$this->db_instance->qtg_docs_mgr_table}",
        //     ['id' => $id],
        //     ['%d']
        // );
        $this->db_instance->delete_file($id);
    }


	/**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count()
    {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$this->db_instance->qtg_docs_mgr_table}";
        return $wpdb->get_var($sql);
    }

	/**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
            case 'file_name':
            case 'file_year':
                return $item[$column_name];
            default:
                return print_r($item, true); 
        }
    }

	/**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

	/**
     * Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'sp'),
            'file_name' => __('File', 'sp'),
            'file_year' => __('File Year', 'sp')
        ];
    }


	 /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        return [
            'bulk-delete' => 'Delete'
        ];
    }

	/**
     * Get sortable columns
     * @return array
     */
    function get_sortable_columns()
    {
        return array(
            'name' => ['name', true],
            'file_year' => ['file_year', true],
        );
    }

	 /**
     * Get search box
     */
    public function search_box($text, $input_id)
    {
        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby'])) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        }
        if (!empty($_REQUEST['order'])) {
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        }
        if (!empty($_REQUEST['post_mime_type'])) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
        }
        if (!empty($_REQUEST['detached'])) {
            echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button($text, '', '', false, array('id' => 'search-submit')); ?>
        </p>
    <?php
    }

    public function prepare_items()
    {
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
            $this->get_primary_column_name(),
        ];

        /** Process bulk action */
        $this->process_bulk_action();


        $_per_page = $this->get_items_per_page('per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            //WE have to calculate the total number of items
            '_per_page' => $_per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = $this->get_all_files($_per_page, $current_page);
    }

    public function process_bulk_action()
{
    // Detect when a bulk action is being triggered...
    if ('delete' === $this->current_action()) {
        // In our file that handles the request, verify the nonce.
        $nonce = esc_attr($_REQUEST['_wpnonce']);

        if (!wp_verify_nonce($nonce, 'delete_upload_' . $_GET['upload_delete'])) {
            die('Go get a life script kiddies');
        } else {
            $this->delete_file(absint($_GET['upload_delete']));

            wp_redirect(esc_url(add_query_arg(array('page' => 'qtg-docs-mgr'), admin_url('admin.php'))));
            exit;
        }
    }

    // If the delete bulk action is triggered
    if (
        (isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
        || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
    ) {
        $delete_ids = esc_sql($_POST['bulk-delete']);

        // loop over the array of record IDs and delete them
        foreach ($delete_ids as $id) {
            $this->delete_file($id);
        }

        wp_redirect(esc_url(add_query_arg(array('page' => 'qtg-docs-mgr'), admin_url('admin.php'))));
        exit;
    }
}

}
?>