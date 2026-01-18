<?php

class plugin_shopaction
{
    private $result = array();

    function __construct()
    {
        if (file_exists('system/action/shopAction.php')) {
            require_once 'system/action/shopAction.php';
            $order = new seShopOrder();
            $order->select('id');
            $order->where('nk=0');
            $order->andwhere("status='Y'");
            foreach ($order->getList() as $it) {
                if ($this->result[] = shopAction($it['id'])) {
                    se_db_query('UPDATE shop_order SET nk=1 WHERE id=' . $it['id']);
                }
            }
        }
    }

    public function getResult()
    {
        return $this->result;
    }
}
