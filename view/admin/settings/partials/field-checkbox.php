<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<input id="<?php echo $args['name']; ?>"
       type="checkbox"
       name="<?php echo $args['name']; ?>"
       <?php echo $args['value'] ? 'checked' : ''; ?>>