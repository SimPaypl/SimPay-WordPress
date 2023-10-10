<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\View;

interface ViewManagerInterface
{
    public function render(string $viewName, array $params = []): void;
}
