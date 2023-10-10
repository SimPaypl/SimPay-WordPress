<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode;

use SimPay\SimPayWordpressPlugin\Database\QueryManager\QueryManagerInterface;

final class PaywallModeService implements PaywallModeInterface
{
    public function __construct(private readonly QueryManagerInterface $queryManager)
    {
    }

    public function getNumberOfPaywallUsersOfPost($postId = null, $users = []): int
    {
        $query = $this->makeWhereCondition(['post_id', $postId], ['user_id', $users], 'COUNT(*) as count');
        $result = $this->queryManager->readOne($query);

        return (int) $result?->count;
    }

    private function makeWhereCondition(array $singleWhere = null, array $arrayWhere = null, string $selector = '*'): string
    {
        $where = '';
        $clauses = [];
        if (isset($singleWhere) && $singleWhere[1] !== null) {
            $clauses[] = "{$singleWhere[0]} = {$singleWhere[1]}";
        }

        if (isset($arrayWhere) && !empty($arrayWhere[1])) {
            $list = join(', ', $arrayWhere[1]);
            $clauses[] = "{$arrayWhere[0]} IN({$list})";
        }

        if (!empty($clauses)) {
            $where = " WHERE " . join(' AND ', $clauses);
        }

        return "SELECT {$selector} FROM {$this->queryManager->getDbPrefix()}simpay_wp_paywall_payments pp{$where}";
    }

    public function getPostsByUser(int $userId = null): array|null
    {
        $query = $this->makeWhereCondition(
            ['user_id', $userId],
            null,
            'pp.*, u.user_nicename, p.post_title'
        );

        return $this->queryManager->read($query .
            ' LEFT JOIN wp_users u ON (u.id = pp.user_id) LEFT JOIN wp_posts p ON (p.id = pp.post_id)',
            ARRAY_A
        );
    }

    public function grantAccessToPost(int $userId, int $postId)
    {
        $this->queryManager->write(
            "INSERT INTO {$this->queryManager->getDbPrefix()}simpay_wp_paywall_payments 
            (`post_id`, `user_id`)
            VALUES ({$postId}, $userId);");
    }

    public function hasUserPaymentForPost(int $userId, int $postId): bool
    {
        return $this->getNumberOfPaywallPostsOfUser($userId, [$postId]) > 0;
    }

    public function getNumberOfPaywallPostsOfUser($userId = null, $posts = []): int
    {
        $query = $this->makeWhereCondition(['user_id', $userId], ['post_id', $posts], 'COUNT(*) as count');
        $result = $this->queryManager->readOne($query);

        return (int) $result?->count;
    }
}
