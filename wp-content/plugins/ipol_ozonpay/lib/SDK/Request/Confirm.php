<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\BasketItemPositionCollection;
use Ipol\OzonPay\SDK\Entity\Money;
use Ipol\OzonPay\SDK\Exceptions\OzonPayEntitiesCheckException;
use Ipol\OzonPay\SDK\Entity\AbstractEntity;

class Confirm extends AbstractEntity
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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Confirm
     */
    public function setId(string $id): Confirm
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
     * @return Confirm
     */
    public function setAmount(Money $amount): Confirm
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
     * @return Confirm
     */
    public function setPositions(BasketItemPositionCollection $positions): Confirm
    {
        $this->positions = $positions;
        return $this;
    }

    /**
     * @return void
     * @throws OzonPayEntitiesCheckException
     */
    public function checkData()
    {
        if (!$this->positions || ($this->positions->getQuantity()==0))
            throw new OzonPayEntitiesCheckException('Confirm positions is empty!');
    }

}