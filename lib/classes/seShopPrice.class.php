<?php

require_once dirname(__file__) . "/base/seBaseShopPrice.class.php";

/**
 * класс товаров Интернет-магазина
 * @filesource seShopPrice.class.php
 * @copyright EDGESTILE
 */
class seShopPrice extends seBaseShopPrice
{
	private $user_id;

	public function getDiscount()
	{
		if ($this->discount != 'Y')
			return;

		$user_id = seUserId();

		$discount = new seShopDiscount();
		$discount->where('(id_price = ' . $this->id . ' OR id_group = ?)', $this->id_group);
		if ($user_id) // Пользователь авторизован
			$discount->andwhere('((id_user IS NULL) OR (id_user = ?))', $user_id);
		else
			$discount->andwhere('(id_user IS NULL)');

		return $discount->getList();
	}

	public function getSpecial()
	{
		if ($this->special_price != 'Y') return;
		$special = new seShopSpecial();
		$special->where("(id_price=? OR id_group={$this->id_group})", $this->id);
		$special->andwhere("expires_date >= '?'", date('Y-m-d H:i:s'));
		$special->fetchOne();
		return $special;
	}

	public function getImages()
	{
		$images = new seShopImage();
		$images->where('id_price=?', $this->id);
		return $images;
	}
}
