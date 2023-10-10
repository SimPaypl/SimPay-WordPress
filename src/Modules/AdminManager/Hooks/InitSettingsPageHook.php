<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\FormSimPayOptions;

final class InitSettingsPageHook implements ActionInterface
{
    private const OPTION_GROUP = 'simpay_options';

    public static function getHookName(): string
    {
        return 'admin_init';
    }

    public function __invoke(): void
    {
        $formSimPayOptions = new FormSimPayOptions('simpay-options', self::OPTION_GROUP);
        $formSimPayOptions->register();
    }
}
