<?php

namespace Ipol\OzonPay\SDK\Responses;

class OrderDetailsResponse extends AbstractResponse
{

    protected $item;

    protected $ext_data;

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     * @return OrderDetailsResponse
     */
    public function setItem($item)
    {
        $this->item = (new \Ipol\OzonPay\SDK\Responses\Entity\OrderDetailsItemEntity())->setFields($item);
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
     * @return OrderDetailsResponse
     */
    public function setExtData($ext_data)
    {
        $this->ext_data = $ext_data;
        return $this;
    }

}