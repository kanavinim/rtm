<?php

namespace Ipol\OzonPay\SDK;

use Ipol\OzonPay\SDK\Entity\Settings;

class OzonSDK
{
    private static $instance = null;

    public static function withSettings(Settings $settings)
    {
        if (self::$instance === null)
            self::$instance = new OzonPay($settings);
        return self::$instance;
    }

    public static function withParams(
        $apiUrl = '',
        $accessKey = '',
        $secretKey = '',
        $logFile = '',
        $encoder = null
    )
    {
        if (self::$instance === null) {
            $settings = (new Settings($accessKey,$secretKey))
                ->setApiUrl($apiUrl)
                ->setLogFile($logFile)
                ->setEncoder($encoder);
            self::$instance = new OzonPay($settings);
        }
        return self::$instance;
    }

}