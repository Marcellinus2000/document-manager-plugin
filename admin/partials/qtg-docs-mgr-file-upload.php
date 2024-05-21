<?php

require_once plugin_dir_path(dirname(__FILE__)) . '../customs/class-qtg-docs-mgr-db-configs.php';
$d_con = new Qtg_Docs_Mgr_Db();

// Checking if the file upload form was submitted
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $name = sanitize_text_field($_POST['name']);
    $file_year = sanitize_text_field($_POST['file_year']);

    // Checking if there are no errors during the file upload
    if ($file['error'] === 0) {
        // Define the upload directory
        $upload_dir = wp_upload_dir();

        // Generating a unique filename to avoid overwriting existing files
        $unique_filename = wp_unique_filename($upload_dir['path'], $file['name']);

        // Handling the file upload through WordPress media functions
        $attachment_id = media_handle_upload('file', 0); // 0 means the file is unattached

        if (is_wp_error($attachment_id)) {
            // Error handling for the file move operation
            echo "Error uploading the file: " . $attachment_id->get_error_message();
        } else {
            
            global $wpdb;
            $table_name = $d_con->qtg_docs_mgr_table; 

            $data_to_insert = array(
                'file_name' => $unique_filename,
                'file_url' => wp_get_attachment_url($attachment_id),
                'name' => $name,
                'file_year' => $file_year,
                'attachment_id' => $attachment_id, // Storing the attachment ID in the database
            );

            $wpdb->insert($table_name, $data_to_insert);

            echo "<h3>File upload successful</h3>";
        }
    } else {
        // Error handling for the file upload
        echo "Error uploading file";
    }
}

?>

<div class = "layout">
<form action="" class="card" method="post" enctype="multipart/form-data">
    <div>
        <label for="file">File:</label>
        <input type="file" name="file">
    </div>
    <div>
        <label for="name">File Name:</label>
        <input type="text" name="name" placeholder="Name">
    </div>

    <div>
        <label for="year">Year:</label>
        <select name="file_year">
            <?php
                $current_year = date('Y');
                    for ($year = $current_year; $year >= 2000; $year--) {
                        $selected = isset($_POST['file_year']) && $_POST['file_year'] == $year ? 'selected' : '';
                        echo "<option value='$year' $selected>$year</option>";
                    }
            ?>
        </select>
    </div>

    <div>
    <input type="submit" value="Upload File">
    </div>
</form>
</div>