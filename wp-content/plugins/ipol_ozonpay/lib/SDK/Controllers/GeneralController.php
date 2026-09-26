<?php

namespace Ipol\OzonPay\SDK\Controllers;

use Ipol\OzonPay\SDK\Client\CurlAdapter;
use Ipol\OzonPay\SDK\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Exceptions\OzonApiException;
use Ipol\OzonPay\SDK\Exceptions\OzonBadResponseException;
use Ipol\OzonPay\SDK\Exceptions\OzonPayException;
use Ipol\OzonPay\SDK\Other\EncoderInterface;
use Ipol\OzonPay\SDK\Responses\AbstractResponse;
use Ipol\OzonPay\SDK\Responses\ErrorResponse;

class GeneralController
{
    private $adapter;
    private $encoder;
    /**
     * @var array
     */
    private $methodParametersMapping;
    /**
     * @var string
     */
    private $accessKey='';

    /**
     * @var string
     */
    private $secretKey='';
    private $sortFieldName;

    /**
     * @var array
     */
    private $signData = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var AbstractResponse
     */
    private $response;

    public function __construct(CurlAdapter $adapter, array $methodParametersMapping, EncoderInterface $encoder = null)
    {
        $this->adapter = $adapter;
        $this->encoder = $encoder;
        $this->methodParametersMapping = $methodParametersMapping;
    }

    private function prepareData(AbstractEntity $object)
    {
        if ($this->encoder)
            $this->setData($this->encoder->encodeToAPI($object->getAllFields()));
        else
            $this->setData($object->getAllFields());
    }

    /**
     * @param AbstractEntity|null $requestData
     * @return mixed
     * @throws OzonPayException
     * <br><br>
     * Check && run request.<br>
     * Request data can be empty, but if you need to add signature data, you need to use the <b>setSignData</b> method before run.
     */
    public function exec(AbstractEntity $requestData = null)
    {
        if ($requestData) $this->prepareData($requestData);
        $responseClass = 'Ipol\OzonPay\SDK\Responses\\'.ucfirst($this->adapter->getMethod()).'Response';
        try {
            $response = new $responseClass($this->request());
            $response->setSuccess(true);
        } catch (OzonApiException $exception) {
            $response = new ErrorResponse($exception);
            $response->setSuccess(false);
        } catch (OzonBadResponseException $exception) {
            //пока не знаю что тут делать
        }
        $this->setResponse($this->reEncodeResponse($response));
        $this->setFields();
        return $this->getResponse();
    }

    /**
     * @throws OzonPayException
     * @throws OzonApiException
     */
    public function request()
    {
        $this->data['accessKey']=$this->accessKey;
        $this->data['requestSign']=$this->sign();
        switch ($this->adapter->getRequestType()) {
            case 'GET':
                return $this->adapter->get('',$this->getData());
            case 'POST':
                return $this->adapter->post($this->getData());
            case 'PUT':
                return $this->adapter->put($this->getData());
            default:
                throw new OzonPayException('Type request not set!');
        }
    }

    public function sign(): string
    {
        $signParameters = $this->methodParametersMapping[$this->adapter->getMethod()]['SIGN_PARAMS'];
        $resultString = '';
        $values = array_merge($this->signData,$this->data);
        //$stringSplitter='!';
        $stringSplitter='';
        foreach ($signParameters as $parameterName => $parameter)
        {
            $stepValue = '';
            //Work with collections: sorting & getting values
            if (gettype($parameter)=='array') {
                $baseValues = &$values[$parameterName];
                //Check sorting before get values
                foreach ($parameter as $param) {
                    if (strpos($param,'.sort')) { //Sorting collection for this field_name
                        $this->sortFieldName = preg_replace('/.sort/','',$param);
                        usort($baseValues,function ($a,$b){
                            return strcasecmp($a[$this->sortFieldName],$b[$this->sortFieldName]);
                        });
                    }
                }
                //Getting values
                foreach ($baseValues as $item) {
                    foreach ($parameter as $param) {
                        if (strpos($param,'.sort')) $param = preg_replace('/.sort/','',$param);
                        $stepValue.=$item[$param].$stringSplitter;
                    }
                }
                unset($baseValues);
            } else {
                $pathFields = explode('.',$parameter);
                $paramValues = [];
                for ($i=0;$i<=count($pathFields)-1;$i++) {
					if ($i==0) {
						if (isset($values[$pathFields[$i]])) $paramValues[] = $values[$pathFields[$i]];
					} else {
						if (isset($paramValues[count($paramValues)-1][$pathFields[$i]])) $paramValues[] = $paramValues[count($paramValues)-1][$pathFields[$i]];
					}
                }
                if (!empty($paramValues))
					$stepValue = $paramValues[count($paramValues)-1].$stringSplitter;
                unset($paramValues);
            }
            $resultString.=$stepValue;
        }

        $resultString.=$this->secretKey;
        return hash('sha256',$resultString);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return GeneralController
     */
    public function setData(array $data): GeneralController
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $accessKey
     * @return GeneralController
     */
    public function setAccessKey(string $accessKey): GeneralController
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @param string $secretKey
     * @return GeneralController
     */
    public function setSecretKey(string $secretKey): GeneralController
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @param array $signData
     * @return GeneralController
     * <br><br>
     * Add specify param for SIGN data (autosign)
     */
    public function setSignData(array $signData): GeneralController
    {
        $this->signData = $signData;
        return $this;
    }

    public function reEncodeResponse($response)
    {
        if($this->encoder) {
            if(method_exists($response,'getDecoded') && method_exists($response,'setDecoded')) {
                $response->setDecoded($this->encoder->encodeFromAPI($response->getDecoded()));
            }
        }
        return $response;
    }

    /**
     * @return AbstractResponse
     */
    public function getResponse(): AbstractResponse
    {
        return $this->response;
    }

    /**
     * @param AbstractResponse $response
     * @return GeneralController
     */
    public function setResponse(AbstractResponse $response): GeneralController
    {
        $this->response = $response;
        return $this;
    }

    protected function setFields()
    {
        /**@var \Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity $response */
        $response = $this->getResponse();
        if ($response) {
            $response->setFields($response->getDecoded());
        }
    }

}