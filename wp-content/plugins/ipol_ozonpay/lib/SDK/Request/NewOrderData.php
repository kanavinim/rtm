<?php

namespace Ipol\OzonPay\SDK\Request;

use Ipol\OzonPay\SDK\Entity\BasketItemCollection;
use Ipol\OzonPay\SDK\Other\Types;
use Ipol\OzonPay\SDK\Entity\AbstractEntity;
use Ipol\OzonPay\SDK\Entity\Money;
use Ipol\OzonPay\SDK\Entity\DateTime;

/**
 * Mapping Values Include!
 */
class NewOrderData extends AbstractEntity
{

    /**
     * @var string
     */
    protected $extId;

    protected $extData;

    /**
     * @var Money
     */
    protected $amount;

    /**
     * @var boolean|null
     * <br>
     * This property supports autocompletion!
     */
    protected $enableFiscalization;

    /**
     * @var DateTime|null
     * <br>
     * This property supports autocompletion!
     */
    protected $expiresAt;

    /**
     * @var int|null
     * <br>
     * This property supports autocompletion!
     */
    protected $fiscalizationType;

    /**
     * @var int|null
     * <br>
     * This property supports autocompletion!
     */
    protected $paymentAlgorithm;

    /**
     * @var string|null
     */
    protected $receiptEmail;

    /**
     * @var BasketItemCollection|null
     */
    protected $items;

	/**
	 * @var string|null
	 */
	protected $fiscalizationPhone;

    /**
     * @var string|null
     */
    protected $failUrl;

    /**
     * @var string|null
     */
    protected $successUrl;


    /**
     * @return string
     */
    public function getExtId(): string
    {
        return $this->extId;
    }

    /**
     * @param string $extId
     * @return NewOrderData
     */
    public function setExtId(string $extId): NewOrderData
    {
        $this->extId = $extId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtData()
    {
        return $this->extData;
    }

    /**
     * @param mixed $extData
     * @return NewOrderData
     */
    public function setExtData($extData)
    {
        $this->extData = $extData;
        return $this;
    }

    /**
     * @return \Ipol\OzonPay\SDK\Entity\Money
     */
    public function getAmount(): \Ipol\OzonPay\SDK\Entity\Money
    {
        return $this->amount;
    }

    /**
     * @param \Ipol\OzonPay\SDK\Entity\Money $amount
     * @return NewOrderData
     */
    public function setAmount(\Ipol\OzonPay\SDK\Entity\Money $amount): NewOrderData
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnableFiscalization(): ?bool
    {
        return $this->enableFiscalization;
    }

    /**
     * @param bool|null $enableFiscalization
     * @return NewOrderData
     * <br>
     * This property supports autocompletion!
     */
    public function setEnableFiscalization(?bool $enableFiscalization): NewOrderData
    {
        $this->enableFiscalization = $enableFiscalization;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }
    /**
     * @param DateTime|null $expiresAt
     * @return NewOrderData
     * <br>
     * This property supports autocompletion!
     */
    public function setExpiresAt(?DateTime $expiresAt): NewOrderData
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getFiscalizationType(): string
    {
        return Types::getFiscalizationType($this->fiscalizationType);
    }

    /**
     * @return int|null
     */
    public function getFiscalizationTypeValue(): ?int
    {
        return $this->fiscalizationType;
    }

    /**
     * @param int|null $fiscalizationType
     * @return NewOrderData
     * <br>
     * This property supports autocompletion!
     */
    public function setFiscalizationType(?int $fiscalizationType): NewOrderData
    {
        $this->fiscalizationType = $fiscalizationType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentAlgorithm(): string
    {
        return Types::getPaymentAlgorithm($this->paymentAlgorithm);
    }

    /**
     * @return int|null
     */
    public function getPaymentAlgorithmValue(): ?int
    {
        return $this->paymentAlgorithm;
    }

    /**
     * @param int|null $paymentAlgorithm
     * @return NewOrderData
     * <br>
     * This property supports autocompletion!
     */
    public function setPaymentAlgorithm(?int $paymentAlgorithm): NewOrderData
    {
        $this->paymentAlgorithm = $paymentAlgorithm;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceiptEmail(): ?string
    {
        return $this->receiptEmail;
    }

    /**
     * @param string|null $receiptEmail
     * @return NewOrderData
     */
    public function setReceiptEmail(?string $receiptEmail): NewOrderData
    {
        $this->receiptEmail = $receiptEmail;
        return $this;
    }

    /**
     * @return BasketItemCollection|null
     */
    public function getItems(): ?BasketItemCollection
    {
        return $this->items;
    }

    /**
     * @param BasketItemCollection|null $items
     * @return NewOrderData
     */
    public function setItems(?BasketItemCollection $items): NewOrderData
    {
        $this->items = $items;
        return $this;
    }

	/**
	 * @return string|null
	 */
	public function getFiscalizationPhone(): ?string {
		return $this->fiscalizationPhone;
	}

	/**
	 * @param string|null $fiscalizationPhone
	 *
	 * @return NewOrderData
	 */
	public function setFiscalizationPhone( ?string $fiscalizationPhone ): NewOrderData {
		$this->fiscalizationPhone = $fiscalizationPhone;

		return $this;
	}

    public function getFailUrl(): ?string
    {
        return $this->failUrl;
    }

    public function setFailUrl(?string $failUrl): NewOrderData
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    public function getSuccessUrl(): ?string
    {
        return $this->successUrl;
    }

    public function setSuccessUrl(?string $successUrl): NewOrderData
    {
        $this->successUrl = $successUrl;
        return $this;
    }




}
