<?php
require_once dirname(__FILE__)."/base/seBasePerson.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seUser.class.php
 * @copyright EDGESTILE
 */
class sePerson extends seBasePerson {

    public function getTown()
    {
	$city = new seTown();
	return $city->find($this->town_id);
    }	

    public function getCountry()
    {
	$country = new seCountry();
	return $country->find($this->country_id);
    }	

    public function getState()
    {
	$region = new seRegion();
	return $region->find($this->state_id);
    }	

    public function getUrid()
    {
		$urid = new seUserUrid();
		return $urid->where('id_author=?', $this->id)->fetchOne();
    }	

    public function getShopOrder()
    {
		$order = new seShopOrder();
		return $order->where('id_author=?', $this->id)->fetchOne();
    }	


}	
	
?>