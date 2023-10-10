<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\Database\QueryManager\QueryManagerFactory;
use SimPay\SimPayWordpressPlugin\HooksManager\HooksManagerInterface;
use SimPay\SimPayWordpressPlugin\ModuleManager\ModuleInterface;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\AddPaywallMetaboxesToPostEdit;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\AddSubmenuToSimPayMenu;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\AddPaywallOnPost;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\PopulatePaywallColumnsOnPostsListing;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\SaveMetaboxPaywallData;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks\ShowPaywallColumnOnPostsListing;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceFactory;

final class PaywallModeModule implements ModuleInterface
{
    public function __construct(
        private readonly HooksManagerInterface  $hooksManager,
    ) {
    }

    public function loadModule(): void
    {
        $queryManager = QueryManagerFactory::create();
        $simPayService = SimPayServiceFactory::create();

        $paywallModeService = new PaywallModeService($queryManager);

        // Public - Paywall
        $this->hooksManager->addFilter(new AddPaywallOnPost($simPayService, $paywallModeService));

        // Admin - Metabox
        $this->hooksManager->addAction(new AddPaywallMetaboxesToPostEdit($simPayService));
        $this->hooksManager->addAction(new SaveMetaboxPaywallData());

        // Admin - Posts listing
        $this->hooksManager->addAction(new PopulatePaywallColumnsOnPostsListing($paywallModeService), 10, 2);
        $this->hooksManager->addFilter(new ShowPaywallColumnOnPostsListing());

        // Admin - Options
        $this->hooksManager->addAction(new AddSubmenuToSimPayMenu($paywallModeService), 11, 2);
    }
}
