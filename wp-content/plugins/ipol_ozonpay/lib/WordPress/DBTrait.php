<?php

namespace Ipol\OzonPay\WordPress;
trait DBTrait
{
	private $db;
	private $tableName;

	private function getOrderByRecordId(int $recordId)
	{
		$sql = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `id` = %d;",$recordId);
		return $this->db->get_row($sql,'ARRAY_A');
	}

	private function getActualOrderData(int $recordId)
	{
		$sql = $this->db->prepare("SELECT `status`,`order_history`,`confirmed`,`finalcheck` FROM `{$this->tableName}` WHERE `id` = %d;",$recordId);
		return $this->db->get_row($sql,'ARRAY_A');
	}

	private function getOrderByWPOrderId(int $wpOrderId)
	{
		$sql = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `wp_order_id` = %d;",$wpOrderId);
		return $this->db->get_row($sql,'ARRAY_A');
	}

	private function getOrderByBankOrderId(string $bankOrderId)
	{
		$sql = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `bank_order_id` = %s;",$bankOrderId);
		return $this->db->get_row($sql,'ARRAY_A');
	}

	private function getOrders(int $pageNum = 1, int $limit = 0)
	{
		$query = "SELECT * FROM `{$this->tableName}` ";
		$sqlParams = [1];

		$pageNum--;
		if ( $limit > 0 ) {
			$query.="ORDER BY `id` DESC LIMIT %d,%d;";
			$sqlParams = [( $pageNum * $limit ),$limit];
		} else {
			$query.="WHERE %d ORDER BY `id` DESC;";
		}

		$sql = $this->db->prepare($query,$sqlParams);
		return $this->db->get_results($sql,'ARRAY_A');
	}

	private function getCountOrders(): int
	{
		return intval($this->db->get_row("SELECT COUNT(*) as cnt from `{$this->tableName}`;",'ARRAY_A')['cnt']);
	}

	private function updateOrder(array $orderData, int $recordId)
	{
		$orderData=array_merge($orderData,['updated'=> (new \DateTime())->format('Y-m-d H:i:s')]);
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
		return $this->db->update($this->tableName,$orderData,['id'=>$recordId],$coltypes,['%d']);
	}

    public function getOrdersForPaymentFinish()
    {
        $beforeDate = (new \DateTime())->modify('-7 days');

        $sql = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `pay_finish` = 0 AND `last_status_upd` > %s;", $beforeDate->format('Y-m-d H:i:s'));
        return $this->db->get_results($sql,'ARRAY_A');
    }

}
