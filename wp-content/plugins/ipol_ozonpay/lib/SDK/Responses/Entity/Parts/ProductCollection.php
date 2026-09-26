<?php

namespace Ipol\OzonPay\SDK\Responses\Entity\Parts;

use Ipol\OzonPay\SDK\Responses\Entity\AbstractCollection;

class ProductCollection extends AbstractCollection
{
    /**
     * @var array of ProductEntity
     */
    protected $products;

    public function __construct()
    {
        parent::__construct('products');
        $this->setChildClass('Ipol\OzonPay\SDK\Responses\Entity\Parts\ProductEntity');
    }
}