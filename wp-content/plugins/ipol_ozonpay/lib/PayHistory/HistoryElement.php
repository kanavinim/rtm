<?php

namespace Ipol\OzonPay\PayHistory;

class HistoryElement {

	/**
	 * @var \DateTime
	 */
	protected $actionDate;

	/**
	 * @var HistoryOperationType
	 */
	protected $actionType;

	/**
	 * @var HistoryBasketElement[]
	 */
	protected $basketElements;


	public function __construct(int $actionType = 0) {
		$this->actionDate = new \DateTime();
		$this->actionType = new HistoryOperationType($actionType);
		$this->basketElements = [];
	}

	public function importBasketElements(array $basketElements):self
	{
		if (is_array($basketElements)) {
			foreach ($basketElements as $product) {
				$this->basketElements[] = new HistoryBasketElement(
					intval($product['id']),
					(string) $product['nm'],
					floatval($product['q']),
					floatval($product['p']),
					intval($product['vid']),
					(string)$product['inf']
				);
			}
		}
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getActionDate(): \DateTime
	{
		return $this->actionDate;
	}

	/**
	 * @param \DateTime $actionDate
	 *
	 * @return HistoryElement
	 */
	public function setActionDate( \DateTime $actionDate ): HistoryElement
	{
		$this->actionDate = $actionDate;

		return $this;
	}

	/**
	 * @return HistoryOperationType
	 */
	public function getActionType(): HistoryOperationType
	{
		return $this->actionType;
	}

	/**
	 * @param int $actionType
	 *
	 * @return HistoryElement
	 */
	public function setActionType( int $actionType ): HistoryElement
	{
		$this->actionType = new HistoryOperationType($actionType);

		return $this;
	}

	/**
	 * @param HistoryBasketElement $basketElement
	 *
	 * @return HistoryElement
	 */
	public function addBasketElement(HistoryBasketElement $basketElement) : HistoryElement
	{
		$this->basketElements[] = $basketElement;
		return $this;
	}

	/**
	 * @return HistoryBasketElement[]
	 */
	public function getBasketElements(): array {
		return $this->basketElements;
	}

	/**
	 * @param HistoryBasketElement[] $basketElements
	 *
	 * @return HistoryElement
	 */
	public function setBbasketElements( array $basketElements ): HistoryElement
	{
		$this->basketElements = $basketElements;

		return $this;
	}



}