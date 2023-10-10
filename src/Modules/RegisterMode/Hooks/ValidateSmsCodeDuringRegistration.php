<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\RegisterMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\FilterInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceFactory;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceInterface;
use WP_Error;

class ValidateSmsCodeDuringRegistration implements FilterInterface
{
    private SimPayServiceInterface $simPayService;
    private int $smsNumber;

    public static function getHookName(): string
    {
        return 'registration_errors';
    }

    public function __invoke(WP_Error $errors)
    {
        $simPayOptions = get_option('simpay_options');

        if ($simPayOptions['simpay_plugin_mode'] !== 'register') {
            return $errors;
        }

        $this->smsNumber = (int) $simPayOptions['simpay_sms_number'];

        try {
            $this->simPayService = SimPayServiceFactory::create();
            return $this->validateSmsForm($errors);
        } catch (SimPayApiInvalidCredentialsException) {
            return;
        }
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    private function validateSmsForm(WP_Error $errors): WP_Error
    {
        if ($errors->has_errors()) {
            // Prevent validating SMS Code when the form already has errors to don't set it as used
            return $errors;
        }

        if (!isset($_POST['sms_code']) || trim($_POST['sms_code']) === '') {
            $errors->add('sms_code', __('<strong>Error</strong>: Enter SMS code.', 'simpay-wordpress'));
            return $errors;
        }

        if (!$this->simPayService->getSmsCodeValidation($_POST['sms_code'], $this->smsNumber)->isValid()) {
            $errors->add('sms_code', __('<strong>Error</strong>: Invalid SMS code.', 'simpay-wordpress'));
        }

        return $errors;
    }
}
