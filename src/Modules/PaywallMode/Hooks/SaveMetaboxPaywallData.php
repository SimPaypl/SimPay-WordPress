<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;

class SaveMetaboxPaywallData implements ActionInterface
{
    public static function getHookName(): string
    {
        return 'save_post';
    }

    public function __invoke($postId): void
    {
        if (!isset($_POST['_simpay_nonce']) || !wp_verify_nonce($_POST['_simpay_nonce'], 'simpay_nonce')) {
            return;
        }

        update_post_meta(
            $postId,
            'paywall_active',
            isset($_POST['paywall_active']),
        );

        if (isset($_POST['paywall_price'])) {
            update_post_meta(
                $postId,
                'paywall_price',
                intval($_POST['paywall_price'])
            );
        }
    }
}
