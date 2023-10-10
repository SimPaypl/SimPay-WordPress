<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\QueryManager;

class QueryManagerFactory
{
    public static function create(): QueryManagerInterface
    {
        [$wpdb] = self::populateConfig();

        return new QueryManagerService($wpdb);
    }

    public static function populateConfig(): array
    {
        global $wpdb;

        return [$wpdb];
    }
}
