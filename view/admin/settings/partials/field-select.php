<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<select
    id="<?php echo esc_html($args['name']); ?>"
    class='post_form'
    name='<?php echo esc_html($args['name']); ?>'>

    <?php foreach ($args['options'] as $optionValue => $optionTitle) {
        if ($args['value'] == $optionValue) {
            $checked = 'selected ';
        } else {
            $checked = '';
        }
        if (isset($args['disabled']) && isset($args['disabled'][$optionValue])) {
            $disabled = 'disabled ';
            $disabledReason = '('.$args['disabled'][$optionValue].')';
        } else {
            $disabled = '';
            $disabledReason = '';
        }
        ?>
    <option value="<?php echo $optionValue; ?>" <?php echo $checked; ?><?php echo $disabled; ?>>
        <?php echo $optionTitle; ?>
        <?php echo $disabledReason; ?>
    </option>
    <?php } ?>
</select>