<?php

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form;

use SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section\SectionInterface;

interface FormInterface
{
    public function register(): void;

    public function getPageName(): string;

    public function getSettingsGroup(): string;

    public function addSection(SectionInterface $section);

    public function registerSections();

    public function getFormOptions(): array;

    public function getFormOptionValue(string $name): string;
}
