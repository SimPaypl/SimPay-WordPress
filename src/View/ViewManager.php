<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\View;

final class ViewManager implements ViewManagerInterface
{
    public function render(string $viewName, array $params = []): void
    {
        $callback = static function (string $template, array $params) {
            extract($params);
            require $template;
        };

        $callback($this->getFileNameFromView($viewName), $params);
    }

    private function getFileNameFromView($viewName): string
    {
        $safeName = preg_replace('/[^A-Za-z0-9 \-\.]/', '', $viewName);
        $fileNameWithoutExtension = str_replace('.', '/', $safeName);

        return SIMPAY_ABSPATH . 'view/' . $fileNameWithoutExtension . '.php';
    }
}
