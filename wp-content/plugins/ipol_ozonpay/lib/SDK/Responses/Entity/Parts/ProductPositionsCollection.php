<?php

namespace Ipol\OzonPay\SDK\Responses\Entity\Parts;

use Ipol\OzonPay\SDK\Responses\Entity\AbstractCollection;

class ProductPositionsCollection extends AbstractCollection
{
    /**
     * @var array of ProductPositions
     */
    protected $positions;

    public function __construct()
    {
        parent::__construct('positions');
        $this->setChildClass('\Ipol\OzonPay\SDK\Responses\Entity\Parts\ProductPositionEntity');
    }
}