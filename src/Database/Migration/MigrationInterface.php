<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

interface MigrationInterface
{
    public static function up(string $dbPrefix, string $charsetCollate): string|array;

    public static function down(string $dbPrefix, string $charsetCollate): string|array;

    public static function getVersion(): int;
}
