<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/zapotocnylubos
 * @since      1.0.0
 *
 * @package    Spc
 * @subpackage Spc/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1><?php _e('Submission post creator', 'spc'); ?></h1>

    <form method="post" action="options.php">
        <?php
        settings_fields("spc-configuration");

        do_settings_sections("spc-page");

        submit_button();
        ?>

        <hr>
        <a href="<?php echo admin_url( 'admin-post.php?action=spc_print.csv' ) ?>">
            <?php _e('CSV DATA EXPORT', 'spc'); ?>
        </a>
        <small>(<?php _e('utf-8 encoding', 'spc'); ?>)</small>
    </form>
</div>