<?php

namespace Ipol\OzonPay\SDK\Client;

use Ipol\OzonPay\SDK\Logger\Logger;
use Ipol\OzonPay\SDK\Exceptions\OzonPayException;
use Ipol\OzonPay\SDK\Exceptions\OzonApiException;

class CurlAdapter
{

    /**
     * @var ApiClient
     */
    protected $curl;
    /**
     * @var array
     */
    private $allowedCodeArr;
    /**
     * @var array
     */
    private $validErrorCodeArr;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $requestType;
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var string
     */
    protected $contentType = 'Content-Type: application/json; charset=utf-8';

    protected $logfile = '';

    protected $logger;

    private $doNotCloseConnectionAfterRequest;

    /**
     * @var string
     */
    protected $method = 'unconfiguredrequest';

    /**
     * CurlAdapter constructor.
     * @param int $timeout
     * @throws Ipol\OzonPay\SDK\Exceptions\OzonPayException if curl not installed
     * @throws OzonPayException
     */
    public function __construct(int $timeout = 15, string $logfile, array $config, string $clientType = 'Curl')
    {
        $this->allowedCodeArr = [200];
        //$this->validErrorCodeArr = [302, 400, 401, 404, 500];
        $this->logfile = $logfile;
        if ($logfile!=='') $this->logger = new Logger($logfile);
	    $clientType = '\Ipol\OzonPay\SDK\Client\\'.$clientType;
		if (!class_exists($clientType))
	        throw new OzonPayException('Error: No Client registered ['.$clientType.']');
		$this->curl = new $clientType($timeout,$config);
        $this->doNotCloseConnectionAfterRequest = false;
    }

    /**
     * @param array $dataPost
     * @param string $urlImplement
     * @param array $dataGet
     * @return mixed
     */
    /*public function form(array $dataPost = [], string $urlImplement = "", array $dataGet = [])
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);

        $getStr = (!empty($dataGet))? "?" . http_build_query($dataGet) : "";

        $this->curl->setUrl($this->getUrl() . $urlImplement . $getStr);

        $this->applyHeaders()->curl->post(http_build_query($dataPost));

        return $this->curl->getAnswer();
    }*/

    /**
     * @param array $dataPost
     * @param string $urlImplement
     * @param array $dataGet
     * @return mixed
     * @throws OzonApiException
     */
    public function post(array $dataPost = [], string $urlImplement = "", array $dataGet = [])
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);

        $getStr = (!empty($dataGet))? "?" . http_build_query($dataGet,'','&') : "";
        if ($this->logger) $this->logger->log('info','Post_Request'.PHP_EOL.'URL: '.$this->getUrl().$getStr.PHP_EOL.'PostData: ',$dataPost);
        $this->curl->setUrl($this->getUrl() . $urlImplement . $getStr);
        $this->applyHeaders()->curl->post(json_encode($dataPost, JSON_UNESCAPED_UNICODE),$this->doNotCloseConnectionAfterRequest);
        $this->doNotCloseConnectionAfterRequest = false;
        if ($this->logger) $this->logger->log('info','Post_Response: '.PHP_EOL.'CODE: '.$this->curl->getCode().PHP_EOL.$this->curl->getAnswer());
        $this->afterCheck($dataPost);
        return $this->curl->getAnswer();
    }

    /**
     * @param string $urlImplement
     * @param array $dataGet
     * @return mixed
     * @throws OzonApiException
     */
    public function get(string $urlImplement = "", array $dataGet = [])
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);

        $this->curl->setUrl($this->getUrl() . $urlImplement);

        $getStr = empty($dataGet) ? '' : '?' . http_build_query($dataGet,'','&'); //only for logging, imitating inner curl.php process
        if ($this->logger) $this->logger->log('info','GET_Request: '.$this->getUrl().$urlImplement.$getStr,$dataGet);
        $this->applyHeaders(false)->curl->get($dataGet);
        if ($this->logger) $this->logger->log('info','GET_Response: '.PHP_EOL.'CODE: '.$this->curl->getCode().PHP_EOL.$this->curl->getAnswer());
        $this->afterCheck('get request');
        return $this->curl->getAnswer();
    }

    /**
     * @param array $dataPut
     * @param string $urlImplement
     * @param array $dataGet
     * @return mixed
     * @throws OzonApiException
     */
    public function put(array $dataPut = [], string $urlImplement = "", array $dataGet = [])
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $getStr = (!empty($dataGet))? "?" . http_build_query($dataGet,'','&') : "";
        $this->curl->setUrl($this->getUrl() . $urlImplement . $getStr);
        if ($this->logger) $this->logger->log('info','PUT_Request: '.$this->getUrl().$urlImplement,[$dataPut,$dataGet]);
        $this->applyHeaders()->curl->put(json_encode($dataPut,JSON_UNESCAPED_UNICODE));
        if ($this->logger) $this->logger->log('info','PUT_Response: '.PHP_EOL.'CODE: '.$this->curl->getCode().PHP_EOL.$this->curl->getAnswer());
        $this->afterCheck('put request');
        return $this->curl->getAnswer();
    }

    /**
     * @param string $urlImplement
     * @return mixed
     */
    /*public function delete(string $urlImplement = "")
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);

        $this->applyHeaders(false)->curl->setUrl($this->getUrl() . $urlImplement);

        $this->curl->delete();

        return $this->curl->getAnswer();
    }*/

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return CurlAdapter
     */
    public function setUrl(string $url): CurlAdapter
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestType(): ?string
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     * @return CurlAdapter
     */
    public function setRequestType(string $requestType): CurlAdapter
    {
        $this->requestType = $requestType;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return CurlAdapter
     */
    public function setMethod(string $method): CurlAdapter
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType(string $contentType): CurlAdapter
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @param array $headers
     * @return CurlAdapter
     */
    public function appendHeaders(array $headers): CurlAdapter
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * @return $this
     */
    protected function applyHeaders($doAppendHeaders = true): CurlAdapter
    {
        if ($doAppendHeaders) {
            $this->appendHeaders([$this->contentType]);
        }
        $this->curl->config([CURLOPT_HTTPHEADER => $this->headers]);
        return $this;

    }

    /**
     * @return ApiClient
     */
    public function getCurl(): ApiClient
    {
        return $this->curl;
    }

    /**
     * @param false $doNotCloseConnectionAfterRequest
     * @return CurlAdapter
     */
    public function setDoNotCloseConnectionAfterRequest(bool $doNotCloseConnectionAfterRequest): CurlAdapter
    {
        $this->doNotCloseConnectionAfterRequest = $doNotCloseConnectionAfterRequest;
        return $this;
    }

    /**
     * @param $sentData
     * @throws OzonApiException
     * @throws OzonPayException
     */
    protected function afterCheck($sentData): void
    {
        if ($this->curl->getCurlErrNum() == CURLE_OPERATION_TIMEDOUT) {
            throw new OzonPayException('OzonPay: Connection timed out', $this->curl->getCurlErrNum());
        }
        if (!in_array($this->curl->getCode(), $this->allowedCodeArr)) {
            throw new OzonApiException(
                'Request error',
                $this->curl->getCode(),
                $this->curl->getUrl(),
                $sentData,
                $this->curl->getAnswer()
            );
        }
    }

}
