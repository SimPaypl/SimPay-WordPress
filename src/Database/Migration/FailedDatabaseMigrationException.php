<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

final class FailedDatabaseMigrationException extends \Exception
{
    public function __construct(private readonly string $migration)
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return __CLASS__ . ": Failed to execute migration {$this->migration}";
    }
}
