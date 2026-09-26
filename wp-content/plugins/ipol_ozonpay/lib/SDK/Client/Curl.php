<?php

namespace Ipol\OzonPay\SDK\Client;

use Ipol\OzonPay\SDK\Exceptions\OzonPayException;

class Curl implements ApiClient
{

    /**
     * @var null | resource
     */
    private $client;

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var mixed
     */
    private $answer;
    /**
     * @var null|int
     */
    private $code;
    /**
     * @var int
     */
    private $curlErrNum = 0;
    /**
     * @var array
     */
    private $arrResponseHeaders = [];


    /**
     * @throws OzonPayException
     */
    public function __construct(int $timeout = 15, array $config = [])
    {
        if (!function_exists('curl_init')) {
            throw new OzonPayException('No CURL library');
        }
        $this->client = curl_init();
        if ($config) {
            $this->config($config);
        }
		$this->setOpt(CURLOPT_TIMEOUT_MS, $timeout * 1000);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function config(array $args)
    {
        curl_setopt_array($this->client, $args);
        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function post(string $data = '', bool $doNotClose = false)
    {
        $this->setOpt(CURLOPT_POST, TRUE);
        if ($data) {
            $this->setOpt(CURLOPT_POSTFIELDS, $data);
        }
        //curl_setopt($this->client, CURLOPT_HEADERFUNCTION, [$this, 'responseHeaderParser']);
        $this->request($doNotClose);
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function get(array $data = [])
    {
        if ($data) {
            if (strpos($this->url, '?') !== false) {
                $this->url = substr($this->url, 0, strpos($this->url, '?'));
            }
            $this->url .= '?' . http_build_query($data,'','&');
        }
        $this->request();

        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function put(string $data = '')
    {
        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            $this->setOpt(CURLOPT_POSTFIELDS, $data);
        }
        $this->request();

        return $this;
    }

    /**
     * @return $this
     */
    public function delete(): curl
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");

        $this->request();

        return $this;
    }

    /**
     * @param int $opt
     * @param mixed $val
     * @return $this
     */
    public function setOpt(int $opt, $val)
    {
        curl_setopt($this->client, $opt, $val);

        return $this;
    }

    /**
     * @param bool $close
     * @return $this
     */
    private function request(bool $doNotClose = false)
    {
        $this->setOpt(CURLOPT_URL, $this->url);
        $this->answer = curl_exec($this->client);
        $this->code = curl_getinfo($this->client, CURLINFO_HTTP_CODE);
        if ($this->code === 0) {
            $this->curlErrNum = curl_errno($this->client);
        }
        if (!$doNotClose) {
            $this->flee();
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function flee(): curl
    {
        if ($this->client) {
            curl_close($this->client);
        }
        return $this;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getCurlErrNum(): int
    {
        return $this->curlErrNum;
    }


}
