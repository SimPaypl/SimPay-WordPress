<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Dto;

use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeValidationInterface;

final class SmsCodeValidationDto implements SmsCodeValidationInterface
{

    public function __construct(
        private readonly mixed $smsCodeValidation,
        private readonly int $validSmsNumber,
    ) {
    }

    public function isUsed(): ?bool
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->used;
    }

    public function getCode(): ?string
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->code;
    }

    public function isTestSms(): ?bool
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->test;
    }

    public function getFromNumber(): ?int
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->from;
    }

    public function getPriceNet(): ?string
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->value;
    }

    public function isValid(): bool
    {
        if ($this->smsCodeValidation === false || $this->getNumber() === null) {
            return false;
        }

        if ($this->getNumber() === $this->validSmsNumber && $this->isUsed() === false) {
            return true;
        }

        return false;
    }

    public function getNumber(): ?int
    {
        if ($this->smsCodeValidation === false) {
            return null;
        }

        return $this->smsCodeValidation->number;
    }
}
