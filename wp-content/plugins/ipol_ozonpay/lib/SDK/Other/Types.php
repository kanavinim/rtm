<?php

namespace Ipol\OzonPay\SDK\Other;

class Types
{
    private const ERROR_VAL = 'Error';

    private const FISCAL_TYPES = [
        'FISCAL_TYPE_UNSPECIFIED',  //0
        'FISCAL_TYPE_SINGLE',       //1
        'FISCAL_TYPE_DOUBLE'        //2
    ];

    private const PAY_ALGOS = [
        'PAY_ALGO_UNSPECIFIED', //0
        'PAY_ALGO_SMS',         //1
        'PAY_ALGO_DMS'          //2
    ];

    private const TYPES = [
        'TYPE_UNSPECIFIED', //0
        'TYPE_PRODUCT',     //1
        'TYPE_SERVICE'      //2
    ];

    private const MEASURE_TYPES = [
        'UNIT_UNSPECIFIED', //0
        'UNIT_PIECE',       //1
        'UNIT_KILO',        //2
        'UNIT_LITER'        //3
    ];

    private const TAX_VALUES = [
        'VAT_UNSPECIFIED',  //0
        'VAT_0',            //1
        'VAT_10',           //2
        'VAT_20',           //3
        'VAT_10_110',       //4
        'VAT_20_120',       //5
        'VAT_NONE',          //6
        'VAT_5',            //7
        'VAT_7',            //8
    ];

    protected const ORDER_STATUSES = [
        'STATUS_NEW',                   //0 новый
        'STATUS_PAYMENT_PENDING',       //1 ожидает оплаты
        'STATUS_EXPIRED',               //2 истек срок действия
        'STATUS_CANCELED',              //3 отменен
        'STATUS_AUTHORIZED',            //4 авторизован - 2хстадийный платеж
        'STATUS_PARTITION_CANCELED',    //5 частично возвращен
        'STATUS_PAID',                  //6 оплачен, 2хстадийная оплата подтверждена
        'STATUS_PARTITIONAL_REFUND',    //7 частично вовзращен
        'STATUS_REFUNDED',              //8 Возвращен
        'STATUS_DISPUTED'               //9 спор
    ];

    protected const ORDER_STATUSES_RUS = [
        'Новый',
        'Ожидает оплаты',
        'Истек срок действия',
        'Отменен',
        'Авторизован',
        'Частично возвращен',
        'Оплачен',
        'Частично возвращен',
        'Возвращен',
        'Спор'
    ];

    protected const REFUND_STATUSES = [
        'TYPE_UNSPECIFIED', //0 - Не было возврата
        'TYPE_FULL',        //1 - Полный возврат
        'TYPE_PARTIAL',     //2 - Частичный
        'TYPE_RESTRICTED'   //3 - Возврат недоступен
    ];

    //Not for BITRIX!
    protected const REFUND_STATUSES_RUS = [
        'Не возвращен',//'Не было возврата',
        'Полный возврат',
        'Частичный',
        'Возврат недоступен'
    ];

    /**
     * @param int $code
     * @return string
     */
    static public function getFiscalizationType(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::FISCAL_TYPES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getFiscalizationTypeCode(string $value): int
    {
        foreach (self::FISCAL_TYPES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getPaymentAlgorithm(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::PAY_ALGOS[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getPaymentAlgorithmCode(string $value): int
    {
        foreach (self::PAY_ALGOS as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getItemType(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::TYPES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getItemTypeCode(string $value): int
    {
        foreach (self::TYPES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getItemMeasureType(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::MEASURE_TYPES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getItemMeasureTypeCode(string $value): int
    {
        foreach (self::MEASURE_TYPES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getTaxValue(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::TAX_VALUES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getTaxValueCode(string $value): int
    {
        foreach (self::TAX_VALUES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getOrderStatus(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::ORDER_STATUSES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getOrderStatusCode(string $value): int
    {
        foreach (self::ORDER_STATUSES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     * <b><u>This method is not recommended to be used in Bitrix</u></b>
     */
    static public function getOrderStatusRus(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::ORDER_STATUSES_RUS[$code];
    }

    /**
     * @param string $code
     * @return string
     * <b><u>This method is not recommended to be used in Bitrix</u></b>
     */
    static public function getOrderStatusRusByCode(string $code): string
    {
        foreach (self::ORDER_STATUSES as $key => $val)
            if ($code == $val) return self::ORDER_STATUSES_RUS[$key];
        return '';
    }

    /**
     * @param int $code
     * @return string
     */
    static public function getRefundStatus(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::REFUND_STATUSES[$code];
    }

    /**
     * @param string $value
     * @return int
     */
    static public function getRefundStatusCode(string $value): int
    {
        foreach (self::REFUND_STATUSES as $key => $val)
            if ($value == $val) return $key;
        return -1;
    }

    /**
     * @param int $code
     * @return string
     * <b><u>This method is not recommended to be used in Bitrix</u></b>
     */
    static public function getRefundStatusRus(int $code = 0): string
    {
        if ($code == -1) return self::ERROR_VAL;
        return self::REFUND_STATUSES_RUS[$code];
    }

    /**
     * @param string $code
     * @return string
     * <b><u>This method is not recommended to be used in Bitrix</u></b>
     */
    static public function getRefundStatusRusByCode(string $code): string
    {
        foreach (self::REFUND_STATUSES as $key => $val)
            if ($code == $val) return self::REFUND_STATUSES_RUS[$key];
        return '';
    }

}