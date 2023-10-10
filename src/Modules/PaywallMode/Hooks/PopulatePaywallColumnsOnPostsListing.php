<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\Modules\PaywallMode\PaywallModeInterface;

final class PopulatePaywallColumnsOnPostsListing implements ActionInterface
{
    public function __construct(private readonly PaywallModeInterface $paywallModeService)
    {
    }

    public static function getHookName(): string
    {
        return 'manage_posts_custom_column';
    }

    public function __invoke(string $column, int $postId): void
    {
        if ($column != 'paywall-users') {
            return;
        }

        echo $this->paywallModeService->getNumberOfPaywallUsersOfPost($postId);
    }
}
