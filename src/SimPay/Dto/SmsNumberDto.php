<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Dto;

use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumberInterface;

final class SmsNumberDto implements SmsNumberInterface
{
    public function __construct(private readonly mixed $smsNumber)
    {
    }

    public function getNumber(): ?int
    {
        if ($this->smsNumber === null) {
            return null;
        }

        return (int) $this->smsNumber->number;
    }

    public function getPriceNet(): ?float
    {
        if ($this->smsNumber === null) {
            return null;
        }

        return (float) $this->smsNumber->value;
    }

    public function getPriceGross(): ?float
    {
        if ($this->smsNumber === null) {
            return null;
        }

        return (float) $this->smsNumber->value_gross;
    }

    public function isAdult(): ?bool
    {
        if ($this->smsNumber === null) {
            return null;
        }

        return (bool) $this->smsNumber->adult;
    }
}
