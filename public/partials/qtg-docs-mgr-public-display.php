<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://marcel
 * @since      1.0.0
 *
 * @package    Qtg_Docs_Mgr
 * @subpackage Qtg_Docs_Mgr/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div class="card">
    <div class="card_header">
        <h1>Quantum Terminal Files</h1>
        <div>
            <form method="post">
                <label for="year">Filter Files by Year:</label>
                <select name="file_year" id="file_year">
                    <option value="all" <?php echo isset($_POST['file_year']) && $_POST['file_year'] == 'all' ? 'selected' : ''; ?>>All</option>
                    <?php
                    $current_year = date('Y');
                        for ($year = $current_year; $year >= 2000; $year--) {
                            $selected = isset($_POST['file_year']) && $_POST['file_year'] == $year ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Filter">
            </form>
        </div>
    </div>

    <table id="file_table">
        <!-- <thead>
            <tr>
                <th>Name</th>
                <th>File</th>
            </tr>
        </thead> -->
        <tbody>

            <?php

              require_once plugin_dir_path(dirname(__FILE__)). '../customs/class-qtg-docs-mgr-db-configs.php';
		          $this->tableLoader = new Qtg_Docs_Mgr_Db();

                 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get the selected year
                $selected_year = $_POST['file_year'];

                // Modify your query to include the year condition only if it's not "all"
                if ($selected_year !== 'all') {
                    $uploads = $this->tableLoader->get_uploads_by_year(intval($selected_year));
                } else {
                    // If "all" is selected, fetch all uploads
                    $uploads = $this->tableLoader->get_all_uploads();
                }
            }

                else {
                        $uploads = $this->tableLoader->get_all_uploads();
                }
            

              if ($uploads) {
                // Loop through the data and populate the table
                foreach ($uploads as $index => $item) :
            ?>
                    <tr>
                        
                        <td> <a href="<?php echo esc_url($item['file_url']); ?>" target="_blank">
                        <img src="<?= plugin_dir_url(dirname(__FILE__)) . './images/pdf_image.png'; ?>" alt="PDF" width="20" height="20"/></a> <?php echo esc_html($item['name']); ?>
                        </td>
                        <td><a href="<?php echo esc_url($item['file_url']); ?>" target="_blank"><?php echo "Download"; ?></a></td>
                        
                    </tr>
            <?php
                endforeach;
            } else {
                echo '<tr><td colspan="3">No files found</td></tr>';
            }
            ?>
           
        </tbody>
    </table>
</div>