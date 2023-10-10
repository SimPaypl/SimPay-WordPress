<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay;

use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeValidationInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumberInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumbersInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsServiceInterface;

interface SimPayServiceInterface
{
    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsService(): SmsServiceInterface;

    public function getSmsNumbers(): SmsNumbersInterface;

    public function getSmsNumber(int $smsNumber): SmsNumberInterface;

    public function getSmsCode(): SmsCodeInterface;

    public function getSmsCodeValidation(string $smsCode, int $smsNumber): SmsCodeValidationInterface;
}
