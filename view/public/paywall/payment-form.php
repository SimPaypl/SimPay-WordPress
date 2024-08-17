<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="simpay-paywall-payment-form">
    <p class="message">
        Aby uzyskać dostęp, wyślij SMS na numer
        <strong><?php echo esc_html($smsNumber); ?></strong> o treści
        <strong><?php echo esc_html($smsCode); ?></strong>. Koszt SMS
        to
        <strong><?php echo esc_html($smsPrice); ?> zł
            (brutto)</strong>
    </p>
    <form method="post">
        <?php wp_nonce_field('simpay_paywall_nonce', '_simpay_nonce'); ?>
        <input type="text" name="sms_code" id="sms_code" class="input" size="25" placeholder="Kod SMS" required />
        <input type="hidden" name="post_id"
            value="<?php echo esc_html($postId); ?>">
        <button>Wyślij</button>
    </form>
</div>