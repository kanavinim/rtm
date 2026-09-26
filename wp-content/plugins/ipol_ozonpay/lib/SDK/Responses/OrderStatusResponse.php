<?php

namespace Ipol\OzonPay\SDK\Responses;

use Ipol\OzonPay\SDK\Responses\Entity\Parts\MoneyEntity;

class OrderStatusResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $ext_id;
    /**
     * @var MoneyEntity
     */
    protected $originalAmount;
    /**
     * @var MoneyEntity
     */
    protected $remainingAmount;
    /**
     * @var string
     */
    protected $status;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return OrderStatusResponse
     */
    public function setId(string $id): OrderStatusResponse
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @param string $ext_id
     * @return OrderStatusResponse
     */
    public function setExtId(string $ext_id): OrderStatusResponse
    {
        $this->ext_id = $ext_id;
        return $this;
    }

    /**
     * @return MoneyEntity
     */
    public function getOriginalAmount(): MoneyEntity
    {
        return $this->originalAmount;
    }

    /**
     * @param MoneyEntity $originalAmount
     * @return OrderStatusResponse
     */
    public function setOriginalAmount($originalAmount): OrderStatusResponse
    {
        $this->originalAmount = (new MoneyEntity())->setFields($originalAmount);
        return $this;
    }

    /**
     * @return MoneyEntity
     */
    public function getRemainingAmount(): MoneyEntity
    {
        return $this->remainingAmount;
    }

    /**
     * @param MoneyEntity $remainingAmount
     * @return OrderStatusResponse
     */
    public function setRemainingAmount($remainingAmount): OrderStatusResponse
    {
        $this->remainingAmount = (new MoneyEntity())->setFields($remainingAmount);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return OrderStatusResponse
     */
    public function setStatus(string $status): OrderStatusResponse
    {
        $this->status = $status;
        return $this;
    }

}