<?php

namespace Ipol\OzonPay\SDK;

use Ipol\OzonPay\SDK\Client\CurlAdapter;
use Ipol\OzonPay\SDK\Controllers\GeneralController;
use Ipol\OzonPay\SDK\Entity\BasketItemCollection;
use Ipol\OzonPay\SDK\Request\CancelOrder;
use Ipol\OzonPay\SDK\Request\Confirm;
use Ipol\OzonPay\SDK\Entity\DateTime;
use Ipol\OzonPay\SDK\Request\FinalReceipt;
use Ipol\OzonPay\SDK\Request\NewOrderData;
use Ipol\OzonPay\SDK\Request\OrderInfo;
use Ipol\OzonPay\SDK\Request\Refund;
use Ipol\OzonPay\SDK\Entity\Settings;
use Ipol\OzonPay\SDK\Request\SpecifyPositions;
use Ipol\OzonPay\SDK\Exceptions\OzonPayException;

class OzonPay
{
    private const URL_MAP = [
        'createOrder'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/createOrder',
            'SIGN_PARAMS'=>[
                'accessKey',
                'expiresAt',
                'extId',
                'fiscalizationType',
                'paymentAlgorithm',
                'amount.currencyCode',
                'amount.value'
            ]
        ],
        'orderDetails'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/getOrderDetails',
            'SIGN_PARAMS'=>[
                'id',
                'extId',
                'accessKey'
            ]
        ],
        'orderStatus'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/getOrderStatus',
            'SIGN_PARAMS'=>[
                'id',
                'extId',
                'accessKey'
            ]
        ],
        'cancelOrder'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/cancelOrder',
            'SIGN_PARAMS'=>[
                'id',
                'accessKey'
            ]
        ],
        'confirmOrder'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/confirmOrder',  // /v1/orders/{id}/confirm
            'SIGN_PARAMS'=>[
                'id',
                'accessKey',
                'amount.currencyCode',
                'amount.value'
            ]
        ],
        'specifyPositions'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/updateOrderPositions',
            'SIGN_PARAMS'=>[
                'id',
                'accessKey',
                'positions'=>[
                    'id.sort',//массив должен быть отсортирован по id
                    'mark'
                ]
            ]
        ],
        'sendFinalReceipt'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/createFinalReceipt', // /v1/orders/{id}/final-receipt
            'SIGN_PARAMS'=>[
                'id',
                'accessKey',
                'positions'=>[
                    'id.sort',//массив должен быть отсортирован по id
                    'mark'
                ]
            ]
        ],
        'refund'=>[
            'REQUEST_TYPE'=>'POST',
            'URL'=>'/v1/refundOrder', // /v1/refundOrder/{id}/refund
            'SIGN_PARAMS'=>[
                'id',
                'accessKey',
                'amount.currencyCode',
                'amount.value'
            ]
        ],
    ];

    private $settings;
    private $controller;
    private $adapter;
    private $doNotCloseConnectionAfterRequest;
    private $urlParameters=[];
    private $sortFieldName;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->doNotCloseConnectionAfterRequest = false;

        $this->adapter = new CurlAdapter($this->settings->getTimeout(),$this->settings->getLogFile(),[],$this->settings->getClientType());

        $this->controller = (new GeneralController(
            $this->adapter,
            self::URL_MAP,
            $this->settings->getEncoder()
        ))
        ->setSecretKey($this->settings->getSecretKey())
        ->setAccessKey($this->settings->getAccessKey());
    }

    /**
     * @param $methodName
     * @param array $urlParameters
     * @return void
     * @throws OzonPayException
     *
     * <br><br>
     * <b>Use this function first in functions!<b><br><br>
     */
    private function prepareRequest($methodName, array $urlParameters = [])
    {
        if (!array_key_exists($methodName,self::URL_MAP))
            throw new OzonPayException('Method '.$methodName.' not found in module SDK!');
        $apiUrl = self::URL_MAP[$methodName]['URL'];
        foreach ($urlParameters as $key => $value)
            $apiUrl = preg_replace("/{{$key}}/",$value,$apiUrl);
        $this->adapter->setUrl( $this->settings->getApiUrl().$apiUrl )
            ->setRequestType(self::URL_MAP[$methodName]['REQUEST_TYPE'])
            ->setMethod($methodName)
            ->setDoNotCloseConnectionAfterRequest($this->doNotCloseConnectionAfterRequest);
        $this->doNotCloseConnectionAfterRequest = false;
        $this->controller->setSignData([]); //Clearing
    }

    /**
     * @throws OzonPayException
     */
    public function createOrder(NewOrderData $newOrderData)
    {
        $this->prepareRequest(__FUNCTION__);
        //Set default values
        if ($newOrderData->getEnableFiscalization()===null)
            $newOrderData->setEnableFiscalization($this->settings->isEnableFiscalization());
        if ($newOrderData->getFiscalizationTypeValue()===null)
            $newOrderData->setFiscalizationType($this->settings->getFiscalizationType());
        if ($newOrderData->getPaymentAlgorithmValue()===null)
            $newOrderData->setPaymentAlgorithm($this->settings->getPaymentAlgorithm());
        if ($newOrderData->getExpiresAt()===null)
            $newOrderData->setExpiresAt(new DateTime());
        //Set Default Values in Items Collection
        $editedCollection = new BasketItemCollection();
        $newOrderData->getItems()->reset();
        while ($item = $newOrderData->getItems()->getNext()) {
            if ($item->getNeedMark()===null)
                $item->setNeedMark($this->settings->isDefaultNeedMark());
            if ($item->getUnitTypeValue()==null)
                $item->setUnitType($this->settings->getDefaultMeasure());
            if ($item->getTypeValue()===null)
                $item->setType($this->settings->getDefaultType());
            if ($item->getVatValue()===null)
                $item->setVat($this->settings->getDefaultTax());
            $editedCollection->add($item);
        }
        $newOrderData->setItems($editedCollection);
        return $this->controller->exec($newOrderData);
    }

    /**
     * @throws OzonPayException
     */
    public function orderDetails(OrderInfo $orderInfo)
    {
        $this->prepareRequest(__FUNCTION__);
        return $this->controller->exec($orderInfo);
    }

    /**
     * @throws OzonPayException
     */
    public function orderStatus(OrderInfo $orderInfo)
    {
        $this->prepareRequest(__FUNCTION__);
        return $this->controller->exec($orderInfo);
    }

    /**
     * @throws OzonPayException
     */
    public function cancelOrder(string $bankOrderId)
    {
        $this->prepareRequest(__FUNCTION__);
        $this->controller->setSignData(['id'=>$bankOrderId]);
        return $this->controller->exec((new CancelOrder())->setId($bankOrderId));
    }

    /**
     * @throws OzonPayException
     */
    public function confirmOrder(Confirm $confirmData)
    {
        $this->prepareRequest(__FUNCTION__);
        //$this->prepareRequest(__FUNCTION__,['id'=>$confirmData->getId()]);
        //$this->controller->setSignData(['id'=>$confirmData->getId()]);
        return $this->controller->exec($confirmData);
    }

    /**
     * @throws OzonPayException
     */
    public function specifyPositions(SpecifyPositions $specifyPositions)
    {
        $this->prepareRequest(__FUNCTION__);
        //$this->prepareRequest(__FUNCTION__,['id'=>$specifyPositions->getId()]);
        //$this->controller->setSignData(['id'=>$specifyPositions->getId()]);
        return $this->controller->exec($specifyPositions);
    }

    /**
     * @throws OzonPayException
     */
    public function sendFinalReceipt(FinalReceipt $finalReceipt)
    {
        $this->prepareRequest(__FUNCTION__);
        //$this->prepareRequest(__FUNCTION__,['id'=>$finalReceipt->getId()]);
        //$this->controller->setSignData(['id'=>$finalReceipt->getId()]);
        return $this->controller->exec($finalReceipt);
    }

    /**
     * @throws OzonPayException
     */
    public function refund(Refund $refundData)
    {
        $this->prepareRequest(__FUNCTION__);
        //$this->prepareRequest(__FUNCTION__,['id'=>$refundData->getId()]);
        //$this->controller->setSignData(['id'=>$refundData->getId()]);
        return $this->controller->exec($refundData);
    }

    /**
     * @param false $doNotCloseConnectionAfterRequest
     * @return OzonPay
     */
    public function setDoNotCloseConnectionAfterRequest(bool $doNotCloseConnectionAfterRequest): OzonPay
    {
        $this->doNotCloseConnectionAfterRequest = $doNotCloseConnectionAfterRequest;
        return $this;
    }

}





