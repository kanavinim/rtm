<?php

namespace Ipol\OzonPay\SDK\Entity;

use Ipol\OzonPay\SDK\Other\EncoderInterface;

/**
 * Settings with default values
 * <br>
 * You must set all settings when using SDK
 */
class Settings
{

    /**
     * @var string
     */
    private $accessKey;
    /**
     * @var string
     */
    private $secretKey;
    /**
     * @var string
     */
    private $apiUrl = '';
    /**
     * @var string
     */
    private $logFile = '';

    //New Order Default Values
    /**
     * @var bool
     */
    private $enableFiscalization = false;
    /**
     * @var int
     * 0 - PAY_ALGO_UNSPECIFIED
     * 1 - PAY_ALGO_SMS
     * 2 - PAY_ALGO_DMS
     */
    private $paymentAlgorithm = 0;
    /**
     * @var int
     * 0 - FISCAL_TYPE_UNSPECIFIED: Неопределенный
     * 1 - FISCAL_TYPE_SINGLE: Одинарная фискализация
     * 2 - FISCAL_TYPE_DOUBLE: Двойная фискализация
     */
    private $fiscalizationType = 0;

    //Goods Default Settings
    /**
     * @var bool
     */
    private $defaultNeedMark = false;
    /**
     * @var int
     */
    private $defaultType = 0;
    /**
     * @var int
     */
    private $defaultMeasure = 0;
    /**
     * @var int
     */
    private $defaultTax = 0;

    /**
     * @var EncoderInterface|null
     */
    private $encoder;

	/**
	 * @var string
	 */
	private $clientType;

	/**
	 * @var int
	 */
	private $timeout;

    public function __construct(
        string $accessKey = '',
        string $secretKey = ''
    )
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
		$this->clientType = 'Curl';
		$this->timeout = 15;

    }

    /**
     * @return string
     */
    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    /**
     * @param string $accessKey
     * @return Settings
     */
    public function setAccessKey(string $accessKey): Settings
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @return Settings
     */
    public function setSecretKey(string $secretKey): Settings
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return Settings
     */
    public function setApiUrl(string $apiUrl): Settings
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }

    /**
     * @param string $logFile
     * @return Settings
     */
    public function setLogFile(string $logFile): Settings
    {
        $this->logFile = $logFile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableFiscalization(): bool
    {
        return $this->enableFiscalization;
    }

    /**
     * @param bool $enableFiscalization
     * @return Settings
     */
    public function setEnableFiscalization(bool $enableFiscalization): Settings
    {
        $this->enableFiscalization = $enableFiscalization;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentAlgorithm(): int
    {
        return $this->paymentAlgorithm;
    }

    /**
     * @param int $paymentAlgorithm
     * @return Settings
     */
    public function setPaymentAlgorithm(int $paymentAlgorithm): Settings
    {
        $this->paymentAlgorithm = $paymentAlgorithm;
        return $this;
    }

    /**
     * @return int
     */
    public function getFiscalizationType(): int
    {
        return $this->fiscalizationType;
    }

    /**
     * @param int $fiscalizationType
     * @return Settings
     */
    public function setFiscalizationType(int $fiscalizationType): Settings
    {
        $this->fiscalizationType = $fiscalizationType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultNeedMark(): bool
    {
        return $this->defaultNeedMark;
    }

    /**
     * @param bool $defaultNeedMark
     * @return Settings
     */
    public function setDefaultNeedMark(bool $defaultNeedMark): Settings
    {
        $this->defaultNeedMark = $defaultNeedMark;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultType(): int
    {
        return $this->defaultType;
    }

    /**
     * @param int $defaultType
     * @return Settings
     */
    public function setDefaultType(int $defaultType): Settings
    {
        $this->defaultType = $defaultType;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultMeasure(): int
    {
        return $this->defaultMeasure;
    }

    /**
     * @param int $defaultMeasure
     * @return Settings
     */
    public function setDefaultMeasure(int $defaultMeasure): Settings
    {
        $this->defaultMeasure = $defaultMeasure;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultTax(): int
    {
        return $this->defaultTax;
    }

    /**
     * @param int $defaultTax
     * @return Settings
     */
    public function setDefaultTax(int $defaultTax): Settings
    {
        $this->defaultTax = $defaultTax;
        return $this;
    }

    /**
     * @return EncoderInterface|null
     */
    public function getEncoder(): ?EncoderInterface
    {
        return $this->encoder;
    }

    /**
     * @param EncoderInterface|null $encoder
     * @return Settings
     */
    public function setEncoder(?EncoderInterface $encoder): Settings
    {
        $this->encoder = $encoder;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getClientType(): string
	{
		return $this->clientType;
	}

	/**
	 * @param string $clientType
	 *
	 * @return Settings
	 */
	public function setClientType( string $clientType ): Settings
	{
		$this->clientType = $clientType;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getTimeout(): int
	{
		return $this->timeout;
	}

	/**
	 * @param int $timeout
	 *
	 * @return Settings
	 */
	public function setTimeout( int $timeout ): Settings
	{
		$this->timeout = $timeout;

		return $this;
	}




}