<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\View\ViewManagerFactory;

final class AddAdminPageHook implements ActionInterface
{

    public static function getHookName(): string
    {
        return 'admin_menu';
    }

    public function __invoke(): void
    {
        add_menu_page(
            'SimPay',
            'SimPay Options',
            'administrator',
            'simpay-options',
            [$this, 'getMenuCallback'],
        );
    }

    public function getMenuCallback(): void
    {
        $view = ViewManagerFactory::create();
        $view->render('admin.settings.settings-page');
    }
}
