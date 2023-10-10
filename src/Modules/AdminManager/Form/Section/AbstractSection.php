<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section;

use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\FormInterface;
use SimPay\SimPayWordpressPlugin\View\ViewManagerFactory;
use SimPay\SimPayWordpressPlugin\View\ViewManagerInterface;

abstract class AbstractSection implements SectionInterface
{
    protected FormInterface $form;
    protected ViewManagerInterface $view;

    private string $sectionName;
    private string $sectionTitle;

    public function __construct(FormInterface $form, string $sectionName, string $sectionTitle)
    {
        $this->form = $form;
        $this->sectionName = $sectionName;
        $this->sectionTitle = $sectionTitle;
        $this->view = ViewManagerFactory::create();
    }

    public function addField(string $id, string $title, callable $renderer): void
    {
        add_settings_field(
            $id,
            $title,
            $renderer,
            $this->form->getPageName(),
            $this->getSectionName(),
        );
    }

    public function getSectionName(): string
    {
        return $this->sectionName;
    }

    public function register(): void
    {
        add_settings_section(
            $this->getSectionName(),
            $this->getSectionTitle(),
            [$this, 'afterRegisterSection'],
            $this->form->getPageName(),
        );

        $this->registerFields();
    }

    public function getSectionTitle(): string
    {
        return $this->sectionTitle;
    }

    abstract protected function registerFields();

    public function afterRegisterSection(): void
    {
        settings_errors($this->getErrorsId(), true, true);
    }

    protected function getFieldNameForForm(string $string): string
    {
        return $this->form->getSettingsGroup() . "[$string]";
    }

    protected function addErrorAlert(string $message, $type = 'error'): void
    {
        add_settings_error($this->getErrorsId(), $this->getErrorsId(), $message, $type);
    }

    protected function getErrorsId(): string
    {
        return $this->getSectionName() . '_errors';
    }
}
