<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<?php
    $defaultArgs = [
        'type' => 'text',
        'value' => '',
        'name' => '',
        'placeholder' => '',
    ];

$args = array_merge($defaultArgs, $args);
?>

<input
    id="<?php echo esc_html($args['name']); ?>"
    type="<?php echo esc_html($args['type']); ?>"
    type="<?php echo esc_html($args['value']); ?>"
    name="<?php echo esc_html($args['name']); ?>"
    placeholder="<?php echo esc_html($args['placeholder']); ?>"
    value="<?php echo esc_html($args['value']); ?>">