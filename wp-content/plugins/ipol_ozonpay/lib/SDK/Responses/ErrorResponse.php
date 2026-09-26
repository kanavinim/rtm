<?php
namespace Ipol\OzonPay\SDK\Responses;

use Ipol\OzonPay\SDK\Exceptions\OzonApiException;
use Ipol\OzonPay\SDK\Responses\Entity\Error;

class ErrorResponse extends AbstractResponse
{
    private $requestUrl;
    private $requestObject;

    private $errorCode;
    private $errorMessage;

    public function __construct(OzonApiException $exception/*$request='',$response='',$errCode=0,$requestUrl=''*/)
    {
        parent::__construct($exception->getResponse());
        // Про это я пока не уверен но какой-то "entity" со стандартными полями быть должен, как вариант - добавить эти поля сюда
        //$this->responseObject = new Error();
        //$this->responseObject->setFields($this->decoded);
        //$this->responseObject->setErrorCode($errCode);

        $this->requestUrl = $exception->getUrl();
        $this->requestObject = $exception->getRequest();
        $this->errorCode = $exception->getCode();
        $this->errorMessage = $exception->getMessage();
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * @return false|mixed
     */
    public function getRequestObject()
    {
        return $this->requestObject;
    }

    /**
     * @return int|mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    /*public function getRequestObject()
    {
        return $this->requestObject;
    }*/

}