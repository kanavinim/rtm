<?php

namespace Ipol\OzonPay\SDK\Entity;

use Ipol\OzonPay\SDK\Other\Types;

/**
 * Mapping Values Include!
 */
class BasketItem extends AbstractEntity
{
    /**
     * @var string
     */
    protected $extId;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool|null
     * <br>
     * This property supports autocompletion!
     */
    protected $needMark;
    /**
     * @var Money
     */
    protected $price;
    /**
     * @var double
     */
    protected $quantity;
    /**
     * @var int|null
     * <br>
     * This property supports autocompletion!
     */
    protected $type;
    /**
     * @var int|null
     * <br>
     * This property supports autocompletion!
     */
    protected $unitType;
    /**
     * @var int|null
     * <br>
     * This property supports autocompletion!
     */
    protected $vat;

    /**
     * @var BasketItemPositionCollection|null
     */
    protected $positions;

    /**
     * @return string
     */
    public function getExtId(): string
    {
        return $this->extId;
    }
    /**
     * @param string $extId
     * @return BasketItem
     */
    public function setExtId(string $extId): BasketItem
    {
        $this->extId = $extId;
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
     * @return BasketItem
     */
    public function setName(string $name): BasketItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getNeedMark(): ?bool
    {
        return $this->needMark;
    }
    /**
     * @param bool|null $needMark
     * @return BasketItem
     * <br>
     * This property supports autocompletion!
     */
    public function setNeedMark(?bool $needMark): BasketItem
    {
        $this->needMark = $needMark;
        return $this;
    }

    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }
    /**
     * @param Money $price
     * @return BasketItem
     */
    public function setPrice(Money $price): BasketItem
    {
        $this->price = $price;
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
     * @return BasketItem
     */
    public function setQuantity(float $quantity): BasketItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Types::getItemType($this->type);
    }
    /**
     * @return int|null
     */
    public function getTypeValue(): ?int
    {
        return $this->type;
    }
    /**
     * @param int|null $type
     * @return BasketItem
     * <br>
     * This property supports autocompletion!
     */
    public function setType(?int $type): BasketItem
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitType(): string
    {
        return Types::getItemMeasureType($this->unitType);
    }
    /**
     * @return int|null
     */
    public function getUnitTypeValue(): ?int
    {
        return $this->unitType;
    }
    /**
     * @param int|null $unitType
     * @return BasketItem
     * <br>
     * This property supports autocompletion!
     */
    public function setUnitType(?int $unitType): BasketItem
    {
        $this->unitType = $unitType;
        return $this;
    }

    /**
     * @return string
     */
    public function getVat(): string
    {
        return Types::getTaxValue($this->vat);
    }
    /**
     * @return int|null
     */
    public function getVatValue(): ?int
    {
        return $this->vat;
    }
    /**
     * @param int|null $vat
     * @return BasketItem
     * <br>
     * This property supports autocompletion!
     */
    public function setVat(?int $vat): BasketItem
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return BasketItemPositionCollection|null
     */
    public function getPositions(): ?BasketItemPositionCollection
    {
        return $this->positions;
    }

    /**
     * @param BasketItemPositionCollection|null $positions
     * @return BasketItem
     */
    public function setPositions(?BasketItemPositionCollection $positions): BasketItem
    {
        $this->positions = $positions;
        return $this;
    }

}