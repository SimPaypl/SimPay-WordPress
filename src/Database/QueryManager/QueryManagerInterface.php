<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\QueryManager;

interface QueryManagerInterface
{
    /**
     * @throws FailedDatabaseQueryException
     */
    public function write(string $query): int|bool;

    public function readOne(string $query): mixed;

    public function read(string $query): mixed;

    public function getDbPrefix(): string;

    public function getDbCharset(): string;
}
