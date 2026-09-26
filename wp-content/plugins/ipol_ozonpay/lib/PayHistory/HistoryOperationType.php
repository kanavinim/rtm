<?php

namespace Ipol\OzonPay\PayHistory;

class HistoryOperationType {

	public const ACTION_UNDEFINED = 0;
	public const ACTION_UNDEFINED_VAL = 'Не определено';
	public const ACTION_PAY = 1;
	public const ACTION_PAY_VAL = 'Оплата';
	public const ACTION_PAY_TWOFACTOR = 2;
	public const ACTION_PAY_TWOFACTOR_VAL = 'Двухстадийная оплата';
	public const ACTION_CANCEL = 3;
	public const ACTION_CANCEL_VAL = 'Отмена';
	public const ACTION_PAY_CONFIRM = 4;
	public const ACTION_PAY_CONFIRM_VAL = 'Подтверждение оплаты';
	public const ACTION_UPDATEMARK = 5;
	public const ACTION_UPDATEMARK_VAL = 'Обновление маркировки';
	public const ACTION_FINALCHECK = 6;
	public const ACTION_FINALCHECK_VAL = 'Формирование финального чека';
	public const ACTION_REFUND = 7;
	public const ACTION_REFUND_VAL = 'Возврат';

	/**
	 * @var int
	 */
	private $operationType;
	public function __construct(int $typeCode) {
		$this->operationType = $typeCode;
	}

	/**
	 * @return int
	 */
	public function getOperationType(): int {
		return $this->operationType;
	}

	/**
	 * @param int $operationType
	 *
	 * @return HistoryOperationType
	 */
	public function setOperationType( int $operationType ): HistoryOperationType {
		$this->operationType = $operationType;

		return $this;
	}

	public function getOperationTypeValue(): string
	{
		switch ($this->operationType) {
			case self::ACTION_UNDEFINED:
				return self::ACTION_UNDEFINED_VAL;
			case self::ACTION_PAY:
				return self::ACTION_PAY_VAL;
			case self::ACTION_PAY_TWOFACTOR:
				return self::ACTION_PAY_TWOFACTOR_VAL;
			case self::ACTION_CANCEL:
				return self::ACTION_CANCEL_VAL;
			case self::ACTION_PAY_CONFIRM:
				return self::ACTION_PAY_CONFIRM_VAL;
			case self::ACTION_UPDATEMARK:
				return self::ACTION_UPDATEMARK_VAL;
			case self::ACTION_FINALCHECK:
				return self::ACTION_FINALCHECK_VAL;
			case self::ACTION_REFUND:
				return self::ACTION_REFUND_VAL;
		}
		return '';
	}

}