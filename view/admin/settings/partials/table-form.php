<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<table class="form-table" role="presentation">
    <tbody>
        <?php foreach ($elements as $label => $element) { ?>
        <tr>
            <th scope="row"><?php echo $label; ?></th>
            <td>
                <?php echo $element(); ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>