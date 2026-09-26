<?php

namespace Ipol\OzonPay\PayHistory;

class OrderHistoryTemplate {

	//Templates
	/**
	 * @var string
	 */
	private $elementTextTemplate;
	/**
	 * @var string
	 */
	private $basketElementTemplate;

	//Placeholders
	/**
	 * @var string
	 */
	private $basketElementPlaceholder;
	/**
	 * @var string
	 */
	private $nnPlaceholder;
	/**
	 * @var string
	 */
	private $datePlaceholder;
	/**
	 * @var string
	 */
	private $payOperationPlaceholder;
	/**
	 * @var string
	 */
	private $productIdPlaceholder;
	/**
	 * @var string
	 */
	private $productVarIdPlaceholder;
	/**
	 * @var string
	 */
	private $productNamePlaceholder;
	/**
	 * @var string
	 */
	private $productQuantityPlaceholder;
	/**
	 * @var string
	 */
	private $productPricePlaceholder;
	/**
	 * @var string
	 */
	private $productOtherInfoPlaceholder;

	public function __construct() {
		$this->elementTextTemplate = '#dtm#: #pay_operation# #basket_elements#';
		$this->basketElementTemplate = '#nn#: #product_name# [#product_id#], стоимость #product_price# за 1 ед, кол-во #product_quantity#';

		$this->basketElementPlaceholder = 'basket_elements';
		$this->nnPlaceholder = 'nn';
		$this->datePlaceholder = 'dtm';
		$this->payOperationPlaceholder = 'pay_operation';
		$this->productIdPlaceholder = 'product_id';
		$this->productVarIdPlaceholder = 'product_var_id';
		$this->productNamePlaceholder = 'product_name';
		$this->productQuantityPlaceholder = 'product_quantity';
		$this->productPricePlaceholder = 'product_price';
		$this->productOtherInfoPlaceholder = 'product_info';
	}

	/**
	 * @return string
	 */
	public function getElementTextTemplate(): string {
		return $this->elementTextTemplate;
	}

	/**
	 * @param string $elementTextTemplate
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setElementTextTemplate( string $elementTextTemplate ): OrderHistoryTemplate {
		$this->elementTextTemplate = $elementTextTemplate;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasketElementTemplate(): string {
		return $this->basketElementTemplate;
	}

	/**
	 * @param string $basketElementTemplate
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setBasketElementTemplate( string $basketElementTemplate ): OrderHistoryTemplate {
		$this->basketElementTemplate = $basketElementTemplate;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasketElementPlaceholder(): string {
		return $this->basketElementPlaceholder;
	}

	/**
	 * @param string $basketElementPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setBasketElementPlaceholder( string $basketElementPlaceholder ): OrderHistoryTemplate {
		$this->basketElementPlaceholder = $basketElementPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getNnPlaceholder(): string {
		return $this->nnPlaceholder;
	}

	/**
	 * @param string $nnPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setNnPlaceholder( string $nnPlaceholder ): OrderHistoryTemplate {
		$this->nnPlaceholder = $nnPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDatePlaceholder(): string {
		return $this->datePlaceholder;
	}

	/**
	 * @param string $datePlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setDatePlaceholder( string $datePlaceholder ): OrderHistoryTemplate {
		$this->datePlaceholder = $datePlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPayOperationPlaceholder(): string {
		return $this->payOperationPlaceholder;
	}

	/**
	 * @param string $payOperationPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setPayOperationPlaceholder( string $payOperationPlaceholder ): OrderHistoryTemplate {
		$this->payOperationPlaceholder = $payOperationPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductIdPlaceholder(): string {
		return $this->productIdPlaceholder;
	}

	/**
	 * @param string $productIdPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductIdPlaceholder( string $productIdPlaceholder ): OrderHistoryTemplate {
		$this->productIdPlaceholder = $productIdPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductVarIdPlaceholder(): string {
		return $this->productVarIdPlaceholder;
	}

	/**
	 * @param string $productVarIdPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductVarIdPlaceholder( string $productVarIdPlaceholder ): OrderHistoryTemplate {
		$this->productVarIdPlaceholder = $productVarIdPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductNamePlaceholder(): string {
		return $this->productNamePlaceholder;
	}

	/**
	 * @param string $productNamePlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductNamePlaceholder( string $productNamePlaceholder ): OrderHistoryTemplate {
		$this->productNamePlaceholder = $productNamePlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductQuantityPlaceholder(): string {
		return $this->productQuantityPlaceholder;
	}

	/**
	 * @param string $productQuantityPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductQuantityPlaceholder( string $productQuantityPlaceholder ): OrderHistoryTemplate {
		$this->productQuantityPlaceholder = $productQuantityPlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductPricePlaceholder(): string {
		return $this->productPricePlaceholder;
	}

	/**
	 * @param string $productPricePlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductPricePlaceholder( string $productPricePlaceholder ): OrderHistoryTemplate {
		$this->productPricePlaceholder = $productPricePlaceholder;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProductOtherInfoPlaceholder(): string {
		return $this->productOtherInfoPlaceholder;
	}

	/**
	 * @param string $productOtherInfoPlaceholder
	 *
	 * @return OrderHistoryTemplate
	 */
	public function setProductOtherInfoPlaceholder( string $productOtherInfoPlaceholder ): OrderHistoryTemplate {
		$this->productOtherInfoPlaceholder = $productOtherInfoPlaceholder;

		return $this;
	}

}