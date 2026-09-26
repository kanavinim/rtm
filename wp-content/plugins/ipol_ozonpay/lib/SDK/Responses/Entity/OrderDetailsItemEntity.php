<?php

namespace Ipol\OzonPay\SDK\Responses\Entity;

use Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Responses\Entity\Parts\MoneyEntity;
use Ipol\OzonPay\SDK\Responses\Entity\Parts\ProductCollection;

class OrderDetailsItemEntity extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $number;
    /**
     * @var string
     */
    protected $ext_id;
    /**
     * @var string
     */
    protected $pay_link;
    /**
     * @var MoneyEntity
     */
    protected $remaining_amount;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $payment_algorithm;
    /**
     * @var string
     */
    protected $fiscalization_type;
    /**
     * @var ProductCollection
     */
    protected $items;
    /**
     * @var string
     */
    protected $expires_at;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return self
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
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
     * @return self
     */
    public function setExtId(string $ext_id): self
    {
        $this->ext_id = $ext_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayLink(): string
    {
        return $this->pay_link;
    }

    /**
     * @param string $pay_link
     * @return self
     */
    public function setPayLink(string $pay_link): self
    {
        $this->pay_link = $pay_link;
        return $this;
    }

    /**
     * @return MoneyEntity
     */
    public function getRemainingAmount(): MoneyEntity
    {
        return $this->remaining_amount;
    }

    /**
     * @param $remaining_amount
     * @return OrderDetailsItemEntity
     */
    public function setRemainingAmount($remaining_amount): OrderDetailsItemEntity
    {
        $this->remaining_amount = (new MoneyEntity())->setFields($remaining_amount);
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
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentAlgorithm(): string
    {
        return $this->payment_algorithm;
    }

    /**
     * @param string $payment_algorithm
     * @return self
     */
    public function setPaymentAlgorithm(string $payment_algorithm): self
    {
        $this->payment_algorithm = $payment_algorithm;
        return $this;
    }

    /**
     * @return string
     */
    public function getFiscalizationType(): string
    {
        return $this->fiscalization_type;
    }

    /**
     * @param string $fiscalization_type
     * @return self
     */
    public function setFiscalizationType(string $fiscalization_type): self
    {
        $this->fiscalization_type = $fiscalization_type;
        return $this;
    }

    /**
     * @return ProductCollection
     */
    public function getItems(): ProductCollection
    {
        return $this->items;
    }

    /**
     * @param $items
     * @return OrderDetailsItemEntity
     */
    public function setItems($items): OrderDetailsItemEntity
    {
        $this->items = (new ProductCollection())->fillFromArray((array)$items);
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiresAt(): string
    {
        return $this->expires_at;
    }

    /**
     * @param string $expires_at
     * @return self
     */
    public function setExpiresAt(string $expires_at): self
    {
        $this->expires_at = $expires_at;
        return $this;
    }

}