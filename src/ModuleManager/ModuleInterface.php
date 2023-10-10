<?php

namespace SimPay\SimPayWordpressPlugin\ModuleManager;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\HooksManager\HooksManagerInterface;

interface ModuleInterface
{
    public function loadModule();
}
