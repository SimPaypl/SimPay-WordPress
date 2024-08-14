<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="simpay-paywall-payment-form">
    <p class="message">
        Aby uzyskać dostęp, wyślij SMS na numer
        <strong><?php echo $smsNumber; ?></strong> o treści
        <strong><?php echo $smsCode; ?></strong>. Koszt SMS to
        <strong><?php echo $smsPrice; ?> zł (brutto)</strong>
    </p>
    <form method="post">
        <input type="text" name="sms_code" id="sms_code" class="input" size="25" placeholder="Kod SMS" required />
        <input type="hidden" name="post_id"
            value="<?php echo $postId; ?>">
        <button>Wyślij</button>
    </form>
</div>