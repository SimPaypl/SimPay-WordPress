<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Models;

interface SmsNumberInterface
{
    public function getNumber(): int|null;

    public function getPriceNet(): float|null;

    public function getPriceGross(): float|null;

    public function isAdult(): bool|null;
}
