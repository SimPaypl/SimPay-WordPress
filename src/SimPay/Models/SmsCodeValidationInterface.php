<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Models;

interface SmsCodeValidationInterface
{
    public function isUsed(): bool|null;

    public function getCode(): string|null;

    public function isTestSms(): bool|null;

    public function getFromNumber(): int|null;

    public function getNumber(): int|null;

    public function getPriceNet(): string|null;

    public function isValid(): bool;
}
