<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

use ArrayIterator;
use IteratorAggregate;

final class MigrationsBag implements IteratorAggregate
{
    protected array $migrations = [];

    public function add(string $migration): void
    {
        $this->migrations[] = $migration;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->migrations);
    }
}
