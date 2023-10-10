<p class="message">
    Aby się zarejestrować, wyślij SMS <br>na numer <strong><?php echo $smsNumber; ?></strong><br>
    o treści <strong><?php echo $smsCode; ?></strong>.<br>
    Koszt SMS to <strong><?php echo $smsPrice; ?> zł (brutto)</strong>
</p>
<br>
<p>
    <label for="sms_code">Kod SMS</label>
    <input type="text" name="sms_code" id="sms_code" class="input" size="25" required />
</p>
