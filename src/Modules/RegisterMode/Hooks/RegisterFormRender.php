<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\RegisterMode\Hooks;

use SimPay\SimPayWordpressPlugin\HooksManager\ActionInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceFactory;
use SimPay\SimPayWordpressPlugin\View\ViewManagerFactory;

class RegisterFormRender implements ActionInterface
{

    public static function getHookName(): string
    {
        return 'register_form';
    }

    public function __invoke(): void
    {
        $simPayOptions = get_option('simpay_options');

        if ($simPayOptions['simpay_plugin_mode'] !== 'register') {
            return;
        }

        try {
            $simPayService = SimPayServiceFactory::create();
            $smsNumber = $simPayService->getSmsNumber((int) $simPayOptions['simpay_sms_number']);
        } catch (SimPayApiInvalidCredentialsException) {
            return;
        }

        $view = ViewManagerFactory::create();
        $view->render('public.register.register-form', [
            'smsNumber' => $smsNumber->getNumber(),
            'smsPrice' => $smsNumber->getPriceGross(),
            'smsCode' => $simPayService->getSmsCode()->getCode(),
        ]);
    }
}
