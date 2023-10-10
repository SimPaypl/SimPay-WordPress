<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Models;

interface SmsCodeInterface
{
    public function getCode(): string;
}
