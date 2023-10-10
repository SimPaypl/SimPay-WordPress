<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\View;

final class ViewManagerFactory
{
    public static function create(): ViewManagerInterface
    {
        return new ViewManager();
    }
}
