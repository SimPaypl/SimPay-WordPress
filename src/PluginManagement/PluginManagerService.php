<?php

namespace SimPay\SimPayWordpressPlugin\PluginManagement;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerFactory;
use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\Database\Migration\DatabaseMigrationFactory;
use SimPay\SimPayWordpressPlugin\HooksManager\HooksManagerFactory;
use SimPay\SimPayWordpressPlugin\ModuleManager\ModuleBag;
use SimPay\SimPayWordpressPlugin\ModuleManager\ModuleLoader;

class PluginManagerService implements PluginManagerInterface
{
    private const LANGUAGE_DOMAIN = 'simpay-wordpress';

    private ConfigManagerInterface $configManager;

    public function __construct()
    {
        $this->configManager = ConfigManagerFactory::create();
    }

    public function init(): void
    {
        $hooksManager = HooksManagerFactory::create();

        $modulesToLoad = $this->configManager->getConfig('plugin_config.modules');

        $modules = new ModuleBag();
        foreach ($modulesToLoad as $module) {
            $modules->add(new $module($hooksManager, $this->configManager));
        }

        $moduleManager = new ModuleLoader();
        $moduleManager->loadModules($modules);

        $hooksManager->loadHooks();
    }

    public function activatePlugin(): void
    {
        $databaseMigration = DatabaseMigrationFactory::create($this->configManager);
        $databaseMigration->runMigration();
    }

    public function deactivatePlugin(): void
    {
        // TODO: Implement deactivatePlugin() method.
    }
}
