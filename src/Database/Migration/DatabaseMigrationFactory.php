<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\Database\QueryManager\QueryManagerFactory;

final class DatabaseMigrationFactory
{
    public static function create(ConfigManagerInterface $configManager): DatabaseMigrationInterface
    {
        [$dbPrefix, $dbCharsetCollate, $queryManagerService] = self::populateConfig();
        return new DatabaseMigrationService($dbPrefix, $dbCharsetCollate, $queryManagerService, $configManager);
    }

    public static function populateConfig(): array
    {
        global $wpdb;

        return [
            $wpdb->prefix,
            $wpdb->get_charset_collate(),
            QueryManagerFactory::create(),
        ];
    }
}
