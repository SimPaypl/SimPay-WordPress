<?php

namespace SimPay\SimPayWordpressPlugin\ModuleManager;


use ArrayIterator;
use IteratorAggregate;

class ModuleBag implements IteratorAggregate
{
    protected array $modules = [];

    public function add(ModuleInterface $module)
    {
        $this->modules[$module::class] = $module;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->modules);
    }
}
