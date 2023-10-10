<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Config;

use function array_keys;
use function array_values;
use function str_replace;
use function yaml_parse_file;

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
        $yamlTags = $this->getYamlTags();

        $ndocs = null;
        $this->configTree = yaml_parse_file(
            $this->configFilePath,
            0,
            $ndocs,
            ['' => fn($value) => str_replace(array_keys($yamlTags), array_values($yamlTags), $value)]
        );

        if ($this->configTree === false) {
            throw new ConfigManagerException('Invalid or missing yaml config');
        }
    }

    private function getYamlTags(): array
    {
        return [
            '%plugin_dir' => SIMPAY_ABSPATH,
        ];
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
