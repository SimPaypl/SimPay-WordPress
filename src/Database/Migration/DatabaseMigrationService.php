<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\Migration;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\Database\QueryManager\FailedDatabaseQueryException;
use SimPay\SimPayWordpressPlugin\Database\QueryManager\QueryManagerInterface;

final class DatabaseMigrationService implements DatabaseMigrationInterface
{
    private const CURRENT_DB_VERSION_OPTION_NAME = 'simpay_wordpress_current_db_version';

    private int $currentDbVersion;

    public function __construct(
        private readonly string $dbPrefix,
        private readonly string $dbCharsetCollate,
        private readonly QueryManagerInterface $queryManagerService,
        private readonly ConfigManagerInterface $configManager,
    ) {
        $this->currentDbVersion = (int)\get_option(self::CURRENT_DB_VERSION_OPTION_NAME, 0);
    }

    /**
     * @throws FailedDatabaseMigrationException
     */
    public function runMigration(): void
    {
        $migrationsToRun = $this->getMigrationsToExecute();
        /** @var MigrationInterface $migration */
        foreach ($migrationsToRun as $migration) {
            try {
                $queries = $migration::up($this->dbPrefix, $this->dbCharsetCollate);
                $this->runQueries($queries);
            } catch (FailedDatabaseQueryException) {
                throw new FailedDatabaseMigrationException($migration::class);
            }

            \update_option(self::CURRENT_DB_VERSION_OPTION_NAME, $migration::getVersion());
        }
    }

    public function getMigrationsToExecute(): MigrationsBag
    {
        $allMigrations = $this->configManager->getConfig('plugin_config.migration.migrations');
        $migrationsToExec = new MigrationsBag();

        foreach ($allMigrations as $migration) {
            if ($migration::getVersion() > $this->currentDbVersion) {
                $migrationsToExec->add($migration);
            }
        }

        return $migrationsToExec;
    }

    /**
     * @throws FailedDatabaseQueryException
     */
    private function runQueries(array|string $queries): void
    {
        if (!is_array($queries)) {
            $queries = [$queries];
        }

        foreach ($queries as $query) {
            $this->queryManagerService->write($query);
        }
    }
}
