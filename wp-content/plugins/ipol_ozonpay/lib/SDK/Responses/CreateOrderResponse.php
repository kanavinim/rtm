<?php

namespace Ipol\OzonPay\SDK\Responses;


use Ipol\OzonPay\SDK\Responses\Entity\OrderDetailsItemEntity;

class CreateOrderResponse extends AbstractResponse
{

    protected $order;
    protected $ext_data;

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return CreateOrderResponse
     */
    public function setOrder($order)
    {
        $this->order = (new OrderDetailsItemEntity())->setFields($order);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtData()
    {
        return $this->ext_data;
    }

    /**
     * @param mixed $ext_data
     * @return CreateOrderResponse
     */
    public function setExtData($ext_data)
    {
        $this->ext_data = $ext_data;
        return $this;
    }

}