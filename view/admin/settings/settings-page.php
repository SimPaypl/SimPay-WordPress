<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php wp_nonce_field('simpay_nonce', '_simpay_nonce'); ?>
        <?php
        settings_fields('simpay-options');
do_settings_sections('simpay-options');
submit_button('Save Settings');
?>
    </form>
</div>