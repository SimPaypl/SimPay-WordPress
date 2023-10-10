<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form;

use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section\ApiCredentialsSection;
use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section\PluginModeSection;

class FormSimPayOptions extends AbstractForm
{
    public function registerSections(): void
    {
        $this->addSection(
            new ApiCredentialsSection(
                $this,
                'simpay_api_credentials',
                'API Credentials'
            )
        );
        $this->addSection(
            new PluginModeSection(
                $this,
                'simpay_plugin_mode',
                'Plugin Mode'
            )
        );
    }
}
