<?php

namespace SimPay\SimPayWordpressPlugin\Migrations;

use SimPay\SimPayWordpressPlugin\Database\Migration\MigrationInterface;

class Version_100_plugin_initialize implements MigrationInterface
{

    const MIGRATION_VERSION = 100;

    public static function up(string $dbPrefix, string $charsetCollate): string|array
    {
        return [
            "CREATE TABLE {$dbPrefix}simpay_wp_paywall_payments (
              id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              payment_date datetime DEFAULT NOW() NOT NULL,
              post_id bigint(20) unsigned NOT NULL,
              user_id bigint(20) unsigned NOT NULL,
              PRIMARY KEY  (id)
            ) {$charsetCollate};",
        ];
    }

    public static function down(string $dbPrefix, string $charsetCollate): string|array
    {
        return "DROP TABLE {$dbPrefix}simpay_wp_paywall_payments;";
    }

    public static function getVersion(): int
    {
        return self::MIGRATION_VERSION;
    }
}
