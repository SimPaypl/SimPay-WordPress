<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\PaywallModeInterface;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Tables\PaywallPostsTable;

class AddSubmenuToSimPayMenu implements ActionInterface
{
    public function __construct(private readonly PaywallModeInterface $paywallModeService)
    {
    }

    public static function getHookName(): string
    {
        return 'admin_menu';
    }

    public function __invoke(): void
    {
        add_posts_page(
            'Paywall Posts',
            'Paywall Posts',
            'administrator',
            'paywall-posts',
            [$this, 'showPostsTable'],
        );

        add_users_page(
            'Paywall Users',
            'Paywall Users',
            'administrator',
            'paywall-users',
        );
    }

    public function showPostsTable(): void
    {
        $table = new PaywallPostsTable($this->paywallModeService);
        $table->prepare_items();
        $table->display();
    }
}
