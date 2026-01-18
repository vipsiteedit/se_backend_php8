<?php

class plugin_shopWishlist
{
	private $mailtemplate = 'depositend';
	private $lang = "rus";

	function __construct()
	{
		$this->lang = se_getlang();
	}

	public function getCount($user_id)
	{
		$tord = new seTable('shop_tovarorder', 'sto');
		$tord->select('COUNT(sto.id) AS cnt');
		$tord->innerjoin('shop_order so', 'sto.id_order=so.id');
		$tord->where('so.id_author=?', $user_id);
		$tord->andwhere("so.status='W'");
		$tord->andwhere("so.is_delete<>'Y'");
		$tord->fetchOne();
		return intval($tord->cnt);
	}

	public function getList($hors = -1, $user_id = 0)
	{
		$wtab = new seTable('shop_order', 'so');
		$wtab->select('so.id, p.email, so.date_credit');
		$wtab->innerjoin('person p', 'so.id_author=p.id');
		$wtab->where("so.status='W'");
		if (-1 != $hors) {
			$wtab->andwhere("so.date_credit < '?'", date('Y-m-d H:i:s', mktime(date('H') - $hors, date('i'), 0, date('m'), date('d'), date('Y'))));
		}
		if ($user_id) {
			$wtab->andwhere("so.id_author=?", $user_id);
		}
		$wtab->andwhere("(so.is_delete='N' OR so.is_delete IS NULL)");
		return $wtab->getList();
	}



	public function delete($order_id)
	{
		if (empty($order_id)) return;
		$goods = $this->getGoods($order_id);
		foreach ($goods as $good) {
			$this->deleteGood($good);
		}

		$ord = new seTable('shop_order');
		$ord->delete($order_id);
	}

	public function getGoods($order_id, $item_id = 0)
	{
		if (empty($order_id)) return;
		$tord = new seTable('shop_tovarorder', 'sto');
		$tord->select('sto.id, sto.id_order, sto.id_price, sto.count, sto.params, sto.nameitem, so.date_credit, p.email, so.id_author');
		$tord->innerjoin('shop_order so', 'sto.id_order=so.id');
		$tord->innerjoin('person p', 'so.id_author=p.id');
		$tord->where('sto.id_order=?', $order_id);
		$tord->andwhere("(so.is_delete='N' OR so.is_delete IS NULL)");
		if ($item_id) {
			$tord->andwhere('sto.id=?', $item_id);
		}
		$res = $tord->getList();
		echo se_db_error();
		return $res;
	}

	public function deleteGood($good)
	{
		if (!$good['id']) return;
		$spr = new seShopPrice();
		$spr->find($good['id_price']);

		if ($spr->presence_count != '' && $spr->presence_count >= 0) {
			$spr->presence_count = $spr->presence_count + $good['count'];
			$spr->save();
		}
		$smail = new plugin_shopmail($good['id_order'], 0, "html");
		$arr = array('GOOD_NAME' => $good['nameitem'], 'DEP_TIME' => $good['date_credit']);
		$smail->sendmail($this->mailtemplate, $good['email'], $arr, $this->lang);
		$sto = new seTable('shop_tovarorder');
		$sto->select('count(*) as cnt');
		$sto->where('id_order=?', $good['id_order']);
		$sto->fetchOne();
		$cnt = $sto->cnt;
		$sto->delete($good['id']);
		if ($cnt < 2) {
			$ord = new seTable('shop_order');
			$ord->delete($good['id_order']);
		}

		// ����� ����� �������� � ���������� ������ ������������� ������ �� ��������� ������
		$sub = new seTable('shop_user_discountbalace');
		$sub->user_id = $good['id_author'];
		$sub->balance = (0 - $spr->price);
		$sub->date_balance = date('Y-m-d H:i:s');
		$sub->description = 'Wishlist:' . $good['nameitem'];
		$sub->save();
		unset($spr);
	}
}
