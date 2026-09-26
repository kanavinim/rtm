<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Exceptions\OzonPayEntitiesCheckException;
use Ipol\OzonPay\SDK\Entity\AbstractEntity;

class OrderInfo extends AbstractEntity
{
    /**
     * @var string|null
     */
    protected $id;
    /**
     * @var string|null
     */
    protected $extId;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return OrderInfo
     */
    public function setId(?string $id): OrderInfo
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtId(): ?string
    {
        return $this->extId;
    }

    /**
     * @param string|null $extId
     * @return OrderInfo
     */
    public function setExtId(?string $extId): OrderInfo
    {
        $this->extId = $extId;
        return $this;
    }

    /**
     * @return void
     * @throws OzonPayEntitiesCheckException
     */
    protected function checkData()
    {
        if (!$this->extId && !$this->id)
            throw new OzonPayEntitiesCheckException('In OrderInfo you must set parameter `Id` or `ExtId`!');
    }

}