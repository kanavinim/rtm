<?php

namespace Ipol\OzonPay\PayHistory;

class OrderHistory {

	/**
	 * @var HistoryElement[]
	 */
	private $historyElements;

	public function __construct() {
		$this->historyElements = [];
	}

	/**
	 * @param HistoryElement $historyElement
	 *
	 * @return $this
	 */
	public function addHistoryElement(HistoryElement $historyElement): self
	{
		$this->historyElements[] = $historyElement;
		return $this;
	}

	public function importHistory(string $historyDump) {
		$history = unserialize($historyDump);
		if (!$history) return false;

		$this->historyElements = [];
		foreach ($history as $historyElement) {
			$this->historyElements[] = (new HistoryElement($historyElement['tp']))
				->setActionDate( \DateTime::createFromFormat('Y-m-d H:i:s',$historyElement['dtm']) )
				->importBasketElements($historyElement['bsk']);
		}
		return true;
	}

	public function exportHistory():string
	{
		$exportObject = [];
		foreach ($this->historyElements as $element) {
			$basketData = [];
			foreach ($element->getBasketElements() as $basketElement) {
				$basketData[] = [
					'id'=>$basketElement->getProductId(),
					'vid'=>$basketElement->getProductVariantId(),
					'nm'=>$basketElement->getProductName(),
					'q'=>$basketElement->getQuantity(),
					'p'=>$basketElement->getPrice(),
					'inf'=>$basketElement->getOtherInfo()
				];
			}
			$exportObject[] = [
				'tp'=>$element->getActionType()->getOperationType(),
				'dtm'=>$element->getActionDate()->format('Y-m-d H:i:s'),
				'bsk'=>$basketData
			];
		}
		return serialize($exportObject);
	}

	/**
	 * @return HistoryElement[]
	 */
	public function getHistoryElements(): array
	{
		return $this->historyElements;
	}


	public function renderHistoryText(OrderHistoryTemplate $renderTemplate) {

		$output = '';
		foreach ($this->historyElements as $element) {
			$elementString = $renderTemplate->getElementTextTemplate();
			$elementString = preg_replace('/#' . $renderTemplate->getDatePlaceholder() . '#/', $element->getActionDate()->format('d.m.Y H:i:s'), $elementString);
			$elementString = preg_replace('/#' . $renderTemplate->getPayOperationPlaceholder() . '#/', $element->getActionType()->getOperationTypeValue(), $elementString);

			$basketElementsString = '';
			$i = 0;
			foreach ($element->getBasketElements() as $basketElement) {
				$basketString = $renderTemplate->getBasketElementTemplate();
				$basketString = preg_replace('/#' . $renderTemplate->getNnPlaceholder() . '#/', ++$i, $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductIdPlaceholder() . '#/', $basketElement->getProductId(), $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductNamePlaceholder() . '#/', $basketElement->getProductName(), $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductQuantityPlaceholder() . '#/', $basketElement->getQuantity(), $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductPricePlaceholder() . '#/', number_format(floatval($basketElement->getPrice())/100,2,'.',' '), $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductVarIdPlaceholder() . '#/', $basketElement->getProductVariantId(), $basketString);
				$basketString = preg_replace('/#' . $renderTemplate->getProductOtherInfoPlaceholder() . '#/', $basketElement->getOtherInfo(), $basketString);

				$basketElementsString .= $basketString;
			}
			$elementString = preg_replace('/#' . $renderTemplate->getBasketElementPlaceholder() . '#/', $basketElementsString, $elementString);

			$output .= $elementString;
		}
		return $output;
	}

}