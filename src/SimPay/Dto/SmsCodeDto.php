<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Dto;

use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsCodeInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsServiceInterface;

final class SmsCodeDto implements SmsCodeInterface
{
    public function __construct(private readonly SmsServiceInterface $smsService)
    {
    }

    public function getCode(): string
    {
        return $this->smsService->getPrefix() . "." . $this->smsService->getSuffix();
    }
}
