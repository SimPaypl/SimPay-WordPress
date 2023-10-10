<?php

namespace SimPay\SimPayWordpressPlugin\ModuleManager;

interface ModuleLoaderInterface
{
    public function loadModules(ModuleBag $moduleBag);
}
