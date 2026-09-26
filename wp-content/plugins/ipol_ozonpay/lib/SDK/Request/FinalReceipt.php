<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Entity\BasketItemPositionCollection;

class FinalReceipt extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;

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
     * @return FinalReceipt
     */
    public function setId(string $id): FinalReceipt
    {
        $this->id = $id;
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
     * @return FinalReceipt
     */
    public function setPositions(BasketItemPositionCollection $positions): FinalReceipt
    {
        $this->positions = $positions;
        return $this;
    }

}