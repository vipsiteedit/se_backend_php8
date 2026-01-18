<?php

namespace SE\Shop;

use SE\DB;
use SE\Exception;
use SE\Shop\Import;
use SE\Shop\ProductExport as ProductExport;

class Product extends Base
{
    /**
     * @var string $tableName имя основной таблмцы
     * @var array $tableNameDepen имена таблиц и поля соотношения (id товара)
     */
    protected $tableName      = "shop_price";
    protected $sortBy         = "name";
    protected $tableNameDepen = array(
        "shop_modifications_img"     => array(
            "field"            => "id_modification",
            "intermediaryTable" => 'shop_modifications',
            "intermediaryField" => "id_price"
        ),
        "shop_price_group"           => "id_price",
        "shop_modifications"         => "id_price",
        "shop_modifications_feature" => "id_price"
    );
    private $newImages;

    // руссификация заголовков столбцов
    protected $rusCols = array(
        "category" => "Путь категории", "codeGroup" => "Код категории",
        "article" => "Артикул", "code" => "Код (URL)", "name" => "Наименование",
        "price" => "Цена пр.", "priceOptCorp" => "Цена корп.", "priceOpt" => "Цена опт.", "pricePurchase" => "Цена закуп.", "bonus" => "Цена бал.",
        "nameBrand" => "Бренд",
        "images" => 'Изображения', "count" => "Остаток",
        "weight" => "Вес", "volume" => "Объем", "measurement" => "Ед.Изм.", "measuresWeight" => "Меры веса", "measuresVolume" => "Меры объема",
        "note" => "Краткое описание", "text" => "Полное описание", "stepCount" => "Шаг количества",
        "codeCurrency" => "КодВалюты",
        "metaHeader" => "MetaHeader", "metaKeywords" => "MetaKeywords", "pageTitle" => "Оглавление страницы", "metaDescription" => "MetaDescription",
        "flagNew" => "Новинки", "flagHit" => "Хиты", "enabled" => "Видимость", "isMarket" => "Маркет",
        "minCount" => "Мин.кол-во",
        "idAcc" => "Сопутствующие товары", 'delivTime' => 'Срок поставки'
    );

    // поля для поиска
    protected $searchFields = [
        ["title" => "Код", "field" => "code"],
        ["title" => "Наименование", "field" => "name", "active" => true],
        ["title" => "Артикул", "field" => "article", "active" => true],
        ["title" => "Группа", "field" => "nameGroup"],
        ["title" => "Бренд", "field" => "nameBrand"]
    ];

    protected function getSettingsInfo($isInfo = false)
    {
        // получаем данные из таблиц БД
        $select = 'sp.id, sp.id_group, sp.id_brand, sp.code, sp.article, sp.name,
                sp.price, sp.price_opt, sp.price_opt_corp, sp.delivery_time, sp.signal_dt,
                sp.img_alt, sp.curr, sp.presence, sp.bonus, sp.min_count,
                sp.presence_count presence_count, sp.special_offer, sp.flag_hit, sp.enabled, sp.flag_new, sp.is_market, sp.note, sp.text,
                sp.price_purchase price_purchase, sp.measure, sp.step_count, sp.max_discount, sp.discount,
                sp.title, sp.keywords, sp.description, sp.page_title, sp.weight, sp.volume, spg.is_main,
                sg.name name_group, sg.id_modification_group_def id_modification_group_def,	
                COUNT(DISTINCT(smf.id_modification)) count_modifications,
                (SELECT picture FROM shop_img WHERE id_price = sp.id LIMIT 1) img,
                sb.name name_brand, slp.id_label id_label, sp.is_show_feature, sp.market_available,
                spm.id_weight_view, spm.id_weight_edit, spm.id_volume_view, spm.id_volume_edit, sp.market_category';

        $joins[] = array(
            "type" => "left",
            "table" => 'shop_price_group spg',
            "condition" => $isInfo ? '(spg.id_price = sp.id AND spg.is_main)' : '(spg.id_price = sp.id)'
        );

        $joins[] = array(
            "type" => "left",
            "table" => 'shop_group sg',
            "condition" => 'sg.id = sp.id_group'
        );
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_price_measure spm',
            "condition" => 'sp.id = spm.id_price'
        );
        $groupBy = "sp.id, spg.is_main, spg.id_group,slp.id_label,spm.id_weight_view,spm.id_weight_edit,spm.id_volume_view,spm.id_volume_edit";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_brand sb',
            "condition" => 'sb.id = sp.id_brand'
        );
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_group_price sgp',
            "condition" => 'sp.id = sgp.price_id'
        );
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_label_product slp',
            "condition" => 'sp.id = slp.id_product'
        );

        $joins[] = array(
            "type" => "left",
            "table" => '(SELECT smf.id_price, smf.id_modification FROM shop_modifications_feature smf
                           WHERE NOT smf.id_value IS NULL AND NOT smf.id_modification IS NULL GROUP BY smf.id_price, smf.id_modification) smf',
            "condition" => 'sp.id = smf.id_price'
        );

        $convertingValues[] = array(
            "price",
            "priceOpt",
            "priceOptCorp",
            "pricePurchase"
        );

        $result["select"] = $select;
        $result["joins"] = $joins;
        $result["groupBy"] = $groupBy;
        $result["orderBy"] = 'sp.name ASC';
        $result["convertingValues"] = $convertingValues[0];
        return $result;
    }


    // Получить настройки
    protected function getSettingsFetch($isInfo = false)
    {
        // получаем данные из таблиц БД
        $select = 'sp.id, sp.code, sp.article, sp.name, sp.price, sp.curr, sp.presence_count presence_count,
        (SELECT picture FROM shop_img WHERE id_price = sp.id LIMIT 1) img, sg.name name_group,
        sb.name name_brand, sp.special_offer, sp.flag_hit, sp.enabled, sp.flag_new, sp.is_market';



        $joins[] = array(
            "type" => "left",
            "table" => 'shop_group sg',
            "condition" => 'sg.id = sp.id_group'
        );

        $groupBy = "sp.id";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_brand sb',
            "condition" => 'sb.id = sp.id_brand'
        );

        $convertingValues[] = array(
            "price",
        );

        $result["select"] = $select;
        $result["joins"] = $joins;
        $result["groupBy"] = $groupBy;
        $result["orderBy"] = 'sp.name ASC';
        $result["convertingValues"] = $convertingValues[0];
        return $result;
    }

    private function reLinkGroups()
    {
        $u = new DB('shop_price', 'sp');
        $u->select('sp.id, sp.id_group');
        $u->innerJoin('shop_group sg', 'sg.id=sp.id_group');
        $u->leftJoin('shop_price_group spg', '(sp.id=spg.id_price AND sp.id_group=spg.id_group)');
        $u->where('sp.id_group>0 AND spg.id IS NULL');
        $groups = $u->getList();
        if (!empty($groups))
            foreach ($groups as $group) {
                $u = new DB('shop_price_group');
                $data = array('idPrice' => $group['id'], 'idGroup' => $group['idGroup'], 'isMain' => 1);
                $u->setValuesFields($data);
                $u->save();
            }
    }

    // Получить
    public function fetch($isId = false)
    {
        $u = new DB('shop_price');
        $u->addField('delivery_time', 'varchar(80)');
        $u->addField('signal_dt', 'varchar(10)');

        parent::fetch($isId);
        if (!$isId) {
            $list = $this->result['items'];
            $this->result['items'] = array();
            foreach ($list as $item) {
                if (strpos($item['img'], "://") === false) {
                    if ($item['img'] && file_exists(DOCUMENT_ROOT . '/images/rus/shopprice/' . $item['img']))
                        $item['imageUrlPreview'] = "//" . HOSTNAME . "/lib/image.php?size=64&img=images/rus/shopprice/" . $item['img'];
                } else {
                    $item['imageUrlPreview'] = $item['img'];
                }
                $this->result['items'][] = $item;
            }
        }
        return $this->result["items"];
    }

    // Добавить изменения
    public function addModifications($ids)
    {
        $array = $result = array();
        $searchBase = array(
            'values' => array(),
            'group' => array(),
            'items' => array()
        );
        foreach ($ids as $id) {

            $array[$id] = $this->getModifications($id);
            // Если у товара нет модификаций то отправляем пустое значение
            if (empty($array[$id])) return $this->result['modifications'] = array();

            // Собираем информацию о схожих группах
            foreach ($array[$id] as $group) {
                $searchBase['group'][$id][] = $group['id'];
                foreach ($group['items'] as $item)
                    $searchBase['items'][$id][$group['id']][] = $this->diffArray($item['values'], true);
            }
        }
        // Проверка групп
        $tmp = array_shift($searchBase['group']);
        foreach ($searchBase['group'] as $gr) {
            $tmp = array_intersect($tmp, $gr);
        }
        $searchBase['group'] = $tmp;

        $i = 0;
        // Проверка элементов групп
        foreach ($searchBase['group'] as $gid) {
            foreach ($searchBase['items'] as $arrayItem) {
                if (!is_array($searchBase['values'][$gid])) {
                    $searchBase['values'][$gid] = array();
                    $i = $gid;
                }
                $searchBase['values'][$gid][] = $arrayItem[$gid];
            }
        }

        foreach ($searchBase['group'] as $gid) {
            $tmp = false;
            $first = true;
            foreach ($searchBase['values'][$gid] as $val) {
                if (!is_array($tmp)) {
                    if ($first == false) {
                        $tmp = array();
                        break 2;
                    }
                    $tmp = $val;
                    $first = false;
                } else {
                    $tmp = array_intersect($tmp, $val);
                }
            }
            $searchBase['values'][$gid] = $tmp;
        }

        if (!empty($searchBase['values'])) {
            $result = array_shift($array);
            foreach ($result as $indexG => $group) {
                if (in_array($group['id'], $searchBase['group'])) {
                    foreach ($group['items'] as $indexI => $item) {
                        $needle = $this->diffArray($item['values'], true);
                        if (!in_array($needle, $searchBase['values'][$group['id']])) unset($result[$indexG]['items'][$indexI]);
                    }
                } else unset($result[$indexG]);
            }
        }

        return $this->result['modifications'] = array_values($result);
    }


    // @@    @@ @@  @@ @@@@@@@@@ @@@@@@
    // @@   @@@ @@  @@ @@  @  @@ @@  @@
    // @@  @@@@ @@@@@@ @@  @  @@ @@  @@
    // @@@@  @@ @@  @@ @@@ @ @@@ @@  @@
    // @@@   @@ @@  @@     @     @@@@@@

    // Инфо
    public function info($id = NULL)
    {
        // исправить все
        $this->correctAll();
        if (isset($this->input['action']) and $this->input['action'] == 'addModifications') {
            return $this->addModifications($this->input['ids']);
        }
        if (isset($this->input['set']) and is_array($this->input['id']) and count($this->input['id']) > 1) {
            $id_array = $this->input['id'];
            foreach ($id_array as $id) {
                if (!is_numeric($id)) {
                    return false;
                }
            }

            return $this->result = $this->getDiffFeatures($id_array, true);
        }

        $input = (is_array($this->input['id'])) ? array_shift($this->input['id']) : $this->input['id'];
        parent::info($input);
        $meas = new Measure();
        $measure = $meas->info();
        if ($this->result['marketCategory']) {
            $catfile = DOCUMENT_ROOT . '/lib/plugins/plugin_shop/market/market_categories.json';
            if (file_exists($catfile)) {
                $list = json_decode(file_get_contents($catfile), true);
                $this->result['nameMarketGroup'] = $list[$this->result['marketCategory']];
            }
        }
        $this->result['weightEdit'] = $this->result['weight'];
        $this->result['volumeEdit'] = $this->result['volume'];
        foreach ($measure["weights"] as $w) {
            if (($this->result['idWeightEdit'] == $w['id']) || empty($this->result['idWeightEdit'])) {
                if (empty($this->result['idWeightEdit']))
                    $this->result['idWeightEdit'] = $w["id"];
                $this->result['weightEdit'] = $this->result['weight'] * $w['value'];
                break;
            }
        }
        foreach ($measure["volumes"] as $v) {
            if (($this->result['idVolumeEdit'] == $v['id']) || empty($this->result['idVolumeEdit'])) {
                if (empty($this->result['idVolumeEdit']))
                    $this->result['idVolumeEdit'] = $v["id"];
                $this->result['volumeEdit'] = $this->result['volume'] * $v['value'];
                break;
            }
        }
    }

    private function calkMeasure($table, $id)
    {
        $u = new DB('shop_measure');
    }

    // Получить функции Diff
    private function getDiffFeatures($id_array, $retard = FALSE)
    {
        if (count($id_array) < 2) {
            return array();
        }
        $id = array_shift($id_array);
        $ids = implode(',', $id_array);
        $sql = 'SELECT `id` FROM `shop_modifications_feature` WHERE `id_price` = %d AND `id_value` IN (SELECT `id_value` FROM `shop_modifications_feature` WHERE `id_price` IN (%s))';
        $sql = sprintf($sql, $id, $ids);
        $result = DB::query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        foreach ($this->getSpecifications($id) as $items) {
            foreach ($result as $item) {
                if ($retard) {
                    if ($item['id'] == $items['id']) {
                        $return[] = $items;
                    }
                } else {
                    if ($item['id'] == $items['id']) {
                        $return[] = array(
                            'id_feature' => $items['idFeature'],
                            'id_value' => $items['idValue']
                        );
                    }
                }
            }
        }
        return $return;
    }

    // Получить настройки
    // protected function getSettingsInfo()
    // {
    //     return $this->getSettingsFetch(true);
    // }

    // Получить изображения
    public function getImages($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_img', 'si');
        $u->where('si.id_price = ?', $id);
        $u->orderBy("sort");
        $objects = $u->getList();

        foreach ($objects as $item) {
            $image = null;
            $image['id'] = $item['id'];
            $image['imageFile'] = $item['picture'];
            $image['imageAlt'] = $item['pictureAlt'];
            $image['imageTitle'] = $item['title'];
            $image['sortIndex'] = $item['sort'];
            $image['isMain'] = (bool)$item['default'];
            if ($image['imageFile']) {
                if (strpos($image['imageFile'], "://") === false) {
                    $image['imageUrl'] = '//' . HOSTNAME . "/images/rus/shopprice/" . $image['imageFile'];
                    $image['imageUrlPreview'] = "//" . HOSTNAME . "/lib/image.php?size=64&img=images/rus/shopprice/" . $image['imageFile'];
                } else {
                    $image['imageUrl'] = $image['imageFile'];
                    $image['imageUrlPreview'] = $image['imageFile'];
                }
            }
            if (empty($product["imageFile"]) && $image['isMain']) {
                $product["imageFile"] = $image['imageFile'];
                $product["imageAlt"] = $image['imageAlt'];
            }
            $result[] = $image;
        }
        return $result;
    }

    // Получить файлы
    public function getFiles($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_files', 'si');
        $u->addField('sort', 'int(11)', '0', 1);
        $u->where('si.id_price = ?', $id);
        $u->orderBy("sort");
        $objects = $u->getList();

        foreach ($objects as $item) {
            $file = null;
            $file['id'] = $item['id'];
            $file['fileURL'] = $item['file'];
            $file['fileText'] = $item['name'];
            $file['fileName'] = $item['file'];
            $file['fileExt'] = strtoupper(substr(strrchr($item['file'], '.'), 1));
            $file['sortIndex'] = $item['sort'];
            if ($file['fileUrl']) {
                if (strpos($file['fileUrl'], "://") === false) {
                    $file['fileUrl'] = '//' . HOSTNAME . "/files/" . $item['file'];
                }
            }
            $result[] = $file;
        }
        return $result;
    }

    // Добавить цену
    public function addPrice()
    {
        $this->correctAll();
        try {
            $idsProducts = $this->input["ids"];
            $idsStr = implode(",", $idsProducts);
            $source = $this->input["source"];
            $type = $this->input["value"];
            $price = $this->input["price"];
            $sql = "UPDATE shop_price SET price = ";
            $priceField = $source ? "price_purchase" : "price";
            if ($type == "a")
                $sql .= "{$priceField}+" . $price;
            if ($type == "p")
                $sql .= "{$priceField}+{$priceField}*" . $price / 100;
            $sql .= " WHERE id IN ({$idsStr})";
            DB::query($sql);

            $sqlMod = "UPDATE shop_modifications sm
                INNER JOIN shop_modifications_group smg ON sm.id_mod_group = smg.id SET `value` = ";
            if ($type == "a")
                $sqlMod .= "`value` + " . $price;
            if ($type == "p")
                $sqlMod .= "`value` + `value` * " . $price / 100;
            $sqlMod .= " WHERE id_price IN ({$idsStr}) AND smg.vtype = 2";
            DB::query($sqlMod);
        } catch (Exception $e) {
            $this->error = "Не удаётся произвести наценку выбранных товаров!";
        }
    }

    // Получить характеристики товара
    public function getSpecifications($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        try {
            $u = new DB('shop_modifications_feature', 'smf');
            $u->addField('is_param', 'tinyint(1)', 0, 1);
            $u->select('sfg.id id_group, sfg.name group_name, sf.name, smf.id_modification,
						sf.type, sf.measure, smf.*, sfvl.value, sfvl.color, sfg.sort index_group');
            $u->innerJoin('shop_feature sf', 'sf.id = smf.id_feature');
            $u->leftJoin('shop_feature_value_list sfvl', 'smf.id_value = sfvl.id');
            $u->leftJoin('shop_feature_group sfg', 'sfg.id = sf.id_feature_group');
            $u->where('smf.id_price = ? AND (smf.id_modification IS NULL OR smf.is_param=1)', $id);
            $u->orderBy('sfg.sort');
            $u->addOrderBy('sf.sort');
            $items = $u->getList();
            $result = [];
            foreach ($items as $item) {
                if ($item["type"] == "number")
                    $item["value"] = (float)$item["valueNumber"];
                elseif ($item["type"] == "string")
                    $item["value"] = $item["valueString"];
                elseif ($item["type"] == "bool")
                    $item["value"] = (bool)$item["valueBool"];
                $result[] = $item;
            }
            return $result;
        } catch (Exception $e) {
            $this->error = "Не удаётся получить характеристики товара!";
        }
    }

    // Получить похожие продукты
    public function getSimilarProducts($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_sameprice', 'ss');
        $u->select('sp1.id id1, sp1.name name1, sp1.code code1, sp1.article article1, sp1.price price1,
                    sp2.id id2, sp2.name name2, sp2.code code2, sp2.article article2, sp2.price price2');
        $u->innerJoin('shop_price sp1', 'sp1.id = ss.id_price');
        $u->innerJoin('shop_price sp2', 'sp2.id = ss.id_acc');
        $u->where('sp1.id = ? OR sp2.id = ?', $id);
        $objects = $u->getList();
        foreach ($objects as $item) {
            $similar = null;
            $i = 1;
            if ($item['id1'] == $id)
                $i = 2;
            $similar['id'] = $item['id' . $i];
            $similar['name'] = $item['name' . $i];
            $similar['code'] = $item['code' . $i];
            $similar['article'] = $item['article' . $i];
            $similar['price'] = (float)$item['price' . $i];
            $result[] = $similar;
        }
        return $result;
    }

    // Получить сопроводительные продукты
    public function getAccompanyingProducts($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_accomp', 'sa');
        $u->select('sp.id, sp.name, sa.id_group, sg.name `group`, sp.code, sp.article, sp.price');
        $u->leftJoin('shop_price sp', 'sp.id = sa.id_acc');
        $u->leftJoin('shop_group sg', 'sg.id = sa.id_group');
        $u->where('sa.id_price = ?', $id);
        $u->orderBy("sa.id");
        $objects = $u->getList();
        foreach ($objects as $item) {
            $accompanying = null;
            $accompanying['id'] = $item['id'];
            $accompanying['idGroup'] = $item['idGroup'];
            $accompanying['name'] = $item['name'];
            $accompanying["type"] = "Товар";
            if ($item["group"]) {
                $accompanying["name"] = $item["group"];
                $accompanying["type"] = "Группа";
            }
            $accompanying['code'] = $item['code'];
            $accompanying['article'] = $item['article'];
            $accompanying['price'] = (float)$item['price'];
            $result[] = $accompanying;
        }
        return $result;
    }

    // Получить комментарии
    public function getComments($idProduct = null)
    {
        $id = $idProduct ? $idProduct : $this->input["id"];
        $comment = new Comment();
        return $comment->fetchByIdProduct($id);
    }

    // Получить обзоры
    public function getReviews($idProduct = null)
    {
        $id = $idProduct ? $idProduct : $this->input["id"];
        $review = new Review();
        return $review->fetchByIdProduct($id);
    }

    // Получить перекрестные группы
    public function getCrossGroups($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_price_group', 'spg');
        $u->select('sg.id, sg.name');
        $u->innerJoin('shop_group sg', 'sg.id = spg.id_group');
        $u->where('spg.id_price = ? AND NOT spg.is_main', $id);

        return $u->getList();
    }

    // Получить изменения (отображение товаров в разделе "товары")
    public function getModifications($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $newTypes = array("string" => "S", "number" => "D", "bool" => "B", "list" => "L", "colorlist" => "CL");
        $product = [];

        $u = new DB('shop_modifications', 'sm');
        $u->select('smg.*,
                GROUP_CONCAT(DISTINCT(CONCAT_WS("\t", sf.id, sf.name, sf.`type`, sf.sort)) SEPARATOR "\n") `columns`');
        $u->innerJoin('shop_modifications_group smg', 'smg.id = sm.id_mod_group');
        $u->innerJoin('shop_modifications_feature smf', 'smf.id_modification = sm.id');
        $u->innerJoin('shop_feature sf', 'sf.id = smf.id_feature');
        $u->where('sm.id_price = ?', $id);
        $u->andWhere('smf.is_param=0');
        $u->groupBy('smg.id');
        $u->orderBy('smg.sort');
        $objects = $u->getList();
        $isDefModification = false;
        if (empty($objects)) {
            $idGroup = $this->result["idModificationGroupDef"];
            if (empty($idGroup))
                return $result;

            $isDefModification = true;
            $u = new DB('shop_modifications_group', 'smg');
            $u->select('smg.*,
                GROUP_CONCAT(DISTINCT(CONCAT_WS("\t", sf.id, sf.name, sf.`type`, sf.sort)) SEPARATOR "\n") `columns`');
            $u->innerJoin('shop_group_feature sgf', 'smg.id = sgf.id_group');
            $u->innerJoin('shop_feature sf', 'sf.id = sgf.id_feature');
            $u->where('smg.id = ?', $idGroup);
            $u->groupBy('smg.id');
            $u->orderBy('smg.sort');
            $objects = $u->getList();
        }
        foreach ($objects as $item) {
            $group = null;
            $group['id'] = $item['id'];
            $group['name'] = $item['name'];
            $group['sortIndex'] = $item['sort'];
            $group['type'] = $item['vtype'];
            if (!$product["idGroupModification"]) {
                $product["idGroupModification"] = $group['id'];
                $product["nameGroupModification"] = $group['name'];
            }
            $items = explode("\n", $item['columns']);
            foreach ($items as $item) {
                $item = explode("\t", $item);
                $column['id'] = $item[0];
                $column['name'] = $item[1];
                $column['type'] = $item[2];
                $column['sortIndex'] = $item[3];
                $column['valueType'] = $newTypes[$column['type']];
                $group['columns'][] = $column;
            }
            //$group['items'] = [];
            $groups[] = $group;
        }
        if (!isset($groups))
            return $result;
        if ($isDefModification)
            return $groups;

        $u = new DB('shop_modifications', 'sm');
        $u->select('sm.*,
                SUBSTRING(GROUP_CONCAT(DISTINCT(CONCAT_WS("\t", sfvl.id_feature, sfvl.id, sfvl.value, sfvl.sort, sfvl.color)) SEPARATOR "\n"), 1) values_feature,
                SUBSTRING(GROUP_CONCAT(DISTINCT(CONCAT_WS("\t", smi.id_img, smi.sort, si.picture)) SEPARATOR "\n"), 1) images');
        $u->innerJoin('shop_modifications_feature smf', 'sm.id = smf.id_modification');
        $u->innerJoin('shop_feature_value_list sfvl', 'sfvl.id = smf.id_value');
        $u->leftJoin('shop_modifications_img smi', 'sm.id = smi.id_modification');
        $u->leftJoin('shop_img si', 'smi.id_img = si.id');
        $u->where('sm.id_price = ?', $id);
        $u->andWhere('smf.is_param=0');
        $u->groupBy();
        $objects = $u->getList();
        $existFeatures = [];
        foreach ($objects as $item) {
            if ($item['id']) {
                $modification = null;
                $modification['id'] = $item['id'];
                $modification['default'] = $item['default'];
                $modification['article'] = $item['code'];
                if ($item['count'] !== null)
                    $modification['count'] = (float)$item['count'];
                else $modification['count'] = -1;
                if (!$modification['article'])
                    $modification['article'] = $product["article"];
                if (!$modification['measurement'])
                    $modification['measurement'] = $product['measurement'];
                if (!$modification['measuresWeight'])
                    $modification['measuresWeight'] = $product['measuresWeight'];
                if (!$modification['measuresVolume'])
                    $modification['measuresVolume'] = $product['measuresVolume'];
                $modification['priceRetail'] = (float)$item['value'];
                $modification['priceSmallOpt'] = (float)$item['valueOpt'];
                $modification['priceOpt'] = (float)$item['valueOptCorp'];
                $modification['description'] = $item['description'];
                $features = explode("\n", $item['valuesFeature']);
                $sorts = [];
                foreach ($features as $feature) {
                    $feature = explode("\t", $feature);
                    $value = null;
                    $value['idFeature'] = $feature[0];
                    $value['id'] = $feature[1];
                    $value['value'] = $feature[2];
                    $sorts[] = $feature[3];
                    $value['color'] = $feature[4];
                    $modification['values'][] = $value;
                }
                $modification['sortValue'] = $sorts;
                if ($item['images']) {
                    $images = explode("\n", $item['images']);
                    foreach ($images as $image) {
                        $feature = explode("\t", $image);
                        $value = null;
                        $value['id'] = $feature[0];
                        $value['sortIndex'] = $feature[1];
                        $value['imageFile'] = $feature[2];
                        if ($value['imageFile']) {
                            if (strpos($value['imageFile'], "://") === false) {
                                $value['imageUrl'] = '//' . HOSTNAME . "/images/rus/shopprice/" . $value['imageFile'];
                                $value['imageUrlPreview'] = "//" . HOSTNAME . "/lib/image.php?size=64&img=images/rus/shopprice/" . $value['imageFile'];
                            } else {
                                $value['imageUrl'] = $image['imageFile'];
                                $value['imageUrlPreview'] = $image['imageFile'];
                            }
                        }
                        $modification['images'][] = $value;
                    }
                }
                foreach ($groups as &$group) {
                    if ($group['id'] == $item['idModGroup']) {
                        $group['items'][] = $modification;
                    }
                }
                $existFeatures[] = $item['valuesFeature'];
            }
        }
        return $groups;
    }


    // @@@@@@ @@@@@@    @@    @@  @@ @@  @@     @@@@@@ @@  @@ @@    @@ @@@@@@  @@  @@ @@    @@
    // @@  @@ @@  @@   @@@@   @@  @@ @@  @@     @@     @@ @@  @@   @@@ @@   @@ @@ @@  @@   @@@
    // @@  @@ @@  @@  @@  @@   @@@@  @@@@@@     @@     @@@@   @@  @@@@ @@   @@ @@@@   @@  @@@@
    // @@  @@ @@  @@ @@    @@   @@       @@     @@     @@ @@  @@@@  @@ @@   @@ @@ @@  @@@@  @@
    // @@  @@ @@@@@@ @@    @@   @@       @@     @@@@@@ @@  @@ @@@   @@ @@@@@@  @@  @@ @@@   @@

    // Получить скидки
    public function getDiscounts($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        $u = new DB('shop_discounts', 'sd');
        $u->select('sd.*');
        $u->innerJoin('shop_discount_links sdl', 'sdl.discount_id = sd.id');
        $u->where('sdl.id_price = ?', $id);
        $u->orderBy('sd.id');
        return $u->getList();
    }


    // Получить дополнительную информацию
    protected function getAddInfo()
    {
        $result["images"] = $this->getImages();
        $result["files"] = $this->getFiles();

        $result["specifications"] = $this->getSpecifications();
        $result["similarProducts"] = $this->getSimilarProducts();
        $result["accompanyingProducts"] = $this->getAccompanyingProducts();
        $result["comments"] = $this->getComments();
        $result["reviews"] = $this->getReviews();
        $result["discounts"] = $this->getDiscounts();
        $result["crossGroups"] = $this->getCrossGroups();
        $result["modifications"] = $this->getModifications();
        $result["customFields"] = $this->getCustomFields();
        $result["countModifications"] = count($result["modifications"]);
        $result["options"] = $this->getOptions();
        $result["labels"] = $this->getLabels();
        if (empty($result["customFields"]))
            $result["customFields"] = false;

        return $result;
    }

    // Получить url
    private function getUrl($code, $id, $existCodes = [])
    {
        $code_n = $code;
        $id = (int)$id;
        $u = new DB('shop_price', 'sp');
        $i = 1;
        while ($i < 1000) {
            $data = $u->findList("sp.code = '$code_n' AND id <> {$id}")->fetchOne();
            if ($data["id"] || in_array($code_n, $existCodes))
                $code_n = $code . "-$i";
            else return $code_n;
            $i++;
        }
        return uniqid();
    }


    // @@@@@@ @@@@@@ @@    @@ @@@@@@
    // @@     @@     @@    @@ @@
    // @@@@@@ @@@@@@  @@  @@  @@@@@@
    //     @@ @@       @@@@   @@
    // @@@@@@ @@@@@@    @@    @@@@@@

    // Сохранить
    public function save($isTransactionMode = true)
    {

        # All Mode
        // исправить все
        if (empty($this->input['note']) && count($this->input['ids']) > 1) {
            unset($this->input['note']);
        }
        if (empty($this->input['text']) && count($this->input['ids']) > 1) {
            unset($this->input['text']);
        }

        $this->correctAll();
        $this->correctMeasure();

        // формирование артикля // при создании товара (если отличен от нуля и пуст)
        if (
            empty($this->input['article']) &&
            count($this->input['ids']) < 1 &&
            empty($this->input['id'])
        ) { // isset($this->input['article']) &&
            if (empty($this->input['ids'])) {
                $u = new DB('shop_price');
                $u->select('MAX(id) AS mid');
                $res = $u->fetchOne();
                $res['mid'] += 1;
            } else {
                $res['mid'] = $this->input['ids'][0];
            }
            $this->input['article'] = sprintf("%03s", $this->input["idGroup"]) . '' . sprintf("%03s", $res["mid"]);
        }

        if (isset($this->input['brand'], $this->input['ids'])) {
            $brand = (int)$this->input['brand']['id'];
            $idsStr = implode(",", $this->input['ids']);

            DB::exec("UPDATE `shop_price` SET `id_brand` = '" . $brand . "' WHERE `shop_price`.`id` IN (" . $idsStr . ");");

            return true;
        }

        //DB::exec("ALTER TABLE `shop_price` CHANGE `code` `code` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        if (isset($this->input["code"])/* && empty($this->input["code"])*/)
            $this->input["code"] = strtolower(se_translite_url($this->input["code"]));

        file_get_contents('//' . HOSTNAME . "/lib/shoppreorder_checkCount.php?id={$this->input["id"]}");

        parent::save();
    }

    // Преобразование веса при редактировании в зависимости от параметра редактирования веса
    private function correctMeasure()
    {
        if ($this->input["weightEdit"]) {
            $u = new DB('shop_measure_weight');
            $u->select('value');
            $u->where('id=?', intval($this->input["idWeightEdit"]));
            $res = $u->fetchOne();
            if ($this->input["weightEdit"] > 0 && $res['value']) {
                $this->input["weight"] = $this->input["weightEdit"] / $res['value'];
            } else {
                $this->input["weight"] = $this->input["weightEdit"];
            }
        }
        if ($this->input["volumeEdit"]) {
            $u = new DB('shop_measure_volume');
            $u->select('value');
            $u->where('id=?', intval($this->input["idVolumeEdit"]));
            $res = $u->fetchOne();
            if ($this->input["volumeEdit"] > 0 && $res['value']) {
                $this->input["volume"] = $this->input["volumeEdit"] / $res['value'];
            } else {
                $this->input["volume"] = $this->input["volumeEdit"];
            }
        }
    }

    // сохранить все меры (объемы и веса)
    public function saveMeasure()
    {

        try {
            $u = new DB('shop_price_measure');
            $u->select('id');
            $u->where('id_price=?', $this->input["id"]); // добавить ID если нет
            $res = $u->fetchOne();                              // получить одну


            $data = array();
            if ($res['id'])
                $data["id"] = $res['id'];
            $data["idPrice"] = $this->input["id"];
            $data["idWeightView"] = $this->input["idWeightView"];
            $data["idWeightEdit"] = $this->input["idWeightEdit"];
            $data["idVolumeView"] = $this->input["idVolumeView"];
            $data["idVolumeEdit"] = $this->input["idVolumeEdit"];

            $u = new DB('shop_price_measure');
            $u->setValuesFields($data);
            $u->save();
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить меры!";
            throw new Exception($this->error);
        }
    }

    // Правильные значения перед сохранением
    protected function correctValuesBeforeSave()
    {
        if (!$this->input["id"] && !$this->input["ids"] || isset($this->input["code"])) {
            if (empty($this->input["code"]))
                $this->input["code"] = strtolower(se_translite_url($this->input["name"]));
            $this->input["code"] = $this->getUrl($this->input["code"], $this->input["id"]);
        }
        if (isset($this->input["presence"]) && empty($this->input["presence"]))
            $this->input["presence"] = null;
    }

    // Сохранить изображения
    private function saveImages()
    {
        if (!isset($this->input["images"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $images = $this->input["images"];
            if ($this->isNew) {
                foreach ($images as &$image)
                    unset($image["id"]);
                unset($image);
            }
            // обновление изображений
            $idsStore = "";

            foreach ($images as $image) {
                if ($image["id"] > 0) {
                    if (!empty($idsStore))
                        $idsStore .= ",";
                    $idsStore .= $image["id"];
                    $u = new DB('shop_img', 'si');
                    $image["picture"] = $image["imageFile"];
                    $image["sort"] = $image["sortIndex"];
                    $image["pictureAlt"] = $image["imageAlt"];
                    $image["title"] = $image["imageTitle"];
                    $image["default"] = $image["isMain"];
                    $u->setValuesFields($image);
                    $u->save();
                }
            }
            $idsStr = implode(",", $idsProducts);
            if (!empty($idsStore)) {
                $u = new DB('shop_img', 'si');
                $u->where("id_price IN ($idsStr) AND NOT (id IN (?))", $idsStore)->deleteList();
            } else {
                $u = new DB('shop_img', 'si');
                $u->where('id_price IN (?)', $idsStr)->deleteList();
            }

            $data = [];
            foreach ($images as $image)
                if (empty($image["id"]) || ($image["id"] <= 0)) {
                    foreach ($idsProducts as $idProduct) {
                        $data[] = array(
                            'id_price' => $idProduct, 'picture' => $image["imageFile"],
                            'sort' => (int)$image["sortIndex"], 'picture_alt' => $image["imageAlt"], 'title' => $image["imageTitle"],
                            'default' => (int)$image["isMain"]
                        );
                        $newImages[] = $image["imageFile"];
                    }
                }

            if (!empty($data))
                DB::insertList('shop_img', $data);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить изображения товара!";
            throw new Exception($this->error);
        }
    }

    private function saveOptions()
    {
        if (!isset($this->input["options"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $options = $this->input["options"];
            $idsStr = implode(",", $idsProducts);
            $idsExists = [];
            foreach ($options as $option)
                foreach ($option['items'] as $items) {
                    if ($items["id"])
                        $idsExists[] = $items["id"];
                }
            $idsExists = implode(",", $idsExists);


            $u = new DB('shop_product_option');
            if (!$idsExists)
                $u->where('id_product IN (?)', $idsStr)->deleteList();
            else $u->where("NOT id IN ({$idsExists}) AND id_product IN (?)", $idsStr)->deleteList();
            foreach ($options as $option) {
                foreach ($option['items'] as $items) {
                    foreach ($idsProducts as $idProduct) {
                        $items["idProduct"] = $idProduct;
                        $items["price"] = $items["priceValue"];
                        $u = new DB('shop_product_option');
                        $u->setValuesFields($items);
                        //writeLog($items);
                        $u->save();
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить опции товара!";
            throw new Exception($this->error);
        }
    }

    // Сохранить файлы
    private function saveFiles()
    {
        if (!isset($this->input["files"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $files = $this->input["files"];
            if ($this->isNew) {
                foreach ($files as &$file)
                    unset($file["id"]);
                unset($file);
            }
            // обновление изображений
            $idsStore = "";

            foreach ($files as $file) {
                if ($file["id"] > 0) {
                    if (!empty($idsStore))
                        $idsStore .= ",";
                    $idsStore .= $file["id"];
                    $u = new DB('shop_files', 'si');
                    $file["file"] = $file["fileName"];
                    $file["sort"] = $file["sortIndex"];
                    $file["name"] = $file["fileText"];
                    $u->setValuesFields($file);
                    $u->save();
                }
            }
            $idsStr = implode(",", $idsProducts);
            if (!empty($idsStore)) {
                $u = new DB('shop_files', 'si');
                $u->where("id_price IN ($idsStr) AND NOT (id IN (?))", $idsStore)->deleteList();
            } else {
                $u = new DB('shop_files', 'si');
                $u->where('id_price IN (?)', $idsStr)->deleteList();
            }

            $data = array();
            foreach ($files as $file)
                if (empty($file["id"]) || ($file["id"] <= 0)) {
                    foreach ($idsProducts as $idProduct) {
                        $data[] = array(
                            'id_price' => $idProduct,
                            'file' => $file["fileName"],
                            'sort' => (int)$file["sortIndex"],
                            'name' => $file["fileText"]
                        );
                    }
                }

            if (!empty($data))
                DB::insertList('shop_files', $data);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить файлы товара!";
            throw new Exception($this->error);
        }
    }

    // Получить группу спецификаций идентификаторов
    private function getIdSpecificationGroup($name)
    {
        if (empty($name))
            return null;

        $u = new DB('shop_feature_group');
        $u->select('id');
        $u->where('name = "?"', $name);
        $result = $u->fetchOne();
        if (!empty($result["id"]))
            return $result["id"];

        $u = new DB('shop_feature_group');
        $u->setValuesFields(array("name" => $name));
        return $u->save();
    }

    // Получить идентификатор
    private function getIdFeature($idGroup, $name)
    {
        $u = new DB('shop_feature', 'sf');
        $u->select('id');
        $u->where('name = "?"', $name);
        if ($idGroup)
            $u->andWhere('id_feature_group = ?', $idGroup);
        else $u->andWhere('id_feature_group IS NULL');
        $result = $u->fetchOne();
        if (!empty($result["id"]))
            return $result["id"];

        $u = new DB('shop_feature', 'sf');
        $data = [];
        if ($idGroup)
            $data["idFeatureGroup"] = $idGroup;
        $data["name"] = $name;
        return $u->save();
    }

    // Получить спецификацию по имени
    public function getSpecificationByName($specification)
    {
        $idGroup = $this->getIdSpecificationGroup($specification->nameGroup);
        $specification->idFeature = $this->getIdFeature($idGroup, $specification->name);
        return $specification;
    }

    public function getOptions($idProduct = null)
    {
        $result = [];
        $id = $idProduct ? $idProduct : $this->input["id"];
        if (!$id)
            return $result;

        try {
            $u = new DB('shop_option', 'so');
            $u->select('so.*');
            $u->innerJoin('shop_option_value sov', 'sov.id_option = so.id');
            $u->innerJoin('shop_product_option spo', 'sov.id = spo.id_option_value');
            $u->where('spo.id_product = ?', $id);
            $u->orderBy('so.sort');
            $u->groupby('so.id');
            $result = $u->getList();
            foreach ($result as &$item) {
                $u = new DB('shop_product_option', 'spo');
                $u->select('spo.*, sov.name, spo.price as priceValue');
                $u->innerJoin('shop_option_value sov', 'sov.id = spo.id_option_value');
                $u->where('spo.id_product = ?', $id);
                $u->andwhere('sov.id_option = ?', $item['id']);
                $u->orderBy('spo.sort');
                $item['columns'] = array(array('id' => $item['id'], 'name' => $item['name']));
                $item['items'] = $u->getList();
            }
            return $result;
        } catch (Exception $e) {
            $this->error = "Не удаётся получить опции товара!";
        }
    }

    // Получить пользовательские поля
    private function getCustomFields()
    {
        $idPrice = intval($this->input["id"]);
        try {

            $u = new DB('shop_userfields', 'su');
            $u->select("cu.id, cu.id_price, cu.value, su.id id_userfield,
                      su.name, su.required, su.enabled, su.type, su.placeholder, su.description, su.values, sug.id id_group, sug.name name_group");
            $u->leftJoin('shop_price_userfields cu', "cu.id_userfield = su.id AND cu.id_price = {$idPrice}");
            $u->leftJoin('shop_userfield_groups sug', 'su.id_group = sug.id');
            $u->where('su.data = "product"');
            $u->groupBy('su.id');
            $u->orderBy('sug.sort');
            $u->addOrderBy('su.sort');
            writeLog($u->getSql());
            $result = $u->getList();


            $groups = array();
            foreach ($result as $item) {
                $groups[intval($item["idGroup"])]["id"] = $item["idGroup"];
                $groups[intval($item["idGroup"])]["name"] = empty($item["nameGroup"]) ? "Без категории" : $item["nameGroup"];
                if ($item['type'] == "date")
                    $item['value'] = date('Y-m-d', strtotime($item['value']));
                $groups[intval($item["idGroup"])]["items"][] = $item;
            }
            $grlist = array();
            foreach ($groups as $id => $gr) {
                $grlist[] = $gr;
            }
            return $grlist;
        } catch (Exception $e) {
            return false;
        }
    }

    // Сохранить Технические характеристики
    private function saveSpecifications()
    {
        if (!isset($this->input["specifications"]))
            return true;

        try {

            $idsProducts = $this->input["ids"];
            $isAddSpecifications = $this->input["isAddSpecifications"];
            $specifications = $this->input["specifications"];
            $idsStr = implode(",", $idsProducts);

            if (!$isAddSpecifications) {
                if (count($idsProducts) > 1) {
                    $delIdsArray = $this->getDiffFeatures($idsProducts);

                    $u = new DB('shop_modifications_feature', 'smf');
                    foreach ($delIdsArray as $die) {
                        $u->where("(id_modification IS NULL OR is_param=1) AND id_price IN (?) AND id_feature = {$die['id_feature']} AND id_value = {$die['id_value']}", $idsStr)->deleteList();
                    }
                } else {
                    $u = new DB('shop_modifications_feature', 'smf');
                    $u->where("(id_modification IS NULL OR is_param=1) AND id_price IN (?)", $idsStr)->deleteList();
                }
            }

            $m = new DB('shop_modifications_feature', 'smf');
            //$m->addFields('is_mod', 'tinyint(6) default 0', 1);
            $m->select('id');
            foreach ($specifications as $specification) {
                foreach ($idsProducts as $idProduct) {
                    if ($isAddSpecifications) {
                        if (is_string($specification["valueString"]) && $specification["type"] == "string")
                            $m->where("id_price = {$idProduct} AND id_feature = {$specification["idFeature"]} AND
							           value_string = '{$specification["value"]}'");

                        if (is_bool($specification["valueBool"]) && $specification["type"] == "bool")
                            $m->where("id_price = {$idProduct} AND id_feature = {$specification["idFeature"]} AND
							           value_bool = '{$specification["value"]}'");

                        if (is_numeric($specification["valueNumber"]) && $specification["type"] == "number")
                            $m->where("id_price = {$idProduct} AND id_feature = {$specification["idFeature"]} AND
							           value_number = '{$specification["valueNumber"]}'");

                        if (is_numeric($specification["idValue"]))
                            $m->where("id_price = {$idProduct} AND id_feature = {$specification["idFeature"]} AND
									   id_value = {$specification["idValue"]}");

                        $result = $m->fetchOne();
                        if ($result["id"])
                            continue;
                    }
                    /*
                    if ($specification["type"] == "number")
                        $specification["valueNumber"] = $specification["value"];
                    elseif ($specification["type"] == "string")
                        $specification["valueString"] = $specification["value"];
                    elseif ($specification["type"] == "bool")
                        $specification["valueBool"] = $specification["value"];
                    else
                         */
                    if (($specification["type"] == "colorlist" || $specification["type"] == "list") && empty($specification["idValue"]))
                        continue;
                    $data[] = array(
                        'id_price' => $idProduct, 'id_feature' => $specification["idFeature"],
                        'id_value' => $specification["idValue"],
                        'value_number' => $specification["valueNumber"],
                        'value_bool' => $specification["valueBool"],
                        'value_string' => $specification["valueString"],
                        'is_param' => 1,
                        'id_modification' => $specification["idModification"]
                    );
                }
            }
            if (!empty($data))
                DB::insertList('shop_modifications_feature', $data, true);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить спецификации товара!";
            throw new Exception($this->error);
        }
    }

    // Сохранить похожие продукты
    private function saveSimilarProducts()
    {
        if (!isset($this->input["similarProducts"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $products = $this->input["similarProducts"];
            $idsExists = [];
            foreach ($products as $p)
                if ($p["id"])
                    $idsExists[] = $p["id"];
            $idsExists = array_diff($idsExists, $idsProducts);
            $idsExistsStr = implode(",", $idsExists);
            $idsStr = implode(",", $idsProducts);
            $u = new DB('shop_sameprice', 'ss');
            if ($idsExistsStr)
                $u->where("((NOT id_acc IN ({$idsExistsStr})) AND id_price IN (?)) OR
                           ((NOT id_price IN ({$idsExistsStr})) AND id_acc IN (?))", $idsStr)->deleteList();
            else $u->where('id_price IN (?) OR id_acc IN (?)', $idsStr)->deleteList();
            $idsExists = [];
            if ($idsExistsStr) {
                $u->select("id_price, id_acc");
                $u->where("((id_acc IN ({$idsExistsStr})) AND id_price IN (?)) OR
                            ((id_price IN ({$idsExistsStr})) AND id_acc IN (?))", $idsStr);
                $objects = $u->getList();
                foreach ($objects as $item) {
                    $idsExists[] = $item["idAcc"];
                    $idsExists[] = $item["idPrice"];
                }
            };
            $data = [];
            foreach ($products as $p)
                if (empty($idsExists) || !in_array($p["id"], $idsExists))
                    foreach ($idsProducts as $idProduct)
                        $data[] = array('id_price' => $idProduct, 'id_acc' => $p["id"]);
            if (!empty($data))
                DB::insertList('shop_sameprice', $data);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить похожие товары!";
            throw new Exception($this->error);
        }
    }

    // Сохранить сопутствующие товары
    private function saveAccompanyingProducts()
    {
        if (!isset($this->input["accompanyingProducts"]))
            return true;

        try {
            foreach ($this->input["ids"] as $id) {
                $idsAcc = [];
                foreach ($this->input["accompanyingProducts"] as $product) {

                    if ($product["id"]) {
                        $t = new DB("shop_accomp", "sa");
                        $t->select("sa.id");
                        $t->where("sa.id_acc = ?", $product["id"]);
                        $t->andWhere("sa.id_price = ?", $id);
                        $result = $t->fetchOne();
                        if (empty($result)) {
                            $t = new DB("shop_accomp", "sa");
                            $t->setValuesFields(["idPrice" => $id, "idAcc" => $product["id"]]);
                            $idsAcc[] = $t->save();
                        } else $idsAcc[] = $result["id"];
                    } else
                    if ($product["idGroup"]) {
                        $t = new DB("shop_accomp", "sa");
                        $t->select("sa.id");
                        $t->where("sa.id_group = ?", $product["idGroup"]);
                        $t->andWhere("sa.id_price = ?", $id);
                        $result = $t->fetchOne();
                        if (empty($result)) {
                            $t = new DB("shop_accomp", "sa");
                            $t->setValuesFields(["idPrice" => $id, "idGroup" => $product["idGroup"]]);
                            $idsAcc[] = $t->save();
                        } else $idsAcc[] = $result["id"];
                    }
                }

                $t = new DB("shop_accomp", "sa");
                $t->where("id_price = ?", $id);
                if (count($idsAcc)) {
                    $idsAcc = implode(",", $idsAcc);
                    $t->andWhere("NOT id IN ($idsAcc)");
                }
                $t->deleteList();
            }

            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить сопутствующие товары!";
            throw new Exception($this->error);
        }
    }

    // Сохранить коментарии
    private function saveComments()
    {
        if (!isset($this->input["comments"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $comments = $this->input["comments"];
            $idsStr = implode(",", $idsProducts);
            $u = new DB('shop_comm', 'sc');
            $u->where('id_price IN (?)', $idsStr)->deleteList();
            foreach ($comments as $c) {
                $showing = 'N';
                $isActive = 'N';
                if ($c["isShowing"])
                    $showing = 'Y';
                if ($c["isActive"])
                    $isActive = 'Y';
                foreach ($idsProducts as $idProduct)
                    $data[] = array(
                        'id_price' => $idProduct, 'date' => $c["date"], 'name' => $c["name"],
                        'email' => $c["email"], 'commentary' => $c["commentary"], 'response' => $c["response"],
                        'showing' => $showing, 'is_active' => $isActive
                    );
            }
            if (!empty($data))
                DB::insertList('shop_comm', $data);
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить комментарии товара!";
            throw new Exception($this->error);
        }
    }

    // Сохранить отзывы по товару
    private function saveReviews()
    {
        if (!isset($this->input["reviews"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $reviews = $this->input["reviews"];
            $idsStr = implode(",", $idsProducts);
            $idsExists = [];
            foreach ($reviews as $review)
                if ($review["id"])
                    $idsExists[] = $review["id"];
            $idsExists = implode(",", $idsExists);
            $u = new DB('shop_reviews');
            if (!$idsExists)
                $u->where('id_price IN (?)', $idsStr)->deleteList();
            else $u->where("NOT id IN ({$idsExists}) AND id_price IN (?)", $idsStr)->deleteList();
            foreach ($reviews as $review) {
                foreach ($idsProducts as $idProduct) {
                    $review["idPrice"] = $idProduct;
                    $u = new DB('shop_reviews');
                    $u->setValuesFields($review);
                    $u->save();
                }
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить отзывы товара!";
            throw new Exception($this->error);
        }
    }

    /*
     * СОХРАНИТЬ ПЕРЕКРЕСТНЫЕ ГРУППЫ
     * crossGroups приходят нумерованным массивом.
     *      Группа - массив свойств, обязательные: id
     * delCrosGroups либо отсутствует (по умолчанию в методе Trye), либо равен "False"
     *
     * если crossGroups отсутствуют - завершение сохранения
     * проходим по id товаров и удаляем их Не главные группы (если не прописана отмена удаления в delCrosGroups)
     * сохраняем новые в shop_price_group
     * фильтруем повторяющиеся записи по id_price==id_price, id_group==id_group, is_main==is_main
     */
    private function saveCrossGroups()
    {
        if (!isset($this->input["crossGroups"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $groups = $this->input["crossGroups"];

            $del = "True";
            if (!empty($this->input['delCrosGroups']))
                $del = $this->input['delCrosGroups'];
            $idsStr = implode(",", $idsProducts);

            if ($del != "False") {
                $u = new DB('shop_price_group', 'spg');
                $u->where('NOT is_main AND id_price in (?)', $idsStr)->deleteList();
                unset($u);
            };
            $chgr = array();
            foreach ($groups as $group) {
                foreach ($idsProducts as $idProduct) {
                    if (empty($chgr[$idProduct][$group["id"]])) {
                        $data[] = array(
                            'id_price' => $idProduct,
                            'id_group' => $group["id"],
                            'is_main' => 0
                        );
                        $chgr[$idProduct][$group["id"]] = true;
                    }
                }
            }
            if (!empty($data)) {
                DB::insertList('shop_price_group', $data, 'INSERT IGNORE INTO');
            }

            DB::query("
                    DELETE a.* FROM shop_price_group a,
                        (SELECT
                            b.id_price, b.id_group, b.is_main, MIN(b.id) mid
                            FROM shop_price_group b
                            GROUP BY b.id_price, b.id_group, b.is_main
                        ) c
                    WHERE
                        a.id_price = c.id_price
                        AND a.id_group = c.id_group
                        AND a.is_main = c.is_main
                        AND a.id > c.mid
                ");
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить дополнительные категории товара!";
            throw new Exception($this->error);
        }
    }

    // @@@@@@ @@@@@@ @@  @@ @@@@@@   @@@@@@ @@  @@ @@    @@ @@@@@@  @@  @@ @@    @@
    // @@     @@  @@  @@@@  @@  @@   @@     @@ @@  @@   @@@ @@   @@ @@ @@  @@   @@@
    // @@     @@  @@   @@   @@@@@@   @@     @@@@   @@  @@@@ @@   @@ @@@@   @@  @@@@
    // @@     @@  @@  @@@@  @@       @@     @@ @@  @@@@  @@ @@   @@ @@ @@  @@@@  @@
    // @@@@@@ @@@@@@ @@  @@ @@       @@@@@@ @@  @@ @@@   @@ @@@@@@  @@  @@ @@@   @@

    // Сохранить скидки
    private function saveDiscounts()
    {

        // если данные отсутствую, передаем просто Истину
        if (!isset($this->input["discounts"]))
            return true;

        // сохранения по id (к столбцу id_price) скидок в таблицу shop_discount_links
        try {
            foreach ($this->input["ids"] as $id)
                DB::saveManyToMany(
                    $id,
                    $this->input["discounts"],
                    array("table" => "shop_discount_links", "key" => "id_price", "link" => "discount_id")
                );
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить скидки товара!";
            throw new Exception($this->error);
        }
    }

    // Разница массива
    private function diffArray($values, $stringMode = false)
    {
        $newValues = array();
        foreach ($values as $value) {
            array_push($newValues, array(
                'id' => $value['id'],
                'idFeature' => $value['idFeature']
            ));
        }
        sort($newValues);
        if ($stringMode) {
            $newValues = json_encode($newValues);
        }
        return $newValues;
    }

    /**
     *
     *
     */
    // Правильные изменения перед сохранением
    private function correctModificationsBeforeSave($tabs)
    {
        $newMod = array();
        foreach ($tabs as $tabIndex => $tab) {
            $newMod[$tabIndex] = $tab;
            $newMod[$tabIndex]['items'] = array();
            $searchBase = array();
            foreach ($tab['items'] as $itemIndex => $item) {
                if ($itemIndex == 0) {
                    $newMod[$tabIndex]['items'][] = $item;
                    $searchBase[] = $this->diffArray($item['values']);
                } else {
                    foreach ($searchBase as $example) {
                        if ($example == $this->diffArray($item['values'])) {
                            continue 2;
                        }
                    }
                    $newMod[$tabIndex]['items'][] = $item;
                    $searchBase[] = $this->diffArray($item['values']);
                }
            }
        }
        return $newMod;
    }

    // Сохранить модификации товара
    private function saveModifications()
    {
        if (!isset($this->input["modifications"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $ifAdd = !empty($this->input["add"]);

            $modifications = $this->correctModificationsBeforeSave($this->input["modifications"]);

            if ($this->isNew)
                foreach ($modifications as &$mod1)
                    foreach ($mod1["items"] as &$item1) {
                        $item1["id"] = null;
                        if (empty($item1["article"]))
                            $item1["article"] = $this->input["article"];
                    }

            $idsStr = implode(",", $idsProducts);
            $isMultiMode = sizeof($idsProducts) > 1;

            $namesToIds = [];
            if (!empty($this->newImages)) {
                $imagesStr = '';
                foreach ($this->newImages as $image) {
                    if (!empty($imagesStr))
                        $imagesStr .= ',';
                    $imagesStr .= "'$image'";
                }
                $u = new DB('shop_img', 'si');
                $u->select('id, picture');
                $u->where('picture IN (?)', $imagesStr);
                $u->andWhere('id_price IN (?)', $idsStr);
                $objects = $u->getList();

                foreach ($objects as $item)
                    $namesToIds[$item['picture']] = $item['id'];
            }

            // Собираем существующие модификации
            if (!$isMultiMode) {
                $idsUpdateM = null;
                foreach ($modifications as $mod) {
                    foreach ($mod["items"] as $item) {
                        if (!empty($item["id"])) {
                            if (!empty($idsUpdateM))
                                $idsUpdateM .= ',';
                            $idsUpdateM .= $item["id"];
                        }
                    }
                }
            }
            // Удаление лишних модификаций когда идет замена
            #if (!$ifAdd) {
            $u = new DB('shop_modifications', 'sm');
            if (!empty($idsUpdateM))
                $u->where("NOT id IN ($idsUpdateM) AND id_price in (?)", $idsStr)->deleteList();
            else $u->where("id_price IN (?)", $idsStr)->deleteList();
            /*} else {
                $u = new DB('shop_modifications', 'sm');
                $u->select('sm.id, sm.id_price, smf.id_feature, smf.id_value');
                $u->innerJoin('shop_modifications_feature smf', 'smf.id_modification = sm.id');
                $u->where('sm.id_price IN (?)', $idsStr);
                $tems = $u->getList();
            }*/
            // новые модификации
            $dataM = [];
            $dataF = [];
            $dataI = [];
            $result = DB::query("SELECT MAX(id) FROM shop_modifications")->fetch();
            $i = $result[0] + 1;

            foreach ($modifications as $mod) {
                foreach ($mod["items"] as $item) {
                    if (empty($item["id"]) || $isMultiMode) {
                        $count = null;
                        if ($item["count"] >= 0)
                            $count = $item["count"];
                        foreach ($idsProducts as $idProduct) {
                            $notAdd = false;
                            $newDataM = $newDataF = $newDataI = null;

                            $newDataM = array(
                                'id' => $i,
                                'code' => $item["article"],
                                'id_mod_group' => $mod["id"],
                                'id_price' => $idProduct,
                                'value' => $item["priceRetail"],
                                'value_opt' => $item["priceSmallOpt"],
                                'value_opt_corp' => $item["priceOpt"],
                                'count' => $count,
                                'sort' => (int)$item["sortIndex"],
                                'description' => $item["description"]
                            );

                            foreach ($item["values"] as $v)
                                $dataF[] = array(
                                    'id_price' => $idProduct,
                                    'id_modification' => $i,
                                    'id_feature' => $v["idFeature"],
                                    'id_value' => $v["id"]
                                );
                            foreach ($item["images"] as $img) {
                                if ($img["id"] <= 0)
                                    $img["id"] = $namesToIds[$img["imageFile"]];
                                $newDataI = array(
                                    'id_modification' => $i,
                                    'id_img' => $img["id"],
                                    'sort' => $img["sortIndex"]
                                );
                            }

                            if (isset($tems) || $ifAdd) {
                                foreach ($tems as $it) {
                                    if ($it['idPrice'] == $newDataM['id_price']/* and $it['idValue'] == $newDataF['id_value']*/) {
                                        $notAdd = true;
                                    }
                                }
                            }

                            if (!$notAdd) {
                                if (!empty($newDataM))
                                    $dataM[] = $newDataM;
                                if (!empty($newDataF))
                                    $dataF[] = $newDataF;
                                if (!empty($newDataI))
                                    $dataI[] = $newDataI;
                                $i++;
                            }
                        }
                    }
                }
            }

            try {
                if (!empty($dataM)) {
                    DB::insertList('shop_modifications', $dataM);
                    if (!empty($dataF)) {
                        DB::insertList('shop_modifications_feature', $dataF);
                    }
                    if (!empty($dataI)) {
                        DB::insertList('shop_modifications_img', $dataI);
                    }
                    $dataI = null;
                }
            } catch (Exception $e) {
                throw new Exception();
            }
            // обновление модификаций
            if (!$isMultiMode) {
                foreach ($modifications as $mod) {
                    foreach ($mod["items"] as $item) {
                        if (!empty($item["id"])) {
                            $u = new DB('shop_modifications', 'sm');
                            $item["code"] = $item["article"];
                            $item["value"] = $item["priceRetail"];
                            $item["valueOpt"] = $item["priceSmallOpt"];
                            $item["valueOptCorp"] = $item["priceOpt"];
                            $item["sort"] = $item["sortIndex"];
                            $u->setValuesFields($item);
                            $u->save();

                            $u = new DB('shop_modifications_img', 'smi');
                            $u->where("id_modification = ?", $item["id"])->deleteList();
                            $dataI = [];
                            foreach ($item["images"] as $img) {
                                if ($img["id"] <= 0)
                                    $img["id"] = $namesToIds[$img["imageFile"]];
                                $dataI[] = array(
                                    'id_modification' => $item["id"], 'id_img' => $img["id"],
                                    'sort' => $img["sortIndex"]
                                );
                            }
                            if (!empty($dataI))
                                DB::insertList('shop_modifications_img', $dataI);
                        }
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить модификации товара!";
            throw new Exception($this->error);
        }
    }

    // Сохранить категорию товара
    private function saveIdGroup()
    {

        if (!isset($this->input["idGroup"]))
            return true;

        try {
            $idsProducts = $this->input["ids"];
            $idGroup = $this->input["idGroup"];
            $idsStr = implode(",", $idsProducts);
            $u = new DB('shop_price_group');
            $u->where('is_main AND id_price IN (?)', $idsStr)->deleteList();

            $chgr = array();
            foreach ($idsProducts as $idProduct) {

                if (empty($chgr[$idProduct][$idGroup])) {
                    $u = new DB('shop_price_group');
                    $u->where('id_price = ? AND id_group = ' . $idGroup, $idProduct)->deleteList();

                    $group = array();
                    $group["idPrice"] = $idProduct;
                    $group["idGroup"] = $idGroup;
                    $group["isMain"] = true;

                    $u = new DB('shop_price_group');
                    $u->setValuesFields($group);
                    $u->save();

                    $chgr[$idProduct][$idGroup] = true;
                }
            }

            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить категорию товара!";
            throw new Exception($this->error);
        }
    }

    // Сохранить доп. информацию о товаре
    private function saveCustomFields()
    {
        if (!isset($this->input["customFields"]) && !$this->input["customFields"])
            return true;

        try {
            $idProduct = $this->input["id"];
            $groups = $this->input["customFields"];
            $customFields = [];
            foreach ($groups as $group)
                foreach ($group["items"] as $item)
                    $customFields[] = $item;
            foreach ($customFields as $field) {
                $field["idPrice"] = $idProduct;
                $u = new DB('shop_price_userfields', 'cu');
                $u->setValuesFields($field);
                $u->save();
            }
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить доп. информацию о товаре!";
            throw new Exception($this->error);
        }
    }

    // Сохранить добавленную инфу
    protected function saveAddInfo()
    {
        if (!isset($this->input["ids"]))
            return false;
        $this->reLinkGroups();

        return $this->saveImages() && $this->saveSpecifications() && $this->saveSimilarProducts() &&
            $this->saveAccompanyingProducts() && $this->saveComments() && $this->saveReviews() &&
            $this->saveCrossGroups() && $this->saveDiscounts() && $this->saveMeasure() &&
            $this->saveModifications() && $this->saveIdGroup() &&
            $this->saveCustomFields() && $this->saveFiles() && $this->saveOptions() && $this->saveLabels();
    }



    // Получить группЫ
    protected function getGroup($groups, $idGroup)
    {
        if (!$idGroup)
            return null;

        // разложение строки на элементы
        $groupsLine = explode(",", $idGroup);

        // прогонка всех элементов через цикл
        $nameGroups = null;
        foreach ($groupsLine as $groupLine) {
            foreach ($groups as $group) {
                if ($group["id"] == $groupLine) {
                    if ($group['upid'])
                        $nameGroups .= $this->getGroup($groups, $group['upid']) . "/" . $group["name"] . ',';
                    else
                        $nameGroups .= $group["name"] . ',';
                }
            }
        }
        $nameGroups = chop($nameGroups, ','); // удаление поседней запятой

        return $nameGroups;
    }


    // @@@@@@ @@@@@@ @@@@@@@@ @@@@@@ @@@@@@ @@@@@@ @@  @@ @@@@@@ @@@@@@ @@@@@
    // @@     @@        @@    @@     @@  @@ @@  @@ @@  @@ @@  @@ @@         @@
    // @@     @@@@@@    @@    @@     @@@@@@ @@  @@ @@  @@ @@@@@@ @@@@@  @@@@@
    // @@  @@ @@        @@    @@  @@ @@ @@  @@  @@ @@  @@ @@         @@     @@
    // @@@@@@ @@@@@@    @@    @@@@@@ @@  @@ @@@@@@ @@@@@@ @@     @@@@@  @@@@@

    // Получить группЫ 53
    protected function getGroup53($groups, $idGroup)
    {
        if (!$idGroup)
            return null;

        // разложение строки на элементы
        $groupsLine = explode(",", $idGroup);

        // выстраивание пути из ид в имена
        $nameGroups = null;
        foreach ($groupsLine as $groupLine) {
            foreach ($groups as $group) {
                if ($group["id"] == $groupLine) {
                    $nameGroups .= $group["name"] . ',';
                }
            }
        }
        $nameGroups = chop($nameGroups, ','); // удаление последней запятой

        return $nameGroups;
    }


    // @@@@@@ @@  @@ @@@@@@ @@@@@@ @@@@@@ @@@@@@@@
    // @@      @@@@  @@  @@ @@  @@ @@  @@    @@
    // @@@@@@   @@   @@@@@@ @@  @@ @@@@@@    @@
    // @@      @@@@  @@     @@  @@ @@ @@     @@
    // @@@@@@ @@  @@ @@     @@@@@@ @@  @@    @@

    // Экспорт
    public function export()
    {

        // прием данных из формы
        $input = $this->input;

        // определяем параметры файла
        $fileName = "export_products.xlsx";
        $oldFileName = "old_export_products.xlsx";
        $filePath = DOCUMENT_ROOT . "/files/tempfiles";
        if (!file_exists($filePath) || !is_dir($filePath))
            mkdir($filePath, 0766, true);
        $temporaryFilePath = "{$filePath}";
        // создать директорию, если отсутствует
        // if (!is_dir($temporaryFilePath))
        //     mkdir($temporaryFilePath, 0777);
        $oldFilePath = $filePath . "/{$oldFileName}";
        $filePath .= "/{$fileName}";
        $urlFile = '//' . HOSTNAME . "/files/tempfiles/{$fileName}";

        try {

            $export = new ProductExport($this->input);

            /*
             * ЭКСПОРТ прохобит в 2 этапа:
             *   1. ПОЛУЧЕНИЕ ПРЕВЬЮ: Получение заголовков + модификаций для чекбокс-листа (выбора экспортируемых столбцов)
             *   2. ПОЛУЧЕНИЕ ФАЙЛА:  Получение заголовков + модификаций + листа записей по товарам с записью в файл
             *
             * тк в обоих шагах используется function export() - создана вилка.
             * На переключение влияет параметр  $this->input['statusPreview']  true или отсутствие
             */

            if ($input['statusPreview'] == true) {
                // получаем заголовки + модификации товаров
                $headerCSV = $export->previewExport($temporaryFilePath);
            } else {
                // получение данных из базы (заголовки,товары)
                $pages = $export->mainExport($input, $fileName, $filePath, $oldFilePath, $temporaryFilePath);
            };

            // передача в Ajax
            $this->result['pages'] = $pages;
            $this->result['url'] = $urlFile;
            $this->result['name'] = $fileName;
            $this->result['headerCSV'] = $headerCSV;
        } catch (Exception $e) {
            // ошибка экспорта
            $this->error = $e->getMessage();
            //$this->error = "Не удаётся экспортировать товары!";
            throw new Exception($this->error);
        }
    }


    // @@@@@@ @@@@@@ @@@@@@ @@@@@@@@
    // @@  @@ @@  @@ @@        @@
    // @@@@@@ @@  @@ @@@@@@    @@
    // @@     @@  @@     @@    @@
    // @@     @@@@@@ @@@@@@    @@

    // После
    public function post($tempFile = FALSE)
    {
        $this->rmdir_recursive(DOCUMENT_ROOT . "/files/tempfiles");  // очистка директории с временными файлами
        unset($_SESSION["getId"]);                                       // очистка временных данных по связям товар-группа
        if ($items = parent::post(true)) {
            $this->import($items[0]["url"], $items[0]["name"]);
        }
    }


    // @@@@@@ @@     @@ @@@@@@ @@@@@@ @@@@@@ @@@@@@@@
    //   @@   @@@   @@@ @@  @@ @@  @@ @@  @@    @@
    //   @@   @@ @@@ @@ @@@@@@ @@  @@ @@@@@@    @@
    //   @@   @@  @  @@ @@     @@  @@ @@ @@     @@
    // @@@@@@ @@     @@ @@     @@@@@@ @@  @@    @@

    // Импорт
    public function import($url = null, $fileName = null)
    {

        if (!empty($_POST)) $_SESSION["options"] = $_POST;
        /*
         * if   превью импорта
         * else непосредственный импорт
         *
         * $this->result - отправка в Ajax
         * $headerCSV - заголовки CSV?
         */
        if (!is_null($fileName)) {
            return $this->productsImport($url, $fileName);
        } else {
            $import = new Import($this->input);
            $options = $_SESSION["options"];
            $pages = $import->startImport(
                $this->input['filename'],
                false,
                $options,
                $this->input['prepare'][0],
                $this->input['cycleNum'],
                $this->input['last']
            );

            /**
             * @var float $this->result['pages']        всего страниц
             * @var Integer $this->result['countPages'] колво прочитанных страниц
             * @var Integer $this->result['cycleNum']   колво обработанных страниц
             */
            $this->result['pages'] = $_SESSION["pages"];
            $this->result['countPages'] = $_SESSION["countPages"];
            $this->result['cycleNum'] = $_SESSION["cycleNum"];
            $this->result['errors'] = implode('<br/>', $_SESSION['errors']);
            $this->reLinkGroups();
            return true;
        }
    }


    // @@@@@@ @@@@@@ @@@@@@ @@@@@@    @@@@@@ @@     @@ @@@@@@
    // @@  @@ @@  @@ @@  @@ @@   @@     @@   @@@   @@@ @@  @@
    // @@@@@@ @@@@@@ @@  @@ @@   @@     @@   @@ @@@ @@ @@@@@@
    // @@     @@ @@  @@  @@ @@   @@     @@   @@  @  @@ @@
    // @@     @@  @@ @@@@@@ @@@@@@    @@@@@@ @@     @@ @@

    // Превью импорта
    private function productsImport($url, $fileName)
    {
        /** @param array $this->result возвращаемые клиенту данные */
        $import = new Import($this->input);
        $options = $_SESSION["options"];
        $this->result = $import->startImport(
            $fileName,
            true,
            $options,
            !empty($this->input['prepare'][0]) ? $this->input['prepare'][0] : 0,
            0
        );
        $this->result['errors'] = implode('<br/>', $_SESSION['errors']);
        return true;
    }

    // Создать группу
    function createGroup(&$groups, $idParent, $name)
    {
        foreach ($groups as $group) {
            if ($group['upid'] == $idParent && trim($group['name']) == trim($name))
                return $group['id'];
        }

        $u = new DB('shop_group', 'sg');
        $data["codeGr"] = Category::getUrl(strtolower(se_translite_url(trim($name))));
        $data["name"] = trim($name);
        if ($idParent)
            $data["upid"] = $idParent;
        $u->setValuesFields($data);
        $id = $u->save();

        $group = [];
        $group["id"] = $id;
        $group['name'] = trim($name);
        $group["codeGr"] = $data["codeGr"];
        $group['upid'] = $idParent;
        $groups[] = $group;

        return $id;
    }

    // Создать группу 53
    private function createGroup53(&$groups, $idParent, $name)
    {
        foreach ($groups as $group) {
            if ($group['upid'] == $idParent && $group['name'] == $name)
                return $group['id'];
        }

        $u = new DB('shop_group', 'sg');
        $data["codeGr"] = Category::getUrl(strtolower(se_translite_url(trim($name))));
        $data["name"] = $name;
        $u->setValuesFields($data);
        $id = $u->save();

        $group = [];
        $group["id"] = $id;
        $group['name'] = $name;
        $group["codeGr"] = $data["codeGr"];
        $group['upid'] = $idParent;
        $groups[] = $group;

        Category::saveIdParent($id, $idParent);

        return $id;
    }

    public function getLabels($idProduct = null)
    {
        $idProduct = $idProduct ? $idProduct : $this->input["id"];
        $result = [];
        $labels = (new ProductLabel())->fetch();
        $u = new DB("shop_label_product");
        $u->select("id_label");
        $u->where("id_product = ?", $idProduct);
        $items = $u->getList();
        foreach ($labels as $label) {
            $isChecked = false;
            foreach ($items as $item)
                if ($isChecked = ($label["id"] == $item["idLabel"]))
                    break;
            $label["isChecked"] = $isChecked;
            $result[] = $label;
        }
        return $result;
    }

    private function saveLabels()
    {
        $labels = $this->input["labels"];
        $labelsNew = [];
        foreach ($labels as $label)
            if ($label["isChecked"])
                $labelsNew[] = $label;
        try {
            foreach ($this->input["ids"] as $id)
                DB::saveManyToMany(
                    $id,
                    $labelsNew,
                    array("table" => "shop_label_product", "key" => "id_product", "link" => "id_label")
                );
            return true;
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить ярлыки товара!";
            throw new Exception($this->error);
        }
    }
}
