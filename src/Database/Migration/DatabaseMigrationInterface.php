<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

interface DatabaseMigrationInterface
{
    public function getMigrationsToExecute(): MigrationsBag;

    /**
     * @throws FailedDatabaseMigrationException
     */
    public function runMigration(): void;
}
