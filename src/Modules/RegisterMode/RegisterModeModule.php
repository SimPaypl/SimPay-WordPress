<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\RegisterMode;

use SimPay\SimPayWordpressPlugin\Config\ConfigManagerInterface;
use SimPay\SimPayWordpressPlugin\HooksManager\HooksManagerInterface;
use SimPay\SimPayWordpressPlugin\ModuleManager\ModuleInterface;
use SimPay\SimPayWordpressPlugin\Modules\RegisterMode\Hooks\RegisterFormRender;
use SimPay\SimPayWordpressPlugin\Modules\RegisterMode\Hooks\ValidateSmsCodeDuringRegistration;

final class RegisterModeModule implements ModuleInterface
{
    public function __construct(
        private readonly HooksManagerInterface  $hooksManager,
    ) {
    }

    public function loadModule(): void
    {
        $this->hooksManager->addAction(new RegisterFormRender());
        $this->hooksManager->addFilter(new ValidateSmsCodeDuringRegistration());
    }
}
