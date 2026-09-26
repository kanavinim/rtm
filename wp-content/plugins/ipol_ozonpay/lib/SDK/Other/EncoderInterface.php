<?php

namespace Ipol\OzonPay\SDK\Other;

/**
 * Interface EncoderInterface
 * @package Ipol\OzonPay\Other
 * Encodes handle from API-server into cms encoding
 */
interface EncoderInterface
{
    public function encodeToAPI($handle);

    public function encodeFromAPI($handle);
}