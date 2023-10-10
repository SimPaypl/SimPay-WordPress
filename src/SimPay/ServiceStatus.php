<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay;

enum ServiceStatus
{
    case service_new;
    case service_active;
    case service_blocked;
    case service_deleted;
    case service_second_verify;
    case service_rejected;
    case service_verify;
    case service_ongoing;
}
