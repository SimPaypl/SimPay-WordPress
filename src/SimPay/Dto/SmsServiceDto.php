<?php

namespace SimPay\SimPayWordpressPlugin\SimPay\Dto;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsServiceInterface;
use SimPay\SimPayWordpressPlugin\SimPay\ServiceStatus;
use SimPay\SimPayWordpressPlugin\SimPay\ServiceType;

final class SmsServiceDto implements SmsServiceInterface
{
    public function __construct(private readonly object $response)
    {
    }

    public function getId(): ?string
    {
        return $this->response?->id;
    }

    public function getType(): ?ServiceType
    {
        return $this->response?->type;
    }

    public function getStatus(): ?ServiceStatus
    {
        return $this->response?->status;
    }

    public function getName(): ?string
    {
        return $this->response?->name;
    }

    public function getPrefix(): ?string
    {
        return $this->response?->prefix;
    }

    public function getSuffix(): ?string
    {
        return $this->response?->suffix;
    }

    public function getDescription(): ?string
    {
        return $this->response?->description;
    }

    public function isAdult(): ?bool
    {
        return $this->response?->adult;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        try {
            return new DateTimeImmutable($this->response?->created_at);
        } catch (Exception) {
            return null;
        }
    }
}
