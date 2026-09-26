<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\AbstractEntity;

class CancelOrder extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CancelOrder
     */
    public function setId(string $id): CancelOrder
    {
        $this->id = $id;
        return $this;
    }


}