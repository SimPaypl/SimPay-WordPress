<div class="simpay-alert-paywall">
    <h2>Dziękujemy za zainteresowanie!</h2>
    <p>Dostęp do treści na tej stronie jest zarezerwowany tylko dla płatnych użytkowników.</p>
    <?php if (isset($error) && $error !== null): ?>
        <p>
            <?php echo $error; ?>
        </p>
    <?php endif; ?>
    <?php if (isset($showNotLoggedInInfo) && $showNotLoggedInInfo === true): ?>
        <p>Musisz być zalogowanym użytkownikiem. <a href="<?php echo $registerUrl; ?>">Kliknij tutaj</a>, aby się zalogować.</p>
    <?php endif; ?>
</div>
