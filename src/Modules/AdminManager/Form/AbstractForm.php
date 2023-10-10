<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form;

use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section\SectionInterface;

abstract class AbstractForm implements FormInterface
{
    private string $settingsName;
    private string $pageName;
    private string $settingsGroup;
    private array $formOptions;

    public function __construct(string $pageName, string $settingsGroup)
    {
        $this->pageName = $pageName;
        $this->settingsGroup = $settingsGroup;
    }

    public function addSection(SectionInterface $section)
    {
        $section->register();
    }

    public function register(): void
    {
        register_setting($this->getPageName(), $this->getSettingsGroup());
        $this->formOptions = get_option($this->getSettingsGroup()) ?: [];

        $this->registerSections();
    }

    public function getPageName(): string
    {
        return $this->pageName;
    }

    public function getSettingsGroup(): string
    {
        return $this->settingsGroup;
    }

    abstract public function registerSections();

    public function getFormOptionValue(string $name): string
    {
        if (!isset($this->getFormOptions()[$name])) {
            return '';
        }

        return $this->getFormOptions()[$name];
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }
}
