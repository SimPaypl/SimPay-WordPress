<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\PluginManagement;

final class PluginManagerFactory
{
    public static function create(): PluginManagerInterface
    {
        return new PluginManagerService();
    }
}
