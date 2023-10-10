<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Modules\AdminManager\Form\Section;

interface SectionInterface
{
    public function register(): void;

    public function addField(string $id, string $title, callable $renderer): void;

    public function getSectionTitle(): string;

    public function getSectionName(): string;
}
