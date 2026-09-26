<?php

namespace Ipol\OzonPay\WordPress;

use Ipol\OzonPay\PayHistory\OrderHistory;
use Ipol\OzonPay\PayHistory\OrderHistoryTemplate;
use Ipol\OzonPay\SDK\Other\Types;

class OzonPayPlugin
{
	use DBTrait;

	private static $countOrderInPage = 10;

	private static $instance;
	private static $pluginDir;
	private $pluginHandler;
	private $mainPluginFile;

	public static function getInstance() {
		if ( ! self::$instance ) {
			global $wpdb;
			self::$instance                 = new self();
			self::$instance::$pluginDir     = dirname(dirname( __DIR__ ));
			self::$instance->pluginHandler  = __FILE__;
			self::$instance->mainPluginFile = self::$instance::$pluginDir . DIRECTORY_SEPARATOR . 'ipol_ozonpay.php';
			self::$instance->db             = $wpdb;
			self::$instance->tableName      = self::$instance->db->get_blog_prefix().'ozonpay_orders';
		}

		return self::$instance;
	}

	public static function getPluginDir(): string {
		if ( ! self::$pluginDir ) {
			self::$pluginDir = dirname(dirname( __DIR__ ));
		}

		return self::$pluginDir;
	}

	public function run() {
        //CompabilityWithWOOBlocks
        add_action( 'woocommerce_blocks_loaded', function () {
            if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
                add_action(
                    'woocommerce_blocks_payment_method_type_registration',
                    function( \Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
                        $payment_method_registry->register( new OzonPaymentMethod );
                    }
                );
            }
        } );

        //Install-uninstall
		register_activation_hook( $this->mainPluginFile, [ $this, 'registerActivationHook' ] );
		register_deactivation_hook( $this->mainPluginFile, [ $this, 'registerDeactivationHook' ] );
		register_uninstall_hook( $this->mainPluginFile, [ self::class, 'moduleUninstall' ] );

		add_filter( 'woocommerce_payment_gateways', [ $this, 'paymentGateways' ] );

		load_textdomain('ipol_ozonpay',OzonPayPlugin::getPluginDir() . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'ipol_ozonpay-ru_RU.mo');

		add_action('admin_menu', function(){
			add_submenu_page(
				'woocommerce',
				__('Payments Ozon Pay', 'ipol_ozonpay'),
				__('Payments Ozon Pay', 'ipol_ozonpay'),
				'manage_options',
				'ipol_ozonpay_main',
				[self::class,'renderManageOrdersPages']
			);
		});

		add_action('wp', [$this, 'actionWP']);
		add_action('ipol_ozonpay_checkorders_event', [$this, 'actionTwoFactorOrders']);
		add_action('ipol_ozonpay_payfinish_event', [$this, 'actionCheckPayFinishAction']);

        add_filter( 'cron_schedules', function ($schedules) {
            $schedules['five_min'] = array(
                'interval' => 300,
                'display'  => 'Раз в 5 минут'
            );
            return $schedules;
        } );
	}

	public function registerActivationHook() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->get_blog_prefix()."ozonpay_orders` (
		`id` int(11) unsigned NOT NULL auto_increment,
		`wp_order_id` int(11) NOT NULL,
		`paylink` varchar(255) NOT NULL,
		`created` datetime,
		`updated` datetime,
		`containsmark` tinyint(1) unsigned NOT NULL,
		`en_fiscal` tinyint(1) unsigned NOT NULL,
		`fiscal_type` tinyint(1) unsigned NOT NULL,
		`payment_scheme` tinyint(1) unsigned NOT NULL,
		`status` tinyint(1) unsigned NOT NULL,
		`confirmed` tinyint(1) unsigned NOT NULL,
		`finalcheck` tinyint(1) unsigned NOT NULL,
		`bank_order_id` varchar(50) NOT NULL,
		`orderinfo` text NOT NULL,
		`orderinfo_current` text NOT NULL,
		`order_history` text NOT NULL,
		`last_status_upd` datetime,
		`pay_finish` tinyint(1) unsigned NOT NULL,
		PRIMARY KEY (`id`),
		INDEX IX_OZONPAY_ORDERS_WP_ORDER_ID (`wp_order_id`),
        INDEX IX_OZONPAY_ORDERS_BANK_ORDER_ID (`bank_order_id`)
		) DEFAULT CHARSET=".$this->db->charset.";");

		wp_clear_scheduled_hook('ipol_ozonpay_checkorders_event');
		wp_schedule_event(time(), 'daily', 'ipol_ozonpay_checkorders_event');
		wp_schedule_event(time(), 'five_min', 'ipol_ozonpay_payfinish_event');
	}

	public function registerDeactivationHook() {
		wp_clear_scheduled_hook('ipol_ozonpay_checkorders_event');
		wp_clear_scheduled_hook('ipol_ozonpay_payfinish_event');
	}

	public static function moduleUninstall() {
		//Nothing here yet...
	}

	public function paymentGateways( $gateways ) {
		$gateways['ozonpayment'] = '\Ipol\OzonPay\WordPress\OzonPayment';
		return $gateways;
	}

	public static function renderManageOrdersPages()
	{
		wp_enqueue_style('ipol-ozonpay-style',plugin_dir_url( self::$instance->mainPluginFile ).'assets/css/admin.css',[],'1.0');

		$htmlclasses=[
			'h1'=>[],
			'h2'=>[],
			'h3'=>[],
			'p'=>[ 'class'=>[] ],
			'b'=>[ ],
			'button'=>[ 'class'=>[], 'type'=>[] ],
			'div'=>[ 'class'=>[], ],
			'table'=>[ 'class'=>[], 'pi'=>[], 'pr'=>[] ],
			'th'=>[],
			'tr'=>[ 'class'=>[], 'pi'=>[] ],
			'td'=>[ 'class'=>[], 'colspan'=>[] ],
			'a'=>[ 'href'=>[], 'class'=>[], 'target'=>[] ],
			'strong'=>[],
			'br'=>[ 'class'=>[] ],
			'span'=>[ 'class'=>[] ],

			'input'=>[ 'type'=>[], 'value'=>[], 'name'=>[], 'class'=>[], 'initmax'=>[], 'min'=>[], 'max'=>[] ],
			'form'=>['action'=>[],'method'=>[],'id'=>[]],
			'label'=>[ 'class'=>[] ],
			'svg'=>[ 'xmlns'=>[],'viewbox'=>[],'width'=>[],'height'=>[] ],
			'path'=>['d'=>[]]

		];

		if (isset($_REQUEST['order'])) $page = self::renderOrderManagePage();
		else $page = self::renderMainAdminPage();

		echo wp_kses($page,$htmlclasses);
		wp_enqueue_script( 'rsb-admin', plugin_dir_url( self::$instance->mainPluginFile ) . 'assets/js/admin.js', [], '1.0' );
	}

	private static function renderMainAdminPage(): string
	{
		$ordersCount = self::$instance->getCountOrders();
		$pages = intval(ceil($ordersCount  / self::$countOrderInPage));
		$currentPage = 1;
		if (isset($_REQUEST['pagenum']))
		$currentPage = intval(sanitize_text_field($_REQUEST['pagenum']));
			if ($currentPage == 0) $currentPage = 1;

		$orders = self::$instance->getOrders($currentPage,self::$countOrderInPage);

		$html = '
                <div class="wrap">
                <h1>' . __('Payments Ozon Pay', 'ipol_ozonpay') . '</h1>
                <table class="wp-list-table widefat fixed striped table-view-list pages ipol_ozonpay_manageorders">
                <tr class="table_header">
                <td class="manage-column ozonpay_col1">##</td>
                <td class="manage-column ozonpay_col2">' .__('Order no.', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col3">' .__('OZON Pay order ID', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col4">' .__('Status', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col5">' .__('Contains markings', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col6">' .__('Fiscalization','ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col7">' .__('Fiscalization scheme', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col8">' .__('Payment type', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col9">' .__('Confirmed', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col10">' .__('Final check', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col11">' .__('Updated', 'ipol_ozonpay'). '</td>
                <td class="manage-column ozonpay_col12"></td>
                </tr>';

		if (empty($orders)) {
			$html .= '
				<tr class="iedit author-self level-0 type-page status-publish hentry orderrow">
				<td class="manage-column" colspan="12">' .__('There are no orders here yet', 'ipol_ozonpay'). '...</td>
				</tr>
			';
		} else {
			foreach ($orders as $order) {
				$order['status'] = Types::getOrderStatusRus(intval($order['status']));
				$order['containsmark'] = ( $order['containsmark'] == '1' ) ? __('Yes', 'ipol_ozonpay') : __('No', 'ipol_ozonpay');

				$order['finalcheck'] = ( $order['finalcheck'] == '1' )? __('Formed', 'ipol_ozonpay') : __('Is not sent', 'ipol_ozonpay');
				if (($order['fiscal_type'] != '2') && ($order['en_fiscal']!='1')) $order['finalcheck'] = ' - ';

				if ($order['fiscal_type'] == '1') $order['fiscal_type'] = __('Single', 'ipol_ozonpay');
				if ($order['fiscal_type'] == '2') $order['fiscal_type'] = __('Double', 'ipol_ozonpay');
				if ($order['en_fiscal']!='1') $order['fiscal_type'] = ' - ';
				$order['en_fiscal'] = ( $order['en_fiscal'] == '1' )? __('Enabled', 'ipol_ozonpay') : __('No', 'ipol_ozonpay');


				$order['confirmed'] = ( $order['confirmed'] == '1' )? __('Yes', 'ipol_ozonpay') : __('No', 'ipol_ozonpay');
				if ($order['payment_scheme'] != '2') $order['confirmed'] = ' - ';
				if ($order['payment_scheme'] == '1') $order['payment_scheme'] = __('Single stage', 'ipol_ozonpay');
				if ($order['payment_scheme'] == '2') $order['payment_scheme'] = __('Two-stage', 'ipol_ozonpay');

				$order['updated'] = \DateTime::createFromFormat('Y-m-d H:i:s',$order['updated'])->format('m.d.Y H:i:s');

				$html .= '
					<tr class="iedit author-self level-0 type-page status-publish hentry orderrow">
						<td class="manage-column ozonpay_col1">'.$order['id'].'</td>
		                <td class="manage-column ozonpay_col2">'.$order['wp_order_id'].'</td>
		                <td class="manage-column ozonpay_col3">'.$order['bank_order_id'].'</td>
		                <td class="manage-column ozonpay_col4">'.$order['status'].'</td>
		                <td class="manage-column ozonpay_col5">'.$order['containsmark'].'</td>
		                <td class="manage-column ozonpay_col6">'.$order['en_fiscal'].'</td>
		                <td class="manage-column ozonpay_col7">'.$order['fiscal_type'].'</td>
		                <td class="manage-column ozonpay_col8">'.$order['payment_scheme'].'</td>
		                <td class="manage-column ozonpay_col9">'.$order['confirmed'].'</td>
		                <td class="manage-column ozonpay_col10">'.$order['finalcheck'].'</td>
		                <td class="manage-column ozonpay_col11">'.$order['updated'].'</td>
		                <td class="manage-column ozonpay_col12"><a href="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$order['id'].'">' .__('Actions with the order', 'ipol_ozonpay'). '</a></td>
					</tr>
				';
			}
		}
		$html .= '</table>';

		if (!empty($orders)) $html .= '
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num">' .__('Total orders','ipol_ozonpay'). ': '.$ordersCount.'</span>
				<div class="pagination-links">
				'.paginate_links([
					'base'=>'/wp-admin/admin.php?page=ipol_ozonpay_main%_%',
					'format'=>'&pagenum=%#%',
					'total'=>$pages,
					'current'=>$currentPage,
				]).'
				</div>
			</div>
			<br class="clear">
		</div>
		';

		$html .='</div>';
		return $html;
	}

	private static function renderOrderManagePage(): string
	{
		wp_enqueue_style('ipol-ozonpay-style',plugin_dir_url( self::$instance->mainPluginFile ).'assets/css/admin.css',[],'1.0');
		$recordId = intval(sanitize_text_field($_REQUEST['order']));

		$orderData = self::$instance->getOrderByRecordId($recordId);

		$ozonPay = new OzonPayment();
		$operationResult = '';
		if (isset($_REQUEST['action'])) {
			$actionName = sanitize_text_field($_REQUEST['action']);

			switch ($actionName) {
				case 'cancel':
					$orderData = $ozonPay->getOzonOrderInfo($orderData, true);
					$operationResult = $ozonPay->cancelOrder($orderData);
					sleep(2);
					break;
				case 'sendfincheck':
					$orderData = $ozonPay->getOzonOrderInfo($orderData, true);
					$operationResult = $ozonPay->sendFinalCheck($orderData,sanitize_text_field($_REQUEST['value']));
					sleep(2);
					break;
				case 'renewMark':
					$orderData = $ozonPay->getOzonOrderInfo($orderData, true);
					$operationResult = $ozonPay->updateMark($orderData,sanitize_text_field($_REQUEST['value']));
					sleep(2);
					break;
				case 'confirm':
					$orderData = $ozonPay->getOzonOrderInfo($orderData, true);
					$operationResult = $ozonPay->confirmOrder($orderData,sanitize_text_field($_REQUEST['value']));
					sleep(2);
					break;
				case 'refund':
					$orderData = $ozonPay->getOzonOrderInfo($orderData, true);
					$operationResult = $ozonPay->refund($orderData,sanitize_text_field($_REQUEST['value']));
					sleep(2);
					break;

			}
		}

		$updateInfoError = '';
		$newOrderData = $ozonPay->getOzonOrderInfo($orderData);
		if (!empty($newOrderData['error'])) {
			$updateInfoError = '
				<p><b><span class="red">' .__('Error updating order status', 'ipol_ozonpay'). '!</span></b></p>
				<p>' .__('OZON Pay server is not available or access keys are incorrect', 'ipol_ozonpay'). '.</p>
			';
		} else $orderData = $newOrderData;
		$ozonOrder = unserialize($orderData['orderinfo_current']);

		$isEnCheck = false;
		if ($orderData['en_fiscal'] == '1') $isEnCheck = true;

		$isFinalCheckDone = false;
		if ($orderData['finalcheck'] == '1') $isFinalCheckDone = true;

		$isEnFinalCheck = false;
		if ( $isEnCheck && ($orderData['fiscal_type'] == '2') && (in_array(intval($orderData['status']),[4,5,6,7])) ) $isEnFinalCheck = true;

		$isDoublePayScheme = false;
		if ($orderData['payment_scheme'] == '2') $isDoublePayScheme = true;

		$isDoublePayConfirmed = false;
		if ($orderData['confirmed'] == '1') $isDoublePayConfirmed = true;

		$enableCancel = false;
		$enableRefund = false;
		$enableConfirm = false;

		if ( ($ozonPay->settings['isServiceMode'] == 'yes') && (intval($orderData['status']) == 1) ) {
			$enableCancel = true;
		}

		$enableMark = false;
		if ( ($orderData['containsmark'] == '1') && $isEnCheck && $isEnFinalCheck ) $enableMark = true;

		if ( $isDoublePayScheme && !$isDoublePayConfirmed && (
				( intval($orderData['status']) == 4 ) || ( intval($orderData['status']) == 5 )
			) ) {
			$enableConfirm = true;
		}

		switch (intval($orderData['status'])) {
			case 4:
			case 5:
			case 6:
			case 7:
				$enableRefund = true;
				break;
		}

		$productsHtml = '<div class="ipol_order_products">';
		foreach ($ozonOrder['items'] as $item) {

			$enableItemMarkFunctions = $item['need_mark'];
			if (!$enableMark) $enableItemMarkFunctions = false;

			$productsHtml .= '<div class="ipol_order_product"><div>';

			$markHeader = '';
			if ($enableItemMarkFunctions) $markHeader = '<th>' .__('Ozon marking', 'ipol_ozonpay'). '</th>'.(!$isFinalCheckDone?'<th>'.__('Current marking', 'ipol_ozonpay').'</th>':'').'<th>' .__('Note', 'ipol_ozonpay'). '</th>';


			$positionsInfo = "
				<table class=\"ipol_ozonpay_positioninfo".($enableItemMarkFunctions?' marked':'')."\" pi=\"{$item['ozonId']}\" pr=\"{$item['price']}\">
					<tr>
						<th>ID</th><th>" .__('Refund status', 'ipol_ozonpay'). "</th>" . $markHeader . ( $enableConfirm ? '<th>' .__('Confirmation', 'ipol_ozonpay'). '</th>' : '' ) . ( $enableRefund ? '<th>'.__('Refund', 'ipol_ozonpay').'</th>' : '' ) . "
					</tr>
			";

			$positionCount = 0;
			$refundedCount = 0;
			$unknownMarkRefunded = 0;
			$canRefundCount = 0;
			foreach ($item['positions'] as $position) {
				if ($position['reftype']=='TYPE_FULL') $refundedCount++;
				else $canRefundCount++;

				if (!$position['mark_missing']) $positionCount++;
				else $unknownMarkRefunded++;
			}
			foreach ($item['positions'] as $position) {
				$typeRefund = Types::getRefundStatusRusByCode($position['reftype']);
				switch (Types::getRefundStatusCode($position['reftype'])) {
					case 0:
						$typeRefund = '<span class="green">' . $typeRefund . '</span>';
						break;
					case 1:
						$typeRefund = '<span class="red">' . $typeRefund . '</span>';
						break;
					case 3:
						$typeRefund = '<span class="yellow">' . $typeRefund . '</span>';
				}
				$markInfo = '';
				if ($enableItemMarkFunctions) {
					$markInfo = '<td>' . (($position['mark'] != '') ? $position['mark'] : '<span class="red">'.__('Marking not specified', 'ipol_ozonpay').'</span>') . '</td>';
					if ($position['mark_missing']) {
						$markInfo .= '<td><span class="red">' .__('Item refunded with unknown markings', 'ipol_ozonpay'). '</span></td>';
					} else {
						$markInfo .= (!$isFinalCheckDone ?( ( $position['reftype'] != 'TYPE_FULL' )?'<td><input type="text" value="'.$position['mark'].'" /></td>':'<td class="emp">-</td>' ):'').'<td></td>';
					}
				}
				$confirm = '';
				if ($enableConfirm) $confirm = ( $position['reftype'] != 'TYPE_FULL' )?'<td class="centered"><label class="ipol_ozonpay_checkconfirm"><input type="checkbox" ></label></td>':'<td class="emp">-</td>';

				$refund = '';
				if ($enableRefund) {
					$refund = '<td class="emp">-</td>';
					if ( ($position['reftype'] != 'TYPE_FULL') && (($canRefundCount - $unknownMarkRefunded) > 0) ) $refund = '<td class="centered"><label class="ipol_ozonpay_checkrefund"><input type="checkbox" ></label></td>';
				}

				$positionsInfo .= "<tr class='r' pi=\"{$position['id']}\"><td>{$position['id']}</td><td>{$typeRefund}</td>{$markInfo}{$confirm}{$refund}</tr>";

			}
			$positionsInfo .= '</table>';
			$item['need_mark'] = ($item['need_mark']) ? __('Yes', 'ipol_ozonpay') : __('No', 'ipol_ozonpay');

			$productsHtml .= "<p><b>".__('Name', 'ipol_ozonpay').": </b>{$item['name']}</p>";
			$productsHtml .= "<p><b>" .__('Cost for 1 unit', 'ipol_ozonpay'). ": </b>".(intval($item['price'])/100)." ".__('RUB','ipol_ozonpay')."</p>";
			$productsHtml .= "<p><b>" .__('Quantity', 'ipol_ozonpay'). ": </b>{$positionCount}</p>";
			$productsHtml .= "<p><b>" .__('Refunded', 'ipol_ozonpay'). ": </b>{$refundedCount}</p>";
			$productsHtml .= "<p><b>" .__('Labeled product', 'ipol_ozonpay'). ": </b>{$item['need_mark']}</p>";
			$productsHtml .= "<p><b>" .__('Details', 'ipol_ozonpay'). ": </b></p>" . $positionsInfo;

			if ($enableItemMarkFunctions) {
				$productsHtml .= "<div class=\"unknown_mark_refund\">";
				if ($isFinalCheckDone && $enableRefund && (($canRefundCount - $unknownMarkRefunded) > 0)) {
					$productsHtml .= "<p>".__('If the markings of the goods are unknown, enter here the number of goods to be refunded with unknown markings', 'ipol_ozonpay').".</p><input type=\"number\" min=\"0\" max=\"" . ($canRefundCount - $unknownMarkRefunded) . "\" initmax=\"" . ($canRefundCount - $unknownMarkRefunded) . "\" value=\"0\" />";
				}

				if ($unknownMarkRefunded > 0)
						$productsHtml .= "<p><span class='red'>".__('Attention','ipol_ozonpay')."!</span> ".__('Previously, a product with an unknown marking was returned in the amount of', 'ipol_ozonpay')." {$unknownMarkRefunded} " . __('units', 'ipol_ozonpay') . "</p><p>".__('Maximum available quantity of goods for return', 'ipol_ozonpay').": " . ($canRefundCount - $unknownMarkRefunded) . " ".__('units', 'ipol_ozonpay')."</p>";

				$productsHtml .= "</div>";
			}

			if ($enableRefund) $productsHtml .= "<p class=\"product_refund_total\"><b>".__('Total to be refunded','ipol_ozonpay').": </b><span>0</span> ".__('RUB','ipol_ozonpay')."</p>";
			$productsHtml .= "</div></div>";
		}
		$productsHtml .= '</div>';


		$orderStatusRus = WPTypes::getOrderStatusRus(intval($orderData['status']));
		switch (intval($orderData['status'])) {
			case 0:
			case 4:
			case 5:
			case 7:
				$orderStatusRus = '<span class="yellow">' . $orderStatusRus . '</span>';
				break;

			case 2:
			case 3:
			case 8:
			case 9:
				$orderStatusRus = '<span class="red">' . $orderStatusRus . '</span>';
				break;

			case 6:
				$orderStatusRus = '<span class="green">' . $orderStatusRus . '</span>';
				break;

			default:
				$orderStatusRus = '<span>' . $orderStatusRus . '</span>';
		}

		$history = new OrderHistory();
		$history->importHistory($orderData['order_history'] ?? '');

		$historyTemplate = (new OrderHistoryTemplate())
			->setElementTextTemplate("<p><b>#dtm#</b>: #pay_operation#</p><div class='ipol_ozonpay_history_operations'>#basket_elements#</div><div class='ipol_ozonpay_line'></div>")
			->setBasketElementTemplate("<p class='ipol_ozonpay_history_operations_product'><b>#nn#</b>: #product_name# [id: #product_id#], " . __('cost', 'ipol_ozonpay') . " <b>#product_price#</b> " . __('rub for 1 unit, quantity', 'ipol_ozonpay') . " <b>#product_quantity#</b> ".__('units', 'ipol_ozonpay')." #product_info#</p>");
		$historyInfo = $history->renderHistoryText($historyTemplate);

		$actionPanel = '';
		if ( empty($updateInfoError) && (
			$enableCancel
			|| $enableConfirm
			|| ($enableMark && ($isEnFinalCheck && !$isFinalCheckDone))
			|| ($isEnFinalCheck && !$isFinalCheckDone)
			|| $enableRefund
		) ) $actionPanel = '
			<div class="ipol_ozonpay_toppanel actionpanel">
				<h3>'.__('Actions with the order', 'ipol_ozonpay').'</h3>			
				<div class="tbl">
					'.( $enableCancel ? '<button class="ipol_ozonpay_btn cancelpaybtn">'.__('Cancellation of payment','ipol_ozonpay').'</button>' : '' ).'
					'.( $enableConfirm ? '<button class="ipol_ozonpay_btn confirmpaybtn">'.__('Confirm two-step payment','ipol_ozonpay').'</button>' : '' ).'
					'.( ($enableMark && ($isEnFinalCheck && !$isFinalCheckDone)) ? '<button class="ipol_ozonpay_btn refreshmarkbtn">'.__('Specify marking codes','ipol_ozonpay').'</button>' : '' ).'
					'.( ($isEnFinalCheck && !$isFinalCheckDone)? '<button class="ipol_ozonpay_btn sendfinalcheckbtn">'.__('Generate final check','ipol_ozonpay').'</button>' : '' ).'
					'.( $enableRefund ? '<button class="ipol_ozonpay_btn refundbtn">'.__('Refund','ipol_ozonpay').'</button>' : '' ).'
				</div>
			</div>
		';

		$page = '
			
<div class="ipol_ozonpay_toppanel main">
	<h2>'.__('Information and order management','ipol_ozonpay').' № '.$orderData['wp_order_id'].'</h2>
	'.(empty($updateInfoError)?'<p>'.__('Current order information has been updated','ipol_ozonpay').'.</p>':$updateInfoError).'
	'.$operationResult.'
</div>

<form id="cancel_form" method="post" action="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$recordId.'">
	<input type="hidden" name="action" value="cancel">
</form>
<form id="fincheck_form" method="post" action="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$recordId.'">
	<input type="hidden" name="action" value="sendfincheck">
	<input type="hidden" name="value" value="[]">
</form>
<form id="update_mark_form" method="post" action="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$recordId.'">
	<input type="hidden" name="action" value="renewMark">
	<input type="hidden" name="value" value="">
</form>
<form id="confirm_form" method="post" action="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$recordId.'">
	<input type="hidden" name="action" value="confirm">
	<input type="hidden" name="value" value="">
</form>
<form id="refund_form" method="post" action="/wp-admin/admin.php?page=ipol_ozonpay_main&order='.$recordId.'">
	<input type="hidden" name="action" value="refund">
	<input type="hidden" name="value" value="">
</form>
<div class="ipol_ozonpay_orderpage">
	<p class="h">'.__('General information','ipol_ozonpay').'</p>
	
	<p><b>'.__('WordPress order no.','ipol_ozonpay').': </b>'.$orderData['wp_order_id'].'</p>
	<p><b>'.__('Bank order number','ipol_ozonpay').': </b>'.$orderData['bank_order_id'].'</p>
	<p><b>'.__('Available for payment up to','ipol_ozonpay').': </b>'.$ozonOrder['expire'].'</p>
	<p><b>'.__('Payment status','ipol_ozonpay').': </b>'.$orderStatusRus.'</p>
	<p><b>'.__('Fiscalization enabled','ipol_ozonpay').': </b>'.( $isEnCheck ? __('Yes', 'ipol_ozonpay') : __('No', 'ipol_ozonpay') ).'</p>
	'.( $isEnCheck ? '<p><b>'.__('Type of fiscalization','ipol_ozonpay').': </b>'.( ($orderData['fiscal_type'] == '1')?__('Single','ipol_ozonpay'):'' ).( ($orderData['fiscal_type'] == '2')?__('Double','ipol_ozonpay'):'' ).'</p>' : '' ).'
	<p><b>'.__('Payment scheme','ipol_ozonpay').': </b>'.($isDoublePayScheme?__('Two-stage','ipol_ozonpay'):__('Single stage','ipol_ozonpay')).'</p>

	<p class="h">'.__('Goods and services','ipol_ozonpay').':</p>
	
	'.$productsHtml.'
	
	<div class="actions_info">
		'.( $enableConfirm ? '<p class="total_confirm"><b>'.__('Total to confirm','ipol_ozonpay').': </b><span>0</span> '.__('RUB','ipol_ozonpay').'</p>' : '' ).'
		'.( $enableRefund ? '<p class="total_refund"><b>'.__('Total to refund','ipol_ozonpay').': </b><span>0</span> '.__('RUB','ipol_ozonpay').'</p>' : '' ).'
	</div>
	
	<p class="h">'.__('Order transaction history','ipol_ozonpay').':</p>
	'.( empty($historyInfo)?'<div class="order_history"><p>'.__('No transaction history','ipol_ozonpay').'</p></div>':'<div class="order_history">'.$historyInfo.'</div>' ).'
	
</div>
'.$actionPanel.'
<div class="ppwinbg"></div>
<div class="ppwin cancelwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p><b>'.__('Do you want to cancel the payment','ipol_ozonpay').'?</b></p>
	<p>'.__('This operation cannot be undone','ipol_ozonpay').'.</p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn processbtn">'.__('Confirm','ipol_ozonpay').'</button>
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>
<div class="ppwin sendcheckwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p><b>'.__('Send final check','ipol_ozonpay').'?</b></p>
	<p>'.__('This operation cannot be undone','ipol_ozonpay').'.</p>
	<p>'.__('Attention! When sending the final check, the marking information contained in the “Current marking” field will be transmitted. Please check the data is correct before sending the final check.','ipol_ozonpay').'.</p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn processbtn">'.__('Send','ipol_ozonpay').'</button>
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>
<div class="ppwin sendmarkwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p><b>'.__('Update labeling','ipol_ozonpay').'?</b></p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn processbtn">'.__('Update','ipol_ozonpay').'</button>
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>
<div class="ppwin confirmwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p><b>'.__('Perform payment confirmation','ipol_ozonpay').'?</b></p>
	<p>'.__('This operation cannot be undone','ipol_ozonpay').'.</p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn processbtn">'.__('Confirm','ipol_ozonpay').'</button>
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>

<div class="ppwin refundwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p><b>'.__('Perform a refund','ipol_ozonpay').'?</b></p>
	<p>'.__('This operation cannot be undone','ipol_ozonpay').'.</p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn processbtn">' . __('Refund', 'ipol_ozonpay') . '</button>
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>

<div class="ppwin errorwin">
	<div class="close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 10 10"><path d="M5,6.11L8.889,10L10,8.89L6.111,5L10,1.111L8.89,0.001L5,3.887L1.111,0L0,1.11L3.889,5L0,8.889L1.11,10L5,6.111L5,6.11Z" /></svg></div>
	<p class="h red"><b>'.__('Error','ipol_ozonpay').'.</b></p>
	<p class="t">'.__('An error occurred during the operation','ipol_ozonpay').'.</p>
	<div class="tbl">
		<button class="ipol_ozonpay_btn closebtn">'.__('Close','ipol_ozonpay').'</button>
	</div>
</div>
		';

		return $page;
	}

	/**
	 * Init CRON tasks
	 */
	public function actionWP() {
        if(!wp_next_scheduled('ipol_ozonpay_checkorders_event'))
            wp_schedule_event(time(), 'daily', 'ipol_ozonpay_checkorders_event');
        if(!wp_next_scheduled('ipol_ozonpay_payfinish_event'))
            wp_schedule_event(time(), 'five_min', 'ipol_ozonpay_payfinish_event');
	}

	public function actionTwoFactorOrders() {
		//get All Orders with 2factor
        (new OzonPayment())->checkOrdersConfirmed();
	}

    public function actionCheckPayFinishAction()
    {
        (new OzonPayment())->checkOrdersPayFinish();
    }

}
