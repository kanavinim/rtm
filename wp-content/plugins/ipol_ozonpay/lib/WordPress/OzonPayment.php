<?php

namespace Ipol\OzonPay\WordPress;

use Ipol\OzonPay\PayHistory\HistoryBasketElement;
use Ipol\OzonPay\PayHistory\HistoryElement;
use Ipol\OzonPay\PayHistory\HistoryOperationType;
use Ipol\OzonPay\PayHistory\OrderHistory;
use Ipol\OzonPay\SDK\Entity\BasketItem;
use Ipol\OzonPay\SDK\Entity\BasketItemCollection;
use Ipol\OzonPay\SDK\Entity\BasketItemPosition;
use Ipol\OzonPay\SDK\Entity\BasketItemPositionCollection;
use Ipol\OzonPay\SDK\Entity\DateTime;
use Ipol\OzonPay\SDK\Entity\Money;
use Ipol\OzonPay\SDK\Entity\Settings;
use Ipol\OzonPay\SDK\Other\CalcBasketFixer;
use Ipol\OzonPay\SDK\Other\FloatPrecisionSetSaver;
use Ipol\OzonPay\SDK\Other\Types;
use Ipol\OzonPay\SDK\OzonSDK;
use Ipol\OzonPay\SDK\Request\Confirm;
use Ipol\OzonPay\SDK\Request\FinalReceipt;
use Ipol\OzonPay\SDK\Request\NewOrderData;
use Ipol\OzonPay\SDK\Request\OrderInfo;
use Ipol\OzonPay\SDK\Request\Refund;
use Ipol\OzonPay\SDK\Request\SpecifyPositions;

class OzonPayment extends \WC_Payment_Gateway {
	use DBTrait;

	public $supports = [ 'products', 'refunds' ];

	private $ozon;

	public function __construct() {
		global $wpdb;

		load_textdomain('ipol_ozonpay',OzonPayPlugin::getPluginDir() . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'ipol_ozonpay-ru_RU.mo');

		$this->id           = 'ozonpay';
		$this->icon         = '';
		$this->title = __('Payments with OZON Pay', 'ipol_ozonpay');
		$this->description = __('Payment online with payment service from Ozon', 'ipol_ozonpay');
		$this->method_title = __('Payments with OZON Pay', 'ipol_ozonpay');
		$this->method_description  = __('Payment online with payment service from Ozon', 'ipol_ozonpay');

		$this->init_form_fields();
		$this->init_settings();

		$this->db = $wpdb;
		$this->tableName = $this->db->get_blog_prefix().'ozonpay_orders';

        $customTitle = $this->settings['customTitle'] ?? '';
        if (!empty($customTitle)) $this->title = $customTitle;

		$ozonSettings = (new Settings( $this->settings['accessKey'], $this->settings['secretKey'] ))
			->setApiUrl($this->settings['apiUrl'])
			->setEnableFiscalization($this->settings['enableFiscalization']=='yes')
			->setFiscalizationType(intval($this->settings['fiscalizationType']))
			->setPaymentAlgorithm(intval($this->settings['paymentAlgorithm']))
			->setDefaultType(1)
			->setDefaultMeasure(1)
			->setLogFile(OzonPayPlugin::getPluginDir() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'log.txt')
			->setTimeout(15)
			->setClientType('WordpressRequests')
		;
		$this->ozon = OzonSDK::withSettings($ozonSettings);

		add_action( 'woocommerce_api_wc_' . $this->id, [ $this, 'restFunctions' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options'] );


		$systemTimeZone = ini_get('date.timezone');
		if (!empty($systemTimeZone)) {
			date_default_timezone_set($systemTimeZone);
		}
	}

	public function generate_template_html($key, $data)
	{
		$filePath = OzonPayPlugin::getPluginDir() . DIRECTORY_SEPARATOR .'assets' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $data['value'] . '.tpl';
		if (!file_exists($filePath) && !is_readable($filePath)) return '';
		$templateContent = file_get_contents($filePath);
		if (!empty($data['templatecontent']))
			foreach ($data['templatecontent'] as $key => $value)
				$templateContent = preg_replace('/#' . $key . '#/', $value, $templateContent);

		return $templateContent;
	}

	public function init_form_fields() {
		$this->form_fields = [
			'html_faq'=>[
				'type'=>'template',
				'value'=>'helplink'
			],
			'connection_title' => [
				'type'=>'title',
				'title'=> __('Connection settings', 'ipol_ozonpay')
			],
			'customTitle' => [
                'title' => __('Custom name for checkout page', 'ipol_ozonpay'),
                'description' => __('You can specify a custom name for the payment system for the checkout page', 'ipol_ozonpay'),
                'type' => 'text',
                'desc_tip' => true,
            ],
            'accessKey'=>[
				'title'=>'accessKey',
				'description'=> __('You can get the value of this parameter from your OZON Pay Acquiring personal account', 'ipol_ozonpay'). '.',
				'type'=>'text',
				'desc_tip'=>true
			],
			'secretKey'=>[
				'title'=>'secretKey',
				'description'=> __('You can get the value of this parameter from your OZON Pay Acquiring personal account', 'ipol_ozonpay'). '.',
				'type'=>'text',
				'desc_tip'=>true
			],
			'apiUrl'=>[
				'title'=> __('Address for sending requests', 'ipol_ozonpay'),
				'description'=> __('You can get the value of this parameter from your OZON Pay Acquiring personal account or from the agreement', 'ipol_ozonpay') . '.',
				'type'=>'text',
				'default'=>'https://payapi.ozon.ru',
				'desc_tip'=>true
			],
			'apiurl_btn'=>[
				'type'=>'template',
				'value'=>'setapiurl'
			],
			'paymentAlgorithm'=>[
				'title'=> __('Payment mode', 'ipol_ozonpay'),
				'type'=>'select',
				'default'=>'1',
				'options'=>[
					'1'=> __('One-step payments','ipol_ozonpay'),
					'2'=> __('Two-stage payments', 'ipol_ozonpay')
				]
			],

			'fiscal_title' => [
				'type'=>'title',
				'title'=> __('Fiscalization settings', 'ipol_ozonpay')
			],
			'enableFiscalization' => [
				'title'=> __('Enable fiscalization mode', 'ipol_ozonpay'),
				'description'=> __('If this mode is enabled, receipts will be generated for payments and returns', 'ipol_ozonpay') . '.',
				'desc_tip'=>true,
				'type'    => 'checkbox',
			],
			'fiscalizationPhone' => [
				'title' => __('Phone number for check', 'ipol_ozonpay'),
				'description' => __('This phone number will be transferred to the OFD when generating a fiscal receipt, and will also be shown in this receipt', 'ipol_ozonpay'),
				'desc_tip'=>true,
				'type'=>'text',
			],
			'fiscalizationType' => [
				'title'=> __('Fiscalization scheme', 'ipol_ozonpay'),
				'description'=> __('If a double fiscalization regime is chosen, this will allow trading in goods subject to mandatory labeling', 'ipol_ozonpay') . ".<br/><br/>" . __('When applying this regime, it will be necessary to indicate the labeling of goods in the future. At the time of sending the order to the buyer, issue a final receipt containing information about the labeling of goods', 'ipol_ozonpay') . ".",
				'desc_tip'=>true,
				'type'=>'select',
				'default'=>'1',
				'options'=>[
					'1'=> __('Single circuit', 'ipol_ozonpay'),
					'2'=> __('Double circuit', 'ipol_ozonpay')
				]
			],
			'productTax' => [
				'title'=> __('Default VAT rate for goods', 'ipol_ozonpay'),
				'description'=> __('If the product does not have VAT specified, the value from this setting will be used', 'ipol_ozonpay'),
				'desc_tip'=>true,
				'type'=>'select',
				'default'=>'1',
				'options'=>[
					'1'=>'0%',
                    '7'=>'5%',
                    '8'=>'7%',
					'2'=>'10%',
					'3'=>'20%',
					'4'=>'10/110',
					'5'=>'20/120',
					'6'=> __('Without VAT', 'ipol_ozonpay')
				]
			],
			'deliveryTax' => [
				'title'=> __('Default VAT rate for delivery service', 'ipol_ozonpay'),
				'description'=> __('If the delivery service does not have VAT specified, the value from this setting will be used', 'ipol_ozonpay'),
				'desc_tip'=>true,
				'type'=>'select',
				'default'=>'1',
				'options'=>[
					'1'=>'0%',
                    '7'=>'5%',
                    '8'=>'7%',
					'2'=>'10%',
					'3'=>'20%',
					'4'=>'10/110',
					'5'=>'20/120',
					'6'=> __('Without VAT', 'ipol_ozonpay')
				]
			],

			'service_title' => [
				'type'=>'title',
				'title'=> __('Service settings', 'ipol_ozonpay')
			],
			'enLog' => [
				'title'=> __('Enable exchange logging', 'ipol_ozonpay'),
				'description'=> __('If this mode is enabled, all requests and responses will be recorded in a separate log file for further analysis', 'ipol_ozonpay') . '.',
				'desc_tip'=>true,
				'type'=>'checkbox'
			],
			'isServiceMode' => [
				'title'=> __('Enable service mode', 'ipol_ozonpay'),
				'description'=> __('Some additional utility features are available in this mode. It is not recommended to use this mode on a working project', 'ipol_ozonpay') . '.',
				'desc_tip'=>true,
				'type'=>'checkbox'
			],
			'return_url'=>[
				'type'=>'template',
				'value'=>'returnurl',
				'templatecontent'=>[
					'returnurl'=>site_url() . '/?wc-api=wc_ozonpay&action=return'
				]
			],
		];
	}

	public function process_admin_options() {
		return parent::process_admin_options();
	}

	public function validate_text_field( $key, $value ) {
		switch ($key) {
			case 'accessKey':
				if (empty($value)) {
					\WC_Admin_Settings::add_error( __('Please fill out the setting', 'ipol_ozonpay') .' "accessKey" ' . __('data received from the Agreement or from the Personal Account', 'ipol_ozonpay') . '.');
					$this->update_option('enabled','no');
				}
				break;
			case 'secretKey':
				if (empty($value)) {
					\WC_Admin_Settings::add_error(__('Please fill out the setting', 'ipol_ozonpay') . ' "secretKey" ' . __('data received from the Agreement or from the Personal Account', 'ipol_ozonpay') . '.');
					$this->update_option('enabled','no');
				}
				break;
			case 'apiUrl':
				if (empty($value)) {
					\WC_Admin_Settings::add_error(__('Please fill out the setting', 'ipol_ozonpay') . ' "' . __('Address for sending requests', 'ipol_ozonpay') . '" ' . __('data received from the Agreement or from the Personal Account', 'ipol_ozonpay') . '.');
					$this->update_option('enabled','no');
				}
				break;
			case 'fiscalizationPhone':
				$validatedValue = preg_replace("/^\+7\d{10}$/",'',$value);
				if (!empty($validatedValue)) {
					$this->update_option($key, '');
					$value = '';
				}

				$fieldName = $this->get_field_key('enableFiscalization');
				if ($_POST[$fieldName] === '1') {
					$checked = true;

					if (empty($value)) $checked = false;
					if (!empty($validatedValue)) $checked = false;

					if (!$checked) {
						\WC_Admin_Settings::add_error('При включенной фискализации требуется указать Телефон для чека в формате +71112223344.');
						//$this->update_option('enabled','no');
						$this->update_option('enableFiscalization',null);
					}
				}

				break;
		}
		return $value;
	}

	public function process_payment( $order_id ) {
		$order = new \WC_Order($order_id);
		$operationHistory = new HistoryElement(HistoryOperationType::ACTION_PAY);
		if (intval($this->settings['paymentAlgorithm'])==2) $operationHistory->setActionType(HistoryOperationType::ACTION_PAY_TWOFACTOR);

		$order->update_status('on-hold', __('Payment expected', 'ipol_ozonpay'));

		$order_items = $order->get_items();

		$orderTotal = $order->get_total();
		$order_total = intval(round(floatval($order->get_total())*100, 0));

		$basketItems = [];
		foreach ($order_items as $item) {
			$ord_item = new \WC_Order_Item_Product($item->get_id());

			$total = floatval($ord_item->get_total_tax())+floatval($ord_item->get_total());
			$qty = $ord_item->get_quantity();
			$price_for_one = $total / $qty;
			$price_for_one_norm = intval(round($price_for_one*100));

			if ($price_for_one_norm == 0) continue;

			$taxCode = intval($this->settings['productTax']);
			$tax = new \WC_Tax();
			$variantId = $item['variation_id'];
			if ($variantId) $product = new \WC_Product_Variation($item['variation_id']);
			else $product = new \WC_Product($item['product_id']);
			if (get_option("woocommerce_calc_taxes") != "no") {
				$taxVal = $tax->get_base_tax_rates($product->get_tax_class(true));
				if (!empty($taxVal)) {
					$rates = $tax->get_rates($product->get_tax_class());
					if (!empty($rates)) {
						$rateVal = intval(array_shift($rates)['rate']);
						$taxCode = $this->getTaxCode($rateVal) ?? $taxCode;
					}
				}
			}

			$isMarked = false;
			$customFields = get_post_custom($item['product_id']);
			if ( (!empty($customFields['ozonpay_is_marked'])) && ($customFields['ozonpay_is_marked'][0] == '1') ) $isMarked = true;

			$basketItems[$item->get_id()] = [
				'id' => $item->get_id(),
				'var_id'=>$variantId,
				'qty' => $ord_item->get_quantity(),
				'price' => $price_for_one_norm,
				'name'=>$item['name'],
				'tax' => $taxCode,
				'is_marked'=>$isMarked
			];

		}

		//add Shipment Data
		$shippings = $order->get_shipping_methods();
		if (count($shippings) > 0) {
			$shipping = array_shift($shippings);
			$shipping_id = $shipping->get_id();
			$shipping_name = $shipping->get_name();
			$shipping_price = intval(round(( floatval($shipping->get_total()) + floatval($shipping->get_total_tax()) )*100, 0));

			if ($shipping_price > 0) {
				$shippingTaxCode = intval($this->settings['deliveryTax']);
				$shippingTaxes = $shipping->get_taxes();
				if (is_array($shippingTaxes) && !empty($shippingTaxes) && !empty($shippingTaxes['total'])) {
					foreach ($shippingTaxes['total'] as $tax_rate_id=>$val) {
						if ($tax_rate_id > 0) {
							$rateVal = $this->db->get_var($this->db->prepare("SELECT `tax_rate` FROM `{$this->db->base_prefix}woocommerce_tax_rates` WHERE `tax_rate_id` = %d;",$tax_rate_id));
							if ($rateVal) {
								$shippingTaxCode = $this->getTaxCode(intval($rateVal)) ?? $shippingTaxCode;
								break;
							}
						}
					}
				}

				$basketItems['dlv'] = [
					'id'=>$shipping_id,
					'var_id'=>0,
					'type'=>'dlv',
					'qty'=>1,
					'price'=>$shipping_price,
					'name'=>$shipping_name,
					'tax'=>$shippingTaxCode,
					'is_marked'=>false
				];
			}
		}

		// Corrector
		$basketCorrector = new CalcBasketFixer($basketItems,$order_total);
		if (!$basketCorrector->checkNoNeedRecalc())
			$basketItems = $basketCorrector->correctBasket();

		$ozonItemCollection = new BasketItemCollection();
		$orderContainsMark = false;
		foreach ($basketItems as $bItem) {
			$ozonBasketItem = (new BasketItem())
				->setNeedMark($bItem['is_marked'])
				->setVat($bItem['tax'])
				->setQuantity($bItem['qty'])
				->setName($bItem['name'])
				->setExtId('PROD_'.$bItem['id'].'_'.$bItem['var_id'])
				->setPrice(new Money($bItem['price']))
			;
			$ozonItemCollection->add($ozonBasketItem);

			$operationHistory->addBasketElement(
				(new HistoryBasketElement())
				->setProductName($bItem['name'])
				->setPrice($bItem['price'])
				->setQuantity($bItem['qty'])
				->setProductId($bItem['id'])
				->setProductVariantId($bItem['var_id'])
			);

			if ($bItem['is_marked']) $orderContainsMark = true;
		}
		if (
			$orderContainsMark
			&& ($this->settings['enableFiscalization'] == 'yes')
			&& (intval($this->settings['fiscalizationType']) != 2)
		) {
			//Error!
			wc_add_notice(__('Error when registering OZON Pay Acquiring payment. Please report this problem to the store administration', 'ipol_ozonpay') . '.','error');
			$order->add_order_note(__('Error when registering OZON Pay Acquiring payment. Please report this problem to the store administration', 'ipol_ozonpay') . '.');
			return [
				'result' => 'fail',
				'redirect' => $this->get_return_url($order)
			];
		}

		$ozonOrder = (new NewOrderData())
			->setExtId('Order N'.$order_id)
			->setAmount(new Money($order_total))
			->setItems($ozonItemCollection)
            ->setSuccessUrl(site_url() . '/?wc-api=wc_ozonpay&action=return&cms_ord_id=' . $order_id)
            ->setFailUrl(site_url() . '/?wc-api=wc_ozonpay&action=return&cms_ord_id=' . $order_id)
        ;
		$clientMail = $order->get_billing_email();
		if (!empty($clientMail)) $ozonOrder->setReceiptEmail($clientMail);

		if ($this->settings['enableFiscalization']=='yes') {
			$ozonOrder->setFiscalizationPhone($this->settings['fiscalizationPhone']);
		}


		$createdOrder = $this->ozon->createOrder($ozonOrder);
		if (!$createdOrder->isSuccess()) {
			//error creting ozon order
			wc_add_notice(__('Error when registering OZON Pay Acquiring payment. Please report this problem to the store administration', 'ipol_ozonpay') . '.','error');
			$order->add_order_note(__('Error when registering OZON Pay Acquiring payment. Please report this problem to the store administration', 'ipol_ozonpay') . '.');
			return [
				'result' => 'fail',
				'redirect' => $this->get_return_url($order)
			];
		}
		$this->correctWPOrder(intval($order_id),$basketItems);

		$ozonOrderInfo = $createdOrder->getOrder();

		$orderInfo = [
			'ozon_id' => $ozonOrderInfo->getId(),
			'number' => $ozonOrderInfo->getNumber(),
			'ext_id' => $ozonOrderInfo->getExtId(),
			'paylink' => $ozonOrderInfo->getPayLink(),
			'expire' => $ozonOrderInfo->getExpiresAt(),
			'remaining_amount' => $ozonOrderInfo->getRemainingAmount()->getValue(),
			'status' => Types::getOrderStatusCode($ozonOrderInfo->getStatus()),
			'fiscaltype' => Types::getFiscalizationTypeCode($ozonOrderInfo->getFiscalizationType()),
			'payalgo' => Types::getPaymentAlgorithmCode($ozonOrderInfo->getPaymentAlgorithm()),
			'items' => []
		];
		if (($ozonOrderItems = $ozonOrderInfo->getItems()) && ($ozonOrderItems->getQuantity() > 0)) {
			$ozonOrderItems->reset();
			while ($oItem = $ozonOrderItems->getNext()) {
				$arItem = [
					'ozonId' => $oItem->getId(),
					'ext_id' => $oItem->getExtId(),
					'name' => $oItem->getName(),
					'qty' => $oItem->getQuantity(),
					'need_mark' => $oItem->isNeedMark(),
					'vat' => Types::getTaxValueCode($oItem->getVat()),
					'price' => $oItem->getPrice()->getValue(),
					'positions' => []
				];
				if (($ozonItemPositions = $oItem->getPositions()) && ($ozonItemPositions->getQuantity() > 0)) {
					$ozonItemPositions->reset();
					while ($oPos = $ozonItemPositions->getNext()) {
						$arItem['positions'][] = [
							'id' => $oPos->getId(),
							'mark' => ($oPos->getMark()) ? $oPos->getMark() : '',
							'price' => $oPos->getPrice()->getValue(),
							'reftype' => $oPos->getRefundType(),
							'remaining' => $oPos->getRemainingAmount()->getValue(),
							'mark_missing' => $oPos->getMarkIsMissing()
						];
					}
				}
				$orderInfo['items'][] = $arItem;
			}
		}

		$orderData = [
			'wp_order_id'=>intval($order_id),
			'paylink'=>$orderInfo['paylink'],
			'containsmark'=>$orderContainsMark,
			'en_fiscal'=>$this->settings['enableFiscalization'] == 'yes',
			'fiscal_type'=>intval($this->settings['fiscalizationType']),
			'payment_scheme'=>intval($this->settings['paymentAlgorithm']),
			'status'=>$orderInfo['status'],
			'confirmed'=>false,
			'finalcheck'=>false,
			'bank_order_id'=>$orderInfo['ozon_id'],
			'orderinfo'=>serialize($orderInfo),
			'orderinfo_current'=>serialize($orderInfo),
			'order_history' => (new OrderHistory())->addHistoryElement($operationHistory)->exportHistory(),
            'last_status_upd' => (new \DateTime())->format('Y-m-d H:i:s'),
		];

		$this->createOrder($orderData);

		$orderData = $this->getOrderByBankOrderId($orderInfo['ozon_id']);

		$orderNote = __('Attention', 'ipol_ozonpay') . '!' . PHP_EOL . __('All actions to manage this order should be performed from the Ozon Pay Acquiring control panel', 'ipol_ozonpay') . '.'.PHP_EOL.'<a href="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$orderData['id'].'">' . __('Order management', 'ipol_ozonpay') . '</a>';
		$order->add_order_note($orderNote);

		return array(
			'result' => 'success',
			'redirect' => $orderInfo['paylink']
		);
	}
	//Correct Order Items Data - delete TAX info && correct item Price
	private function correctWPOrder(int $order_id, array $basketItems)
	{
		$order = wc_get_order($order_id);
		foreach ($basketItems as $item) {
			//correctShipping
			if (!empty($item['type']) && ($item['type'] == 'dlv')) {
				$shipings = $order->get_shipping_methods();
				foreach ($shipings as $shipping) {
					if (intval($shipping->get_id()) == $item['id']) {
						$shipping->set_taxes([]);
						$shipping->set_total($item['price']/100);
						$shipping->save();
						break;
					}
				}
			} else {
				if (!empty($item['is_new']) && ($item['is_new'] == 'yes')) {
					$currentProduct = new \WC_Order_Item_Product($item['id']);

					$newProduct = new \WC_Order_Item_Product();
					$newProduct->set_product($currentProduct->get_product());
					$newProduct->set_variation_id($currentProduct->get_variation_id());
					$newProduct->set_order_id($currentProduct->get_order_id());
					$newProduct->set_quantity($item['qty']);
					$newProduct->set_name($item['name']);
					$newProduct->set_taxes([]);
					$newProduct->set_total($item['price']/100);
					$newProduct->save();
				} else {
					$product = new \WC_Order_Item_Product($item['id']);
					$product->set_taxes([]);
					$product->set_quantity($item['qty']);
					$product->set_total( ($item['price'] / 100 * $item['qty']) );
					$product->set_subtotal( ($item['price'] / 100 * $item['qty']) );
					$product->save();
				}
			}
		}
	}

	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$orderData = $this->getOrderByWPOrderId(intval($order_id));
		if (!$orderData)
			return new \WP_Error('1002', __('Refund error', 'ipol_ozonpay') . ": \n". __('This order data was not found', 'ipol_ozonpay') .".");

		//check is can refund
		$enableRefund = false;
		switch (intval($orderData['status'])) {
			case 4:
			case 5:
			case 6:
			case 7:
				$enableRefund = true;
				break;
		}
		if (!$enableRefund)
			return new \WP_Error('1003',__('Refund error', 'ipol_ozonpay') . ": \n" . __('The order is not in status', 'ipol_ozonpay') . ", \n" . __('which is possible for the refund procedure', 'ipol_ozonpay') . ".");

		return new \WP_Error('1000',__('To return, use the order management page', 'ipol_ozonpay') . " \n" . __('in the OZON Pay Acquiring section', 'ipol_ozonpay') . ".");
	}

	public function restFunctions() {
		$action = '';
		if (isset($_REQUEST['action'])) $action = sanitize_text_field($_REQUEST['action']);

		if ($action == 'return') {
			$bankOrderId = sanitize_text_field($_REQUEST['order_id'] ?? '');
            $cmsOrderId = intval(sanitize_text_field($_REQUEST['cms_ord_id'] ?? ''));
            if (empty($bankOrderId) && $cmsOrderId > 0) {
                $orderData = $this->getOrderByWPOrderId($cmsOrderId);
            } else {
                $orderData = $this->getOrderByBankOrderId($bankOrderId);
            }
			if (!$orderData) {
				$this->generateErrorPage([ __('Error. This order was not found', 'ipol_ozonpay') . '.'],__('Go back to main page', 'ipol_ozonpay'));
				exit();
			}

			$orderData = $this->getOzonOrderInfo($orderData);
			$isPaid = self::checkIsOrderPaid($orderData['status']);
			if ($isPaid) {
				$orderId = intval($orderData['wp_order_id']);
				wp_redirect(self::setWCOrderPaid($orderId, $bankOrderId, intval($orderData['id'])));
			} else {
				$this->generateErrorPage([ __('Error. Payment for the order failed', 'ipol_ozonpay') . '.'],__('Try to pay for the order again', 'ipol_ozonpay') . '.',$orderData['paylink']);
				exit();
			}
		}
	}

	private function generateErrorPage(array $errors, $linkText = '', $linkadr = '/')
	{
		$errorMessages = '';
		foreach ($errors as $er) $errorMessages .= "<li>{$er}</li>";

		$link = '';
		if ($linkText!='') $link = '<p><a href="'.$linkadr.'">'.$linkText.'</a></p>';

		get_header();
		echo wp_kses('<div class="woocommerce"><ul class="woocommerce-error" role="alert">'.$errorMessages.'</ul></div>
                        <div style="width:100%;height:100px;"></div>
                        '.$link.'
                        <div style="width:100%;height:100px;"></div>',[
			'div'=>[ 'class'=>[], 'style'=>[] ],
			'br'=>[],
			'ul'=>[ 'class'=>[], 'role'=>[] ],
			'li'=>[],
			'p'=>[],
			'a'=>['href'=>[]]
		]);
		get_footer();
	}

	public function getOzonOrderInfo(array $orderData, bool $dontCloseConnection = false): array
	{
		$orderInfoRequest = (new OrderInfo())->setId($orderData['bank_order_id']);
		$newOrderInfo = null;
		if ($dontCloseConnection) $newOrderInfo = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->orderDetails($orderInfoRequest);
		else $newOrderInfo = $this->ozon->orderDetails($orderInfoRequest);

		if (!$newOrderInfo->isSuccess()) {
			return [
				'error'=>true,
				'message'=> __('Error updating order information', 'ipol_ozonpay') . '.'
			];
		}

		$ozonOrderInfo = $newOrderInfo->getItem();
		$orderInfo = [
			'ozon_id' => $ozonOrderInfo->getId(),
			'number' => $ozonOrderInfo->getNumber(),
			'ext_id' => $ozonOrderInfo->getExtId(),
			'paylink' => $ozonOrderInfo->getPayLink(),
			'expire' => $ozonOrderInfo->getExpiresAt(),
			'remaining_amount' => $ozonOrderInfo->getRemainingAmount()->getValue(),
			'status' => Types::getOrderStatusCode($ozonOrderInfo->getStatus()),
			'fiscaltype' => Types::getFiscalizationTypeCode($ozonOrderInfo->getFiscalizationType()),
			'payalgo' => Types::getPaymentAlgorithmCode($ozonOrderInfo->getPaymentAlgorithm()),
			'items' => []
		];
		if (($ozonOrderItems = $ozonOrderInfo->getItems()) && ($ozonOrderItems->getQuantity() > 0)) {
			$ozonOrderItems->reset();
			while ($oItem = $ozonOrderItems->getNext()) {
				$arItem = [
					'ozonId' => $oItem->getId(),
					'ext_id' => $oItem->getExtId(),
					'name' => $oItem->getName(),
					'qty' => $oItem->getQuantity(),
					'need_mark' => $oItem->isNeedMark(),
					'vat' => Types::getTaxValueCode($oItem->getVat()),
					'price' => $oItem->getPrice()->getValue(),
					'positions' => []
				];
				if (($ozonItemPositions = $oItem->getPositions()) && ($ozonItemPositions->getQuantity() > 0)) {
					$ozonItemPositions->reset();
					while ($oPos = $ozonItemPositions->getNext()) {
						$arItem['positions'][] = [
							'id' => $oPos->getId(),
							'mark' => ($oPos->getMark()) ? $oPos->getMark() : '',
							'price' => $oPos->getPrice()->getValue(),
							'reftype' => $oPos->getRefundType(),
							'remaining' => $oPos->getRemainingAmount()->getValue(),
							'mark_missing' => $oPos->getMarkIsMissing()
						];
					}
				}
				$orderInfo['items'][] = $arItem;
			}
		}

        $updData = [
            'status'=>$orderInfo['status'],
            'orderinfo_current'=>serialize($orderInfo),
        ];
        if (intval($orderData['pay_finish']) != 1) {
            $isPaid = self::checkIsOrderPaid($orderInfo['status']);
            if ($isPaid) {
                $updData['pay_finish'] = true;
                $updData['last_status_upd'] = (new \DateTime())->format('Y-m-d H:i:s');
                self::setWCOrderPaid(
                    intval($orderData['wp_order_id']),
                    $orderData['bank_order_id'],
                    intval($orderData['id']),
                    false
                );
            }
        }

		$this->updateOrder($updData,intval($orderData['id']));

		$actualData = $this->getActualOrderData(intval($orderData['id']));
		if (isset($actualData['status'])) $orderData['status'] = $actualData['status'];
		if (isset($actualData['order_history'])) $orderData['order_history'] = $actualData['order_history'];
		if (isset($actualData['confirmed'])) $orderData['confirmed'] = $actualData['confirmed'];
		if (isset($actualData['finalcheck'])) $orderData['finalcheck'] = $actualData['finalcheck'];

		$orderData['status'] = $orderInfo['status'];
		$orderData['orderinfo_current'] = serialize($orderInfo);
		return $orderData;
	}

	private function createOrder(array $orderData)
	{
		$orderData=array_merge($orderData,[
			'created'=> (new \DateTime())->format('Y-m-d H:i:s'),
		    'updated'=> (new \DateTime())->format('Y-m-d H:i:s')
		]);
		$coltypes=[];
		foreach ($orderData as $key=>$vl) {
			$type = '%d';
			switch ($key) {
				case 'paylink':
				case 'created':
				case 'updated':
				case 'bank_order_id':
				case 'orderinfo':
				case 'orderinfo_current':
				case 'order_history':
                case 'last_status_upd':
					$type = '%s';
			}
			$coltypes[] = $type;
		}
		return $this->db->insert($this->tableName,$orderData,$coltypes);
	}

	private function getTaxCode(int $taxRate)
	{
		$value = null;
		switch ($taxRate) {
			case 0:
				$value = 1;
				break;
            case 5:
                $value = 7;
                break;
            case 7:
                $value = 8;
                break;
			case 10:
				$value = 2;
				break;
			case 20:
				$value = 3;
				break;
		}
		return $value;
	}

	public function cancelOrder($cancelData)
	{
		if (empty($cancelData['bank_order_id'])) {
			return '
				<p><b><span class="red">' . __('Cancel error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('Order not registered', 'ipol_ozonpay') . '.</p>
			';
		}
		$cancelResult = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->cancelOrder($cancelData['bank_order_id']);
		if (!$cancelResult->isSuccess()) {
			return '
				<p><b><span class="red">' . __('Cancel error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('An error occurred during the cancellation process. Please try again later', 'ipol_ozonpay') . '.</p>
			';
		}
		$orderHistory = new OrderHistory();
		$orderHistory->importHistory($cancelData['order_history']);
		$orderHistory->addHistoryElement( new HistoryElement(HistoryOperationType::ACTION_CANCEL) );
		$this->updateOrder(
			['order_history'=>$orderHistory->exportHistory()],
			intval($cancelData['id'])
		);
		return '
			<p><b><span class="green">' . __('Order cancelled', 'ipol_ozonpay') . '.</span></b></p>
		';
	}

	public function sendFinalCheck($ozonOrder, $updateMarkData)
	{
		$updateMarkData = json_decode(stripslashes($updateMarkData),true);
		if (!is_array($updateMarkData)) {
			if (!$updateMarkData) {
				return '
				<p><b><span class="red">' . __('Label update error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('Cant parse the data to update the labeling. Try the operation again', 'ipol_ozonpay') . '.</p>
			';
			}
		}

		$orderData = unserialize($ozonOrder['orderinfo_current']);

		$history = new OrderHistory();
		$history->importHistory($ozonOrder['order_history']);
		$newHistoryElement = new HistoryElement(HistoryOperationType::ACTION_FINALCHECK);

		$markItemsCollection = new BasketItemPositionCollection();

		foreach ($orderData['items'] as $ozonItem) {
			if ($ozonItem['need_mark']) {

				$productCode = explode('_',$ozonItem['ext_id']);
				$productId = 0;
				$productVarId = 0;
				if (is_array($productCode) && (count($productCode) == 3)) {
					$productId = intval($productCode[1]);
					$productVarId = intval($productCode[2]);
				}

				foreach ($ozonItem['positions'] as $position) {
					if (Types::getRefundStatusCode($position['reftype']) != 0) continue;
					foreach ($updateMarkData as $mdata) {
						if ($mdata['itm'] == $ozonItem['ozonId']) {
							foreach ($mdata['m'] as $mcode) {
								if ($mcode['i'] == $position['id']) {
									$markItemsCollection->add(
										(new BasketItemPosition())
											->setId($mcode['i'])
											->setMark($mcode['m'])
									);

									$newHistoryElement->addBasketElement(
										(new HistoryBasketElement())
											->setProductId($productId)
											->setProductVariantId($productVarId)
											->setProductName($ozonItem['name'])
											->setQuantity(1)
											->setPrice($ozonItem['price'])
											->setOtherInfo('OzonID: '.$mcode['i'].', Mark: '.$mcode['m'])
									);
									break;
								}
							}
							break;
						}
					}
				}
			}
		}

		$finalCheckRequest = (new FinalReceipt())
			->setId($ozonOrder['bank_order_id'])
			->setPositions($markItemsCollection);
		$finalCheckResponse = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->sendFinalReceipt($finalCheckRequest);
		if (!$finalCheckResponse->isSuccess()) {
			return '
				<p><b><span class="red">' . __('Error sending final check', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('An error occurred during the final check submission process. Please try again later', 'ipol_ozonpay') . '.</p>
			';
		}
		$history->addHistoryElement($newHistoryElement);
		$this->updateOrder(['order_history'=>$history->exportHistory(),'finalcheck'=>1],intval($ozonOrder['id']));
		return '
			<p><b><span class="green">' . __('The final check has been generated', 'ipol_ozonpay') . '.</span></b></p>
		';

	}

	public function updateMark($ozonOrder, $updateMarkData)
	{
		$updateMarkData = json_decode(stripslashes($updateMarkData),true);
		if (!$updateMarkData) {
			return '
				<p><b><span class="red">' . __('Label update error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('Cant parse the data to update the labeling. Try the operation again', 'ipol_ozonpay') . '.</p>
			';
		}
		$orderData = unserialize($ozonOrder['orderinfo_current']);

		$history = new OrderHistory();
		$history->importHistory($ozonOrder['order_history']);
		$newHistoryElement = new HistoryElement(HistoryOperationType::ACTION_UPDATEMARK);

		$markItemsCollection = new BasketItemPositionCollection();

		foreach ($orderData['items'] as $ozonItem) {
			if ($ozonItem['need_mark']) {
				$productCode = explode('_',$ozonItem['ext_id']);
				$productId = 0;
				$productVarId = 0;
				if (is_array($productCode) && (count($productCode) == 3)) {
					$productId = intval($productCode[1]);
					$productVarId = intval($productCode[2]);
				}
				foreach ($ozonItem['positions'] as $position) {
					if (Types::getRefundStatusCode($position['reftype']) != 0) continue;
					foreach ($updateMarkData as $mdata) {
						if ($mdata['itm'] == $ozonItem['ozonId']) {
							foreach ($mdata['m'] as $mcode) {
								if ($mcode['i'] == $position['id']) {
									$markItemsCollection->add(
										(new BasketItemPosition())
											->setId($mcode['i'])
											->setMark($mcode['m'])
									);

									$newHistoryElement->addBasketElement(
										(new HistoryBasketElement())
										->setProductId($productId)
										->setProductVariantId($productVarId)
										->setProductName($ozonItem['name'])
										->setQuantity(1)
										->setPrice($ozonItem['price'])
										->setOtherInfo('OzonID: '.$mcode['i'].', Mark: '.$mcode['m'])
									);
									break;
								}
							}
							break;
						}
					}
				}
			}
		}

		$updateMarkRequest = (new SpecifyPositions())
			->setId($ozonOrder['bank_order_id'])
			->setPositions($markItemsCollection);
		$updateMarkResponse = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->specifyPositions($updateMarkRequest);
		if (!$updateMarkResponse->isSuccess()) {
			return '
				<p><b><span class="red">' . __('Label update error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('An error occurred during the labeling update process. Please try again later', 'ipol_ozonpay') . '.</p>
			';
		}
		$history->addHistoryElement($newHistoryElement);
		$this->updateOrder(['order_history'=>$history->exportHistory()],intval($ozonOrder['id']));
		return '
			<p><b><span class="green">' . __('Labeling updated', 'ipol_ozonpay') . '.</span></b></p>
		';
	}

	public function confirmOrder($orderData,$confirmData)
	{
		$confirmData = json_decode(stripslashes($confirmData),true);
		if (!$confirmData) {
			return '
				<p><b><span class="red">' . __('Confirmation error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('Cant make out the data to confirm. Try the operation again', 'ipol_ozonpay') . '.</p>
			';
		}

		$ozonOrder = unserialize($orderData['orderinfo_current']);

		$amount = 0;
		$positionsCollection = new BasketItemPositionCollection();
		$refundedItemsInfo = '';
		$historyOperation = new HistoryElement(HistoryOperationType::ACTION_PAY_CONFIRM);
		foreach ($ozonOrder['items'] as $ozonItem) {
			$confirmedData = [];
			foreach ($confirmData as $product)
				if ($product['itm'] == $ozonItem['ozonId']) {
					$confirmedData = $product['pos'];
					break;
				}
			foreach ($ozonItem['positions'] as $position) {
				if (in_array($position['id'], $confirmedData)) {
					$positionsCollection->add(
						(new BasketItemPosition())
							->setId($position['id'])
							->setAmount(new Money(intval($position['price'])))
					);
					$amount += intval($position['price']);

					$productCode = explode('_',$ozonItem['ext_id']);
					$productId = 0;
					$productVarId = 0;
					if (is_array($productCode) && (count($productCode) == 3)) {
						$productId = intval($productCode[1]);
						$productVarId = intval($productCode[2]);
					}
					$historyOperation->addBasketElement(
						(new HistoryBasketElement())
						->setProductName($ozonItem['name'])
						->setPrice($ozonItem['price'])
						->setProductId($productId)
						->setProductVariantId($productVarId)
						->setQuantity(1)
						->setOtherInfo('OzonID: '.$position['id'])
					);

				} else {
					$positionsCollection->add(
						(new BasketItemPosition())
							->setId($position['id'])
							->setAmount(new Money(0))
					);
					$refundedItemsInfo .= "\r\n" . $ozonItem['name'] . ', ' . (intval($position['price']) / 100) . ' ' . 'RUB';
				}
			}
		}

		$confirmRequest = (new Confirm())
			->setId($orderData['bank_order_id'])
			->setAmount(new Money($amount))
			->setPositions($positionsCollection);

		$confirmResult = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->confirmOrder($confirmRequest);
		if (!$confirmResult->isSuccess()) {
			return '
				<p><b><span class="red">' . __('Confirmation error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('An error occurred during the confirmation process. Please try again later', 'ipol_ozonpay') . '.</p>
			';
		}
		$history = new OrderHistory();
		$history->importHistory($orderData['order_history']);
		$history->addHistoryElement($historyOperation);
		$this->updateOrder([ 'confirmed'=>1, 'order_history'=>$history->exportHistory() ],intval($orderData['id']));

		//Load WP order
		$order = new \WC_Order(intval($orderData['wp_order_id']));
		$confirmNote = '';
		if ($amount > 0) {
			$order->update_status('processing', __('Order has been paid', 'ipol_ozonpay') . '.');
			$order->payment_complete($orderData['bank_order_id']);
			$confirmNote = __('Order confirmation made', 'ipol_ozonpay') . '.';
			if ($refundedItemsInfo != '') $confirmNote .= PHP_EOL . __('The following items were returned during the confirmation process', 'ipol_ozonpay') . ':' . $refundedItemsInfo;
		} else {
			$order->update_status('refunded', __('Order holding canceled, goods returned', 'ipol_ozonpay') . '.');
			if ($refundedItemsInfo != '') $confirmNote = __('The following items were returned during the confirmation process', 'ipol_ozonpay') . ':' . $refundedItemsInfo;
		}
		$order->add_order_note( $confirmNote );
		$order->save();

		return '
			<p><b><span class="green">' . __('Confirmation completed', 'ipol_ozonpay') . '.</span></b></p>
		';
	}

	public function refund($orderData, $refundData) {
		$refundData = json_decode(stripslashes($refundData),true);
		if (!$refundData) {
			return '
				<p><b><span class="red">' . __('Refund error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('Cant make out the data to refund. Try the operation again', 'ipol_ozonpay') . '.</p>
			';
		}

		$ozonOrder = unserialize($orderData['orderinfo_current']);

		$history = new OrderHistory();
		$history->importHistory($orderData['order_history']);
		$newHistoryElement = new HistoryElement(HistoryOperationType::ACTION_REFUND);

		$amount = 0;
		$positionsCollection = new BasketItemPositionCollection();
		$isRefundWithoutMark = false;
		$positionsWithoutMark = new BasketItemPositionCollection();

		foreach ($ozonOrder['items'] as $ozonItem) {
			$refundedData = [];
			$productFirstId = $ozonItem['positions'][0]['id'];
			$productFirstPrice = intval($ozonItem['positions'][0]['price']);
			$withoutMarkItemsCount = 0;

			$productCode = explode('_',$ozonItem['ext_id']);
			$productId = 0;
			$productVarId = 0;
			if (is_array($productCode) && (count($productCode) == 3)) {
				$productId = intval($productCode[1]);
				$productVarId = intval($productCode[2]);
			}

			foreach ($refundData as $product) {
				if ($product['itm'] == $ozonItem['ozonId']) {
					$refundedData = $product['pos'];
					$withoutMarkItemsCount = $product['addcnt'];
					break;
				}
			}
			foreach ($ozonItem['positions'] as $position) {
				if (in_array($position['id'], $refundedData)) {
					$positionsCollection->add(
						(new BasketItemPosition())
							->setId($position['id'])
							->setAmount(new Money(intval($position['price'])))
					);
					$amount += intval($position['price']);

					$newHistoryElement->addBasketElement(
						(new HistoryBasketElement())
							->setProductId($productId)
							->setProductVariantId($productVarId)
							->setProductName($ozonItem['name'])
							->setQuantity(1)
							->setPrice($ozonItem['price'])
							->setOtherInfo('OzonID: '.$position['id'])
					);
				}
			}

			for ($i = 0; $i < $withoutMarkItemsCount; $i++) {
				$isRefundWithoutMark = true;
				$positionsWithoutMark->add(
					(new BasketItemPosition())
						->setId($ozonItem['ozonId'])
						->setAmount(new Money($productFirstPrice))
				);
				$amount += $productFirstPrice;

				$newHistoryElement->addBasketElement(
					(new HistoryBasketElement())
						->setProductId($productId)
						->setProductVariantId($productVarId)
						->setProductName($ozonItem['name'])
						->setQuantity(1)
						->setPrice($ozonItem['price'])
						->setOtherInfo('UnknownMark')
				);
			}

		}

		$refundRequest = (new Refund())
			->setId($orderData['bank_order_id'])
			->setAmount(new Money($amount))
			->setPositions($positionsCollection);
		if ($isRefundWithoutMark) $refundRequest->setItemsWithoutMark($positionsWithoutMark);

		$refundResult = $this->ozon->setDoNotCloseConnectionAfterRequest(true)->refund($refundRequest);

		if (!$refundResult->isSuccess()) {
			return '
				<p><b><span class="red">' . __('Refund error', 'ipol_ozonpay') . '!</span></b></p>
				<p>' . __('An error occurred during the refund process. Please try again later', 'ipol_ozonpay') . '.</p>
			';
		}

		$history->addHistoryElement($newHistoryElement);
		$this->updateOrder(['order_history'=>$history->exportHistory()],intval($orderData['id']));
		return '
			<p><b><span class="green">' . __('Refund has been made.', 'ipol_ozonpay') . '.</span></b></p>
		';

	}

	public function checkOrdersConfirmed() {
		$orders = $this->db->get_results("SELECT * FROM `{$this->tableName}` WHERE `payment_scheme` = 2 AND `confirmed` = 0 AND `status` = " . WPTypes::getOrderStatusCode('STATUS_AUTHORIZED') . " AND `created` > '" . (new \DateTime() )->modify('-6 days')->format('Y-m-d H:i:s') ."';",'ARRAY_A');

		$lastKey = array_key_last($orders);
		foreach ($orders as $key => $order) {
			$doNotCloseConnection = true;
			if ($key == $lastKey) $doNotCloseConnection = false;
			$this->getOzonOrderInfo($order,$doNotCloseConnection);
		}
	}

    public static function checkIsOrderPaid(string $orderStatus): bool
    {
        $isPaid = false;
        switch (Types::getOrderStatus($orderStatus)) {
            case 'STATUS_PAID':
            case 'STATUS_AUTHORIZED':
                $isPaid = true;
                break;
        }
        return $isPaid;
    }

    public function setWCOrderPaid(int $orderId, string $bankOrderId, int $orderKey, bool $orderUpdate = true):string
    {
        global $woocommerce;
        $order = new \WC_Order($orderId);
        $cart = $woocommerce->cart;
        if ($cart) $cart->empty_cart();
        $order->update_status('processing', __('Order has been paid', 'ipol_ozonpay') . '.');
        $order->payment_complete($bankOrderId);
        //update order status paidComplete
        if ($orderUpdate) $this->updateOrder([
            'last_status_upd' => (new \DateTime())->format('Y-m-d H:i:s'),
            'pay_finish' => true,
        ], $orderKey);
        return $this->get_return_url($order);
    }

    public function checkOrdersPayFinish()
    {
        $orders = $this->getOrdersForPaymentFinish();
        foreach ($orders as $orderData) {
            $orderData = $this->getOzonOrderInfo($orderData);
            $isPaid = self::checkIsOrderPaid($orderData['status']);
            if ($isPaid) {
                self::setWCOrderPaid(
                    intval($orderData['wp_order_id']),
                    $orderData['bank_order_id'],
                    intval($orderData['id'])
                );
            }
        }
    }

}
