<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode;

interface PaywallModeInterface
{
    public function getNumberOfPaywallUsersOfPost($postId = null, $users = []): int;

    public function getNumberOfPaywallPostsOfUser($userId = null, $posts = []): int;

    public function getPostsByUser(int $userId = null): array|null;

    public function grantAccessToPost(int $userId, int $postId);

    public function hasUserPaymentForPost(int $userId, int $postId);
}
