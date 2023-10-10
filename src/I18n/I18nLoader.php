<?php

namespace SimPay\SimPayWordpressPlugin\I18n;

class I18nLoader
{
    public function __construct(private readonly string $domainName){}

    public function loadLanguage() {
        load_plugin_textdomain(
            $this->domainName,
            false,
            SIMPAY_ABSPATH . 'languages/'
        );
    }
}
