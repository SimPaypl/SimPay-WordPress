<?php

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section;

use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\FormInterface;
use SimPay\SimPayWordpressPlugin\SimPay\Exception\SimPayApiInvalidCredentialsException;
use SimPay\SimPayWordpressPlugin\SimPay\Models\SmsNumberInterface;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceFactory;
use SimPay\SimPayWordpressPlugin\SimPay\SimPayServiceInterface;

class PluginModeSection extends AbstractSection
{
    private SimPayServiceInterface $simPayService;

    public function getFieldModeOfPlugin(): void
    {
        $disabledOptions = [];
        if (!get_option('users_can_register')) {
            $disabledOptions['register'] = 'Registrations are disabled';
        }
        $this->view->render('admin.settings.partials.field-select', [
            'args' => [
                'name' => $this->getFieldNameForForm('simpay_plugin_mode'),
                'value' => $this->form->getFormOptionValue('simpay_plugin_mode'),
                'options' => [
                    'register' => "Pay for register",
                    'per_post' => "Pay for post view",
                ],
                'disabled' => $disabledOptions,
            ]
        ]);
    }

    public function getFieldSmsNumber(): void
    {
        $options = [];
        $smsNumbers = $this->simPayService->getSmsNumbers();

        /** @var SmsNumberInterface $smsNumber */
        foreach ($smsNumbers as $smsNumber) {
            $options[$smsNumber->getNumber()] = $this->makeOptionValueForSmsPrice($smsNumber);
        }

        $this->view->render('admin.settings.partials.field-select', [
            'args' => [
                'name' => $this->getFieldNameForForm('simpay_sms_number'),
                'value' => $this->form->getFormOptionValue('simpay_sms_number'),
                'options' => $options,
            ]
        ]);
    }

    private function makeOptionValueForSmsPrice(SmsNumberInterface $smsNumber): string
    {
        return "{$smsNumber->getPriceNet()} PLN ({$smsNumber->getPriceGross()} PLN VAT)";
    }

    protected function registerFields(): void
    {
        if (!get_option('users_can_register')) {
            $this->addErrorAlert('Registrations of users are disabled, so you need to turn it on first to use Pay for register Mode', 'info');
        }

        if (
            $this->form->getFormOptionValue('simpay_api_key') &&
            $this->form->getFormOptionValue('simpay_api_password') &&
            $this->form->getFormOptionValue('simpay_service_id')
        ) {
            try {
                $this->simPayService = SimPayServiceFactory::create();
                $this->simPayService->getSmsService();
            } catch (SimPayApiInvalidCredentialsException) {
                $this->addErrorAlert('Invalid credentials to SimPay API', 'error');
                return;
            }
        } else {
            return;
        }

        $this->addField('simpay_plugin_mode', 'Mode of plugin', [$this, 'getFieldModeOfPlugin']);

        if (
            $this->form->getFormOptionValue('simpay_plugin_mode') === '' ||
            $this->form->getFormOptionValue('simpay_plugin_mode') === 'register'
        ) {
            $this->addField('simpay_sms_number', 'Price for registration', [$this, 'getFieldSmsNumber']);
        }
    }
}
