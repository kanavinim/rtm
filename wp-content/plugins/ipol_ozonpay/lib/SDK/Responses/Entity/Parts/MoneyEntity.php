<?php

namespace Ipol\OzonPay\SDK\Responses\Entity\Parts;

class MoneyEntity extends \Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity
{
    /**
     * @var string
     */
    protected $currency_code;
    /**
     * @var string
     */
    protected $value;

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * @param string $currency_code
     * @return MoneyEntity
     */
    public function setCurrencyCode(string $currency_code): MoneyEntity
    {
        $this->currency_code = $currency_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return MoneyEntity
     */
    public function setValue(string $value): MoneyEntity
    {
        $this->value = $value;
        return $this;
    }
}