<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\QueryManager;

final class FailedDatabaseQueryException extends \Exception
{
    public function __construct(private readonly string $lastDbError)
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return __CLASS__ . ": Failed to query {$this->lastDbError}";
    }
}
