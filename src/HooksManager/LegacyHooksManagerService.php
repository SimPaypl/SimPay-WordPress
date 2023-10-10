<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\HooksManager;

final class LegacyHooksManagerService implements HooksManagerInterface
{
    protected array $actions = [];
    protected array $filters = [];

    public function addAction(
        ActionInterface $hookInstance,
        int             $priority = 10,
        int             $accepted_args = 1
    )
    {
        $this->actions = $this->add($this->actions, $hookInstance::getHookName(), $hookInstance, $priority, $accepted_args);
    }

    private function add(
        array                           $hooks,
        string                          $hookName,
        ActionInterface|FilterInterface $callableMethod,
        int                             $priority,
        int                             $acceptedArgs)
    {
        $hooks[] = [
            'hook' => $hookName,
            'callableMethod' => $callableMethod,
            'priority' => $priority,
            'acceptedArgs' => $acceptedArgs
        ];

        return $hooks;
    }

    public function addFilter(
        FilterInterface $hookInstance,
        int             $priority = 10,
        int             $accepted_args = 1
    )
    {
        $this->filters = $this->add($this->filters, $hookInstance::getHookName(), $hookInstance, $priority, $accepted_args);
    }

    public function loadHooks(): void
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], $hook['callableMethod'], $hook['priority'], $hook['acceptedArgs']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], $hook['callableMethod'], $hook['priority'], $hook['acceptedArgs']);
        }
    }
}
