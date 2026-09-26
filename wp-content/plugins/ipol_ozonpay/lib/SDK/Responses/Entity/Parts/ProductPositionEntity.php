<?php

namespace Ipol\OzonPay\SDK\Responses\Entity\Parts;

use Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity;

class ProductPositionEntity extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string|null
     */
    protected $mark;
    /**
     * @var MoneyEntity
     */
    protected $price;
    /**
     * @var string
     */
    protected $refund_type;
    /**
     * @var MoneyEntity
     */
    protected $remaining_amount;
    /**
     * @var bool|null
     */
    protected $mark_is_missing;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ProductPositionEntity
     */
    public function setId(string $id): ProductPositionEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMark(): ?string
    {
        return $this->mark;
    }

    /**
     * @param string|null $mark
     * @return ProductPositionEntity
     */
    public function setMark(?string $mark): ProductPositionEntity
    {
        $this->mark = $mark;
        return $this;
    }

    /**
     * @return MoneyEntity
     */
    public function getPrice(): MoneyEntity
    {
        return $this->price;
    }

    /**
     * @param MoneyEntity $price
     * @return ProductPositionEntity
     */
    public function setPrice($price): ProductPositionEntity
    {
        $this->price = (new MoneyEntity())->setFields($price);
        return $this;
    }

    /**
     * @return string
     */
    public function getRefundType(): string
    {
        return $this->refund_type;
    }

    /**
     * @param string $refund_type
     * @return ProductPositionEntity
     */
    public function setRefundType(string $refund_type): ProductPositionEntity
    {
        $this->refund_type = $refund_type;
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
     * @param MoneyEntity $remaining_amount
     * @return ProductPositionEntity
     */
    public function setRemainingAmount($remaining_amount): ProductPositionEntity
    {
        $this->remaining_amount = (new MoneyEntity())->setFields($remaining_amount);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMarkIsMissing(): ?bool
    {
        return $this->mark_is_missing;
    }

    /**
     * @param bool|null $mark_is_missing
     * @return ProductPositionEntity
     */
    public function setMarkIsMissing(?bool $mark_is_missing): ProductPositionEntity
    {
        $this->mark_is_missing = $mark_is_missing;
        return $this;
    }

}