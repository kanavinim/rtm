<?php

namespace Ipol\OzonPay\SDK\Entity;

class Money extends AbstractEntity
{

    /**
     * @var string
     */
    protected $currencyCode;
    /**
     * @var int
     */
    protected $value;

    public function __construct(int $value = 0, string $currencyCode = '643')
    {
        $this->currencyCode = $currencyCode;
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return Money
     */
    public function setCurrencyCode(string $currencyCode): Money
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return Money
     */
    public function setValue(int $value): Money
    {
        $this->value = $value;
        return $this;
    }

}