<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\SimPay;

final class SimPayServiceFactory
{
    public static function create(): SimPayServiceInterface
    {
        [$apiKey, $apiPassword, $serviceId] = self::populateConfig();

        return new SimPayService($apiKey, $apiPassword, $serviceId);
    }

    public static function populateConfig(): array
    {
        $simPayOptions = get_option('simpay_options');

        return [
            $simPayOptions['simpay_api_key'] ?? '',
            $simPayOptions['simpay_api_password'] ?? '',
            $simPayOptions['simpay_service_id'] ?? '',
        ];
    }
}
