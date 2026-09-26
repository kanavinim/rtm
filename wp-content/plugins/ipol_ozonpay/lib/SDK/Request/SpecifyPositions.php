<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Entity\BasketItemPositionCollection;

class SpecifyPositions extends AbstractEntity
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
     * @return SpecifyPositions
     */
    public function setId(string $id): SpecifyPositions
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
     * @return SpecifyPositions
     */
    public function setPositions(BasketItemPositionCollection $positions): SpecifyPositions
    {
        $this->positions = $positions;
        return $this;
    }

}