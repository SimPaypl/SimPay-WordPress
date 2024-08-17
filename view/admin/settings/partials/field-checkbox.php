<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<input
    id="<?php echo esc_html($args['name']); ?>"
    type="checkbox"
    name="<?php echo esc_html($args['name']); ?>"
    <?php echo $args['value'] ? 'checked' : ''; ?>>