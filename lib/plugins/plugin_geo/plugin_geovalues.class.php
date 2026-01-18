<?php

class plugin_geovalues
{
    private static $instance = null;
    private $valuelist = array();
    private $cont = array();
    private $pagelist = array();

    public function __construct()
    {
        //if (empty($_SESSION['user_region'])) {
        $geo = plugin_geosites::getInstance();
        //}
        $sc = new seTable('shop_contacts', 'sc');
        $sc->select('sc.id, sc.name, sc.url, sc.address,phone,sc.additional_phones, sc.image, sc.description');
        $sc->where('sc.is_visible=1');
        if (!empty($_SESSION['user_region']['id_contact'])) {
            $sc->andwhere("sc.id=?", intval($_SESSION['user_region']['id_contact']));
        }
        $this->cont = $sc->fetchOne();


        $sc = new seTable('shop_variables', 'sv');
        $sc->select('sv.name, IF(sgv.value IS NOT NULL, sgv.value, sv.value) value');
        $contact_id = !empty($this->cont['id']) ? intval($this->cont['id']) : 0;
        $sc->leftjoin('shop_geo_variables sgv', 'sgv.id_variable=sv.id AND sgv.id_contact=' . $contact_id);
        $sc->orderBy('name');
        $this->valuelist = $sc->getList();
        /*
        try {
            $sc = new seTable('shop_geo_pages', 'sgp');
            $sc->select('sgp.id_contact, sgp.page, sgp.skin, sgp.altpage');
            $sc->where('sgp.id_contact=' . intval($this->cont['id']));
            foreach ($sc->getList() as $p) {
                $this->pagelist[$p['page']] = $p;
            }
        } catch (Exception $ex) {
            //$ex->getMessage();
        }
            */
    }

    public function parseValues($text)
    {
        foreach ($this->valuelist as $val) {
            $text = str_replace('{' . $val['name'] . '}', $val['value'], $text);
        }
        return $text;
    }

    public function getContact()
    {
        return $this->cont;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAltPageName($name)
    {
        $name = trim($name);
        if (!empty($this->pagelist[$name]['altpage'])) {
            return trim($this->pagelist[$name]['altpage']);
        }
    }
    public function getAltDesign($name)
    {
        $name = trim($name);
        if (!empty($this->pagelist[$name]['skin'])) {
            return trim($this->pagelist[$name]['skin']);
        }
    }
}
