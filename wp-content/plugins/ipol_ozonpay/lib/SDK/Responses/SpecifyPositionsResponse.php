<?php

namespace Ipol\OzonPay\SDK\Responses;

use Ipol\OzonPay\SDK\Responses\Entity\Parts\ProductPositionsCollection;

class SpecifyPositionsResponse extends AbstractResponse
{
    /**
     * @var ProductPositionsCollection
     */
    protected $items;

    /**
     * @return ProductPositionsCollection
     */
    public function getItems(): ProductPositionsCollection
    {
        return $this->items;
    }

    /**
     * @param ProductPositionsCollection $items
     * @return SpecifyPositionsResponse
     */
    public function setItems($items): SpecifyPositionsResponse
    {
        $this->items = (new ProductPositionsCollection())->fillFromArray($items);
        return $this;
    }

}