<?php

namespace Ipol\OzonPay\SDK\Entity;

abstract class AbstractEntityCollection
{

    protected $index;

    protected $items;

    public function __construct()
    {
        $this->items = [];
        $this->index = 0;
    }

    public function reset()
    {
        $this->index = 0;
        return $this;
    }

    public function add(AbstractEntity $something)
    {
        array_push($this->items, $something);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNext()
    {
        if (count($this->items)<($this->index+1)) return false;
        return $this->items[$this->index++];
    }

    /**
     * @return mixed
     */
    public function getFirst()
    {
        if (count($this->items)==0) return false;
        return $this->items[0];
    }

    public function getLast()
    {
        if(!$counter = count($this->items)) return false;
        return $this->items[$counter - 1];
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return count($this->items);
    }

    public function getAllFields()
    {
        $result = [];
        $this->reset();
        while ($element = $this->getNext()) {
            $result[] = $element->getAllFields();
        }
        return $result;
    }

}