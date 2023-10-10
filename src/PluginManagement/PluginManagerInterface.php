<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\PluginManagement;

interface PluginManagerInterface
{
    public function init(): void;

    public function activatePlugin(): void;

    public function deactivatePlugin(): void;
}
