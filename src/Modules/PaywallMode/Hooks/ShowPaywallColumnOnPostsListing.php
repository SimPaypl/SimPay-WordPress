<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\FilterInterface;

class ShowPaywallColumnOnPostsListing implements FilterInterface
{
    public static function getHookName(): string
    {
        return 'manage_posts_columns';
    }

    public function __invoke(array $columns): array
    {
        $columns['paywall-users'] = __('Number of paid users', 'simpay-wordress');
        return $columns;
    }
}
