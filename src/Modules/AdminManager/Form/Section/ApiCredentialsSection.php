<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section;

final class ApiCredentialsSection extends AbstractSection
{
    public function getFieldApiKey(): void
    {
        $this->view->render('admin.settings.partials.field-input', [
            'args' => [
                'type' => 'text',
                'placeholder' => 'API Key',
                'name' => $this->getFieldNameForForm('simpay_api_key'),
                'value' => $this->form->getFormOptionValue('simpay_api_key'),
            ]
        ]);
    }

    public function getFieldApiPassword(): void
    {
        $this->view->render('admin.settings.partials.field-input', [
            'args' => [
                'type' => 'password',
                'placeholder' => 'API Password',
                'name' => $this->getFieldNameForForm('simpay_api_password'),
                'value' => $this->form->getFormOptionValue('simpay_api_password'),
            ]
        ]);
    }

    public function getFieldServiceId(): void
    {
        $this->view->render('admin.settings.partials.field-input', [
            'args' => [
                'type' => 'text',
                'placeholder' => 'Service ID',
                'name' => $this->getFieldNameForForm('simpay_service_id'),
                'value' => $this->form->getFormOptionValue('simpay_service_id'),
            ]
        ]);
    }

    protected function registerFields(): void
    {
        $this->addField('simpay_api_key', 'API Key', [$this, 'getFieldApiKey']);
        $this->addField('simpay_api_password', 'API Password', [$this, 'getFieldApiPassword']);
        $this->addField('simpay_service_id', 'Service ID', [$this, 'getFieldServiceId']);
    }
}
