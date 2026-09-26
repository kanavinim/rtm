<?php

namespace Ipol\OzonPay\SDK\Entity;

use Ipol\OzonPay\SDK\Exceptions\OzonPayException;

class BasketItemPosition extends AbstractEntity
{
    /**
     * @var string|null
     */
    protected $id;
    /**
     * @var string|null
     */
    protected $mark;
    /**
     * @var Money|null
     */
    protected $price;
    /**
     * @var string|null
     */
    protected $refundType;
    /**
     * @var Money|null
     */
    protected $remainingAmount;

    /**
     * @var Money|null
     */
    protected $amount;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return BasketItemPosition
     */
    public function setId(?string $id): BasketItemPosition
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
     * @return BasketItemPosition
     */
    public function setMark(?string $mark): BasketItemPosition
    {
        $this->mark = $mark;
        return $this;
    }

    /**
     * @return Money|null
     */
    public function getPrice(): ?Money
    {
        return $this->price;
    }

    /**
     * @param Money|null $price
     * @return BasketItemPosition
     */
    public function setPrice(?Money $price): BasketItemPosition
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefundType(): ?string
    {
        return $this->refundType;
    }

    /**
     * @param string|null $refundType
     * @return BasketItemPosition
     */
    public function setRefundType(?string $refundType): BasketItemPosition
    {
        $this->refundType = $refundType;
        return $this;
    }

    /**
     * @return Money|null
     */
    public function getRemainingAmount(): ?Money
    {
        return $this->remainingAmount;
    }

    /**
     * @param Money|null $remainingAmount
     * @return BasketItemPosition
     */
    public function setRemainingAmount(?Money $remainingAmount): BasketItemPosition
    {
        $this->remainingAmount = $remainingAmount;
        return $this;
    }

    /**
     * @return Money|null
     */
    public function getAmount(): ?Money
    {
        return $this->amount;
    }

    /**
     * @param Money|null $amount
     * @return BasketItemPosition
     */
    public function setAmount(?Money $amount): BasketItemPosition
    {
        $this->amount = $amount;
        return $this;
    }


    /**
     * @throws OzonPayException
     */
    public function checkData()
    {
        $properties = get_class_vars(get_class($this));
        $isNull = true;
        foreach ($properties as $propertyName => $property) {
            $methodName = 'get'.ucfirst($propertyName);
            if ($this->{$methodName}()!==null) $isNull = false;
        }
        if ($isNull)
            throw new OzonPayException('BasketItemPosition object is empty!');
    }

}