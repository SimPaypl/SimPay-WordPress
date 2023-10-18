<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Config;

final class ConfigManagerService implements ConfigManagerInterface
{
    private array $configTree;

    /**
     * @throws ConfigManagerException
     */
    public function __construct(private readonly string $configFilePath)
    {
        $this->loadConfig();
    }

    /**
     * @throws ConfigManagerException
     */
    private function loadConfig(): void
    {
        $jsonContent = file_get_contents($this->configFilePath);
        if (false === $jsonContent) {
            throw new ConfigManagerException('Unable to read JSON config file');
        }

        $this->configTree = json_decode($jsonContent, true);
        if (null === $this->configTree) {
            throw new ConfigManagerException('Invalid or missing JSON config');
        }

        $this->configTree = $this->replaceTags($this->configTree);
    }

    private function replaceTags(array $config): array
    {
        array_walk_recursive($config, function (&$value) {
            if (is_string($value)) {
                $value = \str_replace('%plugin_dir', SIMPAY_ABSPATH, $value);
            }
        });

        return $config;
    }

    public function getConfig($configName, $defaultValue = null): mixed
    {
        $expectedConfigTree = explode('.', $configName);

        $currentConfigNode = 0;
        $finalConfig = $this->configTree;
        while (true) {
            if (!isset($expectedConfigTree[$currentConfigNode])) {
                break;
            }
            $configNode = $expectedConfigTree[$currentConfigNode++];
            if (!isset($finalConfig[$configNode])) {
                $finalConfig = null;
                break;
            }
            $finalConfig = $finalConfig[$configNode];
        }

        return $finalConfig ?? $defaultValue;
    }
}
