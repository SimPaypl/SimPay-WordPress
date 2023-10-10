<?php

namespace SimPay\SimPayWordpressPlugin\SimPay;

enum ServiceType
{
    case ONE_TIME_CODE;
    case CODE_PACK;
    case API_URL;
}
