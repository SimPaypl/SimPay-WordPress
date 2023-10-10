<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Config;

final class ConfigManagerFactory
{
    /**
     * @throws ConfigManagerException
     */
    public static function create(): ConfigManagerInterface
    {
        [$configFilePath] = self::populateConfig();

        return new ConfigManagerService($configFilePath);
    }

    public static function populateConfig(): array
    {
        return [
            SIMPAY_CONFIG_PATH,
        ];
    }
}
