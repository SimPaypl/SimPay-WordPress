<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\PaywallMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumberInterface;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceInterface;
use SimPay\SimPayWordpressPlugin\View\ViewManagerFactory;
use SimPay\SimPayWordpressPlugin\View\ViewManagerInterface;

final class AddPaywallMetaboxesToPostEdit implements ActionInterface
{
    private ViewManagerInterface $view;
    private mixed $post;

    public function __construct(private readonly SimPayServiceInterface $simPayService)
    {
        $this->view = ViewManagerFactory::create();
    }

    public static function getHookName(): string
    {
        return 'add_meta_boxes';
    }

    public function __invoke(): void
    {
        add_meta_box(
            'simpay-paywall-options',
            __('Paywall settings', 'simpay-wordpress'),
            [$this, 'renderMetaboxContent'],
        );
    }

    public function renderMetaboxContent($post): void
    {
        $this->post = $post;
        $this->view->render('admin.settings.partials.table-form', [
            'elements' => [
                __('Is Paywall active:') => [$this, 'getIsPaywallActive'],
                __('SMS Numbers:') => [$this, 'getSmsNumbers'],
            ]
        ]);
    }

    public function getIsPaywallActive(): void
    {
        $this->view->render('admin.settings.partials.field-checkbox', [
            'args' => [
                'name' => 'paywall_active',
                'value' => get_post_meta($this->post->ID, 'paywall_active', false)[0] ?? false,
            ],
        ]);
    }

    public function getSmsNumbers(): void
    {
        $options = [];
        $smsNumbers = $this->simPayService->getSmsNumbers();

        /** @var SmsNumberInterface $smsNumber */
        foreach ($smsNumbers as $smsNumber) {
            $options[$smsNumber->getNumber()] = $this->makeOptionValueForSmsPrice($smsNumber);
        }

        $this->view->render('admin.settings.partials.field-select', [
            'args' => [
                'name' => 'paywall_price',
                'value' => get_post_meta($this->post->ID, 'paywall_price', null)[0] ?? false,
                'options' => $options
            ]
        ]);
    }

    private function makeOptionValueForSmsPrice(SmsNumberInterface $smsNumber): string
    {
        return "{$smsNumber->getPriceNet()} PLN ({$smsNumber->getPriceGross()} PLN VAT)";
    }
}
