<?php

namespace SimPay\SimPayWordpressPlugin\SimPay;

use SimPay\API\SimPay;
use SimPay\API\Sms\Sms;
use SimPay\SimPayWordpressPlugin\SimPay\Dto\SmsCodeDto;
use SimPay\SimPayWordpressPlugin\SimPay\Dto\SmsCodeValidationDto;
use SimPay\SimPayWordpressPlugin\SimPay\Dto\SmsNumberDto;
use SimPay\SimPayWordpressPlugin\SimPay\Dto\SmsNumbersDto;
use SimPay\SimPayWordpressPlugin\SimPay\Dto\SmsServiceDto;
use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeValidationInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumberInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumbersInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsServiceInterface;

final class SimPayService implements SimPayServiceInterface
{
    private SimPay $simPay;
    private ?SmsServiceInterface $smsService;
    private ?SmsNumbersInterface $smsNumbers;

    public function __construct(mixed $apiKey, mixed $apiPassword, private readonly ?string $serviceId)
    {
        $this->simPay = new SimPay($apiKey, $apiPassword);
        $this->smsService = null;
        $this->smsNumbers = null;
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsNumbers(): SmsNumbersInterface
    {
        if ($this->smsNumbers !== null) {
            return $this->smsNumbers;
        }

        $smsNumbers = $this->getApi()->getServiceNumbers($this->getSmsService()->getId());
        $this->smsNumbers = new SmsNumbersDto($smsNumbers);

        return $this->smsNumbers;
    }

    private function getApi(): Sms
    {
        return $this->simPay->sms();
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsService(): SmsServiceInterface
    {
        if ($this->smsService !== null) {
            return $this->smsService;
        }

        $smsService = $this->getApi()->getService($this->serviceId);
        if ($smsService === false) {
            throw new SimPayApiInvalidCredentialsException();
        }

        $this->smsService = new SmsServiceDto($smsService);

        return $this->smsService;
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsNumber(int $smsNumber): SmsNumberInterface
    {
        $smsNumber = $this->getApi()->getServiceNumber($this->getSmsService()->getId(), $smsNumber);
        return new SmsNumberDto($smsNumber);
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsCodeValidation(string $smsCode, int $smsNumber): SmsCodeValidationInterface
    {
        $smsCodeValidation = $this->getApi()->getSmsCode($this->getSmsService()->getId(), $smsCode, $smsNumber);
        return new SmsCodeValidationDto($smsCodeValidation, $smsNumber);
    }

    /**
     * @throws SimPayApiInvalidCredentialsException
     */
    public function getSmsCode(): SmsCodeInterface
    {
        return new SmsCodeDto($this->getSmsService());
    }
}
