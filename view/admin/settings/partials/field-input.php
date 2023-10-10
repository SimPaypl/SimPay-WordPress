<?php
    $defaultArgs = [
        'type' => 'text',
        'value' => '',
        'name' => '',
        'placeholder' => '',
    ];

    $args = array_merge($defaultArgs, $args);
?>

<input id="<?php echo $args['name'] ?>" type="<?php echo $args['type']; ?>" type="<?php echo $args['value']; ?>" name="<?php echo $args['name']; ?>" placeholder="<?php echo $args['placeholder']; ?>" value="<?php echo $args['value']; ?>">
