<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="simpay-alert-paywall">
    <h2>Dziękujemy za zainteresowanie!</h2>
    <p>Dostęp do treści na tej stronie jest zarezerwowany tylko dla płatnych użytkowników.</p>
    <?php if (isset($error) && null !== $error) { ?>
    <p>
        <?php echo $error; ?>
    </p>
    <?php } ?>
    <?php if (isset($showNotLoggedInInfo) && true === $showNotLoggedInInfo) { ?>
    <p>Musisz być zalogowanym użytkownikiem. <a
            href="<?php echo esc_html($registerUrl); ?>">Kliknij
            tutaj</a>, aby się zalogować.</p>
    <?php } ?>
</div>