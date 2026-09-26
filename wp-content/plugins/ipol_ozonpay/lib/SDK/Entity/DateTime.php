<?php

namespace Ipol\OzonPay\SDK\Entity;

class DateTime extends \DateTime
{

    public function getFormatted()
    {
        return $this->format('Y-m-d\TH:i:s\Z');
    }

    public function getFormattedIncreasedHour()
    {
        $newDate = $this;
        $newDate->modify('+1 hour');
        return $newDate->format('Y-m-d\TH:i:s\Z');
    }

}