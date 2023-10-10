<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Config;

interface ConfigManagerInterface
{
    public function getConfig($configName, $defaultValue = null): mixed;
}
