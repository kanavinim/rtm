<?php

namespace Ipol\OzonPay\WordPress;

use Ipol\OzonPay\SDK\Other\Types;

/**
 * Types class for localisation in WP
 */
class WPTypes extends Types
{
	static private $ERROR_VAL = 'Error';
	static private $orderStatusesRus = [];
	static private $refundStatusesRus = [];

	static private $instance = false;

	private static function init()
	{
		if (!self::$instance) {
			self::$instance = true;
			self::$ERROR_VAL = 'WP_ERROR_VAL';
			self::$orderStatusesRus = [
				__('New','ipol_ozonpay'),
				__('Awaiting payment','ipol_ozonpay'),
				__('Expired','ipol_ozonpay'),
				__('Canceled','ipol_ozonpay'),
				__('Authorized','ipol_ozonpay'),
				__('Partially returned','ipol_ozonpay'),
				__('Paid','ipol_ozonpay'),
				__('Partially refunded','ipol_ozonpay'),
				__('Refunded','ipol_ozonpay'),
				__('Dispute','ipol_ozonpay')
			];
			self::$refundStatusesRus = [
				__('Not refunded','ipol_ozonpay'),
				__('Full refunded','ipol_ozonpay'),
				__('Partial','ipol_ozonpay'),
				__('Refunds not available','ipol_ozonpay')
			];
		}
	}

	/**
	 * @param int $code
	 * @return string
	 * <b><u>For use in WP</u></b>
	 */
	static public function getOrderStatusRus(int $code = 0): string
	{
		self::init();
		if ($code == -1) return self::$ERROR_VAL;
		return self::$orderStatusesRus[$code];
	}

	/**
	 * @param string $code
	 * @return string
	 * <b><u>For use in WP</u></b>
	 */
	static public function getOrderStatusRusByCode(string $code): string
	{
		self::init();
		foreach (self::ORDER_STATUSES as $key => $val)
			if ($code == $val) return self::$orderStatusesRus[$key];
		return '';
	}

	/**
	 * @param int $code
	 * @return string
	 * <b><u>For use in WP</u></b>
	 */
	static public function getRefundStatusRus(int $code = 0): string
	{
		self::init();
		if ($code == -1) return self::$ERROR_VAL;
		return self::$refundStatusesRus[$code];
	}

	/**
	 * @param string $code
	 * @return string
	 * <b><u>For use in WP</u></b>
	 */
	static public function getRefundStatusRusByCode(string $code): string
	{
		self::init();
		foreach (self::REFUND_STATUSES as $key => $val)
			if ($code == $val) return self::$refundStatusesRus[$key];
		return '';
	}
}
