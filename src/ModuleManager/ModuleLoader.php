<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\ModuleManager;

final class ModuleLoader implements ModuleLoaderInterface
{
    public function loadModules(ModuleBag $moduleBag): void
    {
        /* @var $module ModuleInterface */
        foreach ($moduleBag as $module) {
            $module->loadModule();
        }
    }
}
