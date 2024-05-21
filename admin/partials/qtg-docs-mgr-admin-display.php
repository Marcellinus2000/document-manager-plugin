<?php

require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'customs/class-qtg-docs-mgr-list-table.php';
$table_instance = new QTG_Docs_Mgr_List_Table();
?>


<div class="wrap">
    <h1>Quantum Terminal Files</h1>
    <a class="page-title-action" href="<?php echo esc_url(admin_url('admin.php?page=file-upload')); ?>">New Upload</a>

    <h4> Use "[terminal_file]" as shortcode to call your public view </h4>

    <div class="meta-box-sortables ui-sortable">
        <form method="post">
            <?php
            $table_instance->prepare_items();
            $table_instance->search_box('Search', 'search_id');
            $table_instance->display(); ?>
        </form>
    </div>

</div>