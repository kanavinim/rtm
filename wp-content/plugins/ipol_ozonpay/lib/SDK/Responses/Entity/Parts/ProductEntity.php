<?php

namespace Ipol\OzonPay\SDK\Responses\Entity\Parts;

use Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity;

class ProductEntity extends AbstractEntity
{
    /**
     * @var string
     */
    protected $ext_id;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var float
     */
    protected $quantity;
    /**
     * @var bool
     */
    protected $need_mark;
    /**
     * @var string
     */
    protected $unitType;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $vat;
    /**
     * @var MoneyEntity
     */
    protected $price;
    /**
     * @var ProductPositionsCollection
     */
    protected $positions;

    /**
     * @return string
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @param string $ext_id
     * @return ProductEntity
     */
    public function setExtId(string $ext_id): ProductEntity
    {
        $this->ext_id = $ext_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ProductEntity
     */
    public function setId(string $id): ProductEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProductEntity
     */
    public function setName(string $name): ProductEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return ProductEntity
     */
    public function setQuantity(float $quantity): ProductEntity
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNeedMark(): bool
    {
        return $this->need_mark;
    }

    /**
     * @param bool $need_mark
     * @return ProductEntity
     */
    public function setNeedMark(bool $need_mark): ProductEntity
    {
        $this->need_mark = $need_mark;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitType(): string
    {
        return $this->unitType;
    }

    /**
     * @param string $unitType
     * @return ProductEntity
     */
    public function setUnitType(string $unitType): ProductEntity
    {
        $this->unitType = $unitType;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ProductEntity
     */
    public function setType(string $type): ProductEntity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getVat(): string
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     * @return ProductEntity
     */
    public function setVat(string $vat): ProductEntity
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return MoneyEntity
     */
    public function getPrice(): MoneyEntity
    {
        return $this->price;
    }

    /**
     * @param $price
     * @return ProductEntity
     */
    public function setPrice($price): ProductEntity
    {
        $this->price = (new MoneyEntity())->setFields($price);
        return $this;
    }

    /**
     * @return ProductPositionsCollection
     */
    public function getPositions(): ProductPositionsCollection
    {
        return $this->positions;
    }

    /**
     * @param ProductPositionsCollection $positions
     * @return ProductEntity
     */
    public function setPositions($positions): ProductEntity
    {
        $this->positions = (new ProductPositionsCollection())->fillFromArray($positions);
        return $this;
    }

}