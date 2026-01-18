<?php

namespace SE\Shop;

// стоимость особенности
class FeatureValue extends Base
{
    protected $tableName = "shop_feature_value_list";   // список функций магазина
    protected $sortBy = "sort";                         // сортировка
    protected $sortOrder = "asc";                   // по возрастанию
    protected $limit = 10000;                           // предел

    // выборка по id
    public function fetchByIdFeature($idFeature)
    {
        if (!$idFeature)
            return [];

        $this->setFilters(array("field" => "idFeature", "value" => $idFeature)); // набор фильтров
        $result = $this->fetch();                                                   // полученное отправить
        foreach ($result as &$item) {
            if ($item['color']) {
                $item['color'] = '#' . $item['color'];
            }
            if (strpos($item['image'], "://") === false) {
                if ($item['image'] && file_exists(DOCUMENT_ROOT . '/images/rus/shopfeature/' . $item['image']))
                    $item['imageUrlPreview'] = "//" . HOSTNAME . "/lib/image.php?size=64&img=images/rus/shopfeature/" . $item['image'];
            } else {
                $item['imageUrlPreview'] = $item['image'];
            }
        }
        return $result;
    }
}
