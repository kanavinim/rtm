<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Entity\BasketItemPositionCollection;
use Ipol\OzonPay\SDK\Entity\Money;

class Refund extends AbstractEntity
{

    /**
     * @var string
     */
    protected $id;
    /**
     * @var Money
     */
    protected $amount;

    /**
     * @var BasketItemPositionCollection
     */
    protected $positions;

    /**
     * @var BasketItemPositionCollection|null - positions without marks
     */
    protected $itemsWithoutMark;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Refund
     */
    public function setId(string $id): Refund
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @param Money $amount
     * @return Refund
     */
    public function setAmount(Money $amount): Refund
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return BasketItemPositionCollection
     */
    public function getPositions(): BasketItemPositionCollection
    {
        return $this->positions;
    }

    /**
     * @param BasketItemPositionCollection $positions
     * @return Refund
     */
    public function setPositions(BasketItemPositionCollection $positions): Refund
    {
        $this->positions = $positions;
        return $this;
    }

    /**
     * @return BasketItemPositionCollection|null
     */
    public function getItemsWithoutMark(): ?BasketItemPositionCollection
    {
        return $this->itemsWithoutMark;
    }

    /**
     * @param BasketItemPositionCollection|null $itemsWithoutMark
     * @return Refund
     */
    public function setItemsWithoutMark(?BasketItemPositionCollection $itemsWithoutMark): Refund
    {
        $this->itemsWithoutMark = $itemsWithoutMark;
        return $this;
    }

}