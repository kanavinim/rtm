<?php

namespace Ipol\OzonPay\SDK\Entity;

abstract class AbstractEntity
{

    public function getAllFields()
    {
        $this->checkData();
        $properties = get_class_vars(get_class($this));
        $result = [];
        foreach ($properties as $propertyName => $propertyValue) {
            $method = 'get' . ucfirst($propertyName);
            //if method get boolean value...
            $methodBool = 'is' . ucfirst($propertyName);
            if (method_exists($this, $method) && is_callable([$this, $method])) {
                $value = $this->{$method}();
                if ($value instanceof self) $value = $value->getAllFields();
                //Special for Expires DateTime
                if ($value instanceof \Ipol\OzonPay\SDK\Entity\DateTime) {
                    if ($propertyName === 'expiresAt') $value = $value->getFormattedIncreasedHour();
                    else $value = $value->getFormatted();
                }
                if ($value instanceof AbstractEntityCollection) {
                    $value = $value->getAllFields();
                }
                $result[$propertyName] = $value;
            }
            if (method_exists($this, $methodBool) && is_callable([$this, $methodBool])) {
                $result[$propertyName] = $this->{$methodBool}();
            }
            if ($result[$propertyName] === null) unset($result[$propertyName]);
        }
        return $result;
    }

    /**
     * In your entities object you can set custom checker before it sended into API
     * @return void
     * @throws OzonPayEntitiesCheckException
     */
    protected function checkData()
    {

    }
}