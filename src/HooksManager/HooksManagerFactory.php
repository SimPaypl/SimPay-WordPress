<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\HooksManager;

final class HooksManagerFactory
{
    public static function create(): HooksManagerInterface
    {
        return new LegacyHooksManagerService();
    }
}
