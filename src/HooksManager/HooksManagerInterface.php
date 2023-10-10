<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\HooksManager;

interface HooksManagerInterface
{
    public function addFilter(
        FilterInterface $hookInstance,
        int             $priority,
        int             $accepted_args
    );

    public function addAction(
        ActionInterface $hookInstance,
        int             $priority = 10,
        int             $accepted_args = 1
    );
}
