<?php
require_once dirname(__FILE__) . "/base/seBaseShopOrder.class.php";

/**
 * ����� ������ �������
 * @filesource seShopOrder.class.php
 * @copyright EDGESTILE
 */
class seShopOrder extends seBaseShopOrder
{

	public function getGoods()
	{
		$goods = new seShopOrderGoods();
		$goods->where('id_order=?', $this->id);
		return $goods;
	}

	public function getSumm($order_id = 0)
	{
		$summ = 0;
		if (empty($status) || $this->status == $status) {
			$goods = $this->getGoods();
			if ($order_id != 0) {
				$goods->where('id_order=?', $order_id);
			}
			$glist = $goods->getlist();

			foreach ($glist as $line) {
				$summ += (($line['price'] - $line['discount']) * $line['count']);
			}

			$summ += $this->delivery_payee - $this->discount;
		}
		return $summ;
	}

	public function getUserSumm($user_id, $status = 'Y')
	{
		$order = $this;
		$order->select('SUM((`st`.`price` - `st`.`discount`)* `st`.`count`) as summa')
			->where('id_author=?', $user_id)->andWhere("status='?'", $status)
			->innerjoin('shop_tovarorder st', 'so.id=st.id_order')
			->fetchOne();
		return $order->summa;
	}

	public function getBonus($order_id = 0)
	{
		if ($order_id == 0) $order_id = $this->order_id;
		$summ = 0;

		$goods = $this->getGoods();
		$goods->select('sum(`sp`.`bonus` * `st`.`count`) as `sumbonus`')
			->innerjoin('shop_price sp', 'st.id_price = sp.id')
			->fetchOne();

		return $goods->sumbonus;
	}

	public function getContract($order_id = 0)
	{
		$contract = new seShopContract();
		if ($order_id == 0) $order_id = $this->id;
		return $contract->where('id_order=?', $this->id);
	}

	public function getAccount($order_id = 0)
	{
		$account = new seShopAccount();
		if ($order_id == 0) $order_id = $this->id;
		return $account->where('id_order=?', $order_id);
	}
}
