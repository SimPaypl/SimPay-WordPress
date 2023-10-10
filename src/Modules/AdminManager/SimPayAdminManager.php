<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\HooksManager\HooksManagerInterface;
use SimPay\SimPayWordpressPlugin\ModuleManager\ModuleInterface;
use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Hooks\AddAdminPageHook;
use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Hooks\InitSettingsPageHook;

final class SimPayAdminManager implements ModuleInterface
{
    public function __construct(
        private readonly HooksManagerInterface $hooksManager,
        private readonly ConfigManagerInterface $configManager
    ) {
    }

    public function loadModule(): void
    {
        $this->initializeAdminSettings();
    }

    private function initializeAdminSettings(): void
    {
        $this->hooksManager->addAction(new InitSettingsPageHook());
        $this->hooksManager->addAction(new AddAdminPageHook());
    }
}
