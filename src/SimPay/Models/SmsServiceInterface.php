<?php

namespace SimPay\SimPayWordpressPlugin\SimPay\Models;

use DateTimeInterface;
use SimPay\SimPayWordpressPlugin\SimPay\ServiceStatus;
use SimPay\SimPayWordpressPlugin\SimPay\ServiceType;

interface SmsServiceInterface
{
    public function getId(): string|null;

    public function getType(): ServiceType|null;

    public function getStatus(): ServiceStatus|null;

    public function getName(): string|null;

    public function getPrefix(): string|null;

    public function getSuffix(): string|null;

    public function getDescription(): string|null;

    public function isAdult(): bool|null;

    public function getCreatedAt(): DateTimeInterface|null;
}
