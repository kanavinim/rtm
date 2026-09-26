<?php

namespace Ipol\OzonPay\PayHistory;

class HistoryBasketElement {

	/**
	 * @var int
	 */
	private $productId;

	/**
	 * @var int | null
	 */
	private $productVariantId;

	/**
	 * @var string
	 */
	private $productName;

	/**
	 * @var float
	 */
	private $quantity;

	/**
	 * @var float
	 */
	private $price;

	/**
	 * @var string
	 */
	private $otherInfo;

	public function __construct(
		int $productId=-1,
		string $productName='',
		float $quantity=-1,
		float $price=-1,
		int $productVariantId = -1,
		string $otherInfo = ''
	) {
		$this->productId = $productId;
		$this->productName = $productName;
		$this->productVariantId = $productVariantId;
		$this->quantity = $quantity;
		$this->price = $price;
		$this->otherInfo = $otherInfo;
	}

	/**
	 * @return int
	 */
	public function getProductId(): int {
		return $this->productId;
	}

	/**
	 * @param int $productId
	 *
	 * @return HistoryBasketElement
	 */
	public function setProductId( int $productId ): HistoryBasketElement {
		$this->productId = $productId;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getProductVariantId(): ?int {
		return $this->productVariantId;
	}

	/**
	 * @param int|null $productVariantId
	 *
	 * @return HistoryBasketElement
	 */
	public function setProductVariantId( ?int $productVariantId ): HistoryBasketElement {
		$this->productVariantId = $productVariantId;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductName(): string {
		return $this->productName;
	}

	/**
	 * @param string $productName
	 *
	 * @return HistoryBasketElement
	 */
	public function setProductName( string $productName ): HistoryBasketElement {
		$this->productName = $productName;

		return $this;
	}

	/**
	 * @return float|int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param float|int $quantity
	 *
	 * @return HistoryBasketElement
	 */
	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;

		return $this;
	}

	/**
	 * @return float|int
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param float|int $price
	 *
	 * @return HistoryBasketElement
	 */
	public function setPrice( $price ) {
		$this->price = $price;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOtherInfo(): string {
		return $this->otherInfo;
	}

	/**
	 * @param string $otherInfo
	 *
	 * @return HistoryBasketElement
	 */
	public function setOtherInfo( string $otherInfo ): HistoryBasketElement {
		$this->otherInfo = $otherInfo;

		return $this;
	}

}