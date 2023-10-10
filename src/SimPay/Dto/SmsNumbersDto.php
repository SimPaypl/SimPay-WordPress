<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay\Dto;

use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumbersInterface;
use Traversable;

final class SmsNumbersDto implements SmsNumbersInterface
{

    public function __construct(private readonly mixed $phoneNumbers)
    {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->phoneNumbers as $phoneNumber) {
            yield new SmsNumberDto($phoneNumber);
        }
    }
}
