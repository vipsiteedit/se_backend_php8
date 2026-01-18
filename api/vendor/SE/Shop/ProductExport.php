<?php

namespace SE\Shop;

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/lib/Spout/Autoloader/autoload.php';

use SE\Shop\Product;
use SE\DB;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;


class ProductExport extends Product
{
    private $fields = []; /*['category', 'codeGroup', 'code', 'article', 'name', 
		'price', 'priceOptCorp', 'priceOpt', 'pricePurchase', 'bonus', 'nameBrand', 
		'images', 'codeCurrency', 'measurement', 'count', 'stepCount', 
		'note', 'text','flagNew', 'flagHit', 'enabled', 'isMarket',
		'weight', 'volume', 'pageTitle', 'measuresWeight', 'measuresVolume', 'minCount', 'delivTime', 
		'idAcc', 'metaHeader', 'metaKeywords', 'metaDescription', 'features'
		];
		*/

    private function getFields()
    {
        list($mainRequest, $pages) = $this->shopPrice(1, 0);
        $goodsL  = $mainRequest->getList(1, 0);  // получение лимитированного списка товаров

        /** заголовки (без модификаций) */
        $this->fields = array();
        // Нужно собрать информацию по полям по всем записям
        foreach ($goodsL[0] as $k => $v) {
            if (!empty($this->rusCols[$k]) && !in_array($k, array('id', 'shopIdGroup'))) {
                $this->fields[] = $k;
            }
        }
    }


    public function previewExport($temporaryFilePath)
    {
        /**
         *  @@@@@@ @@@@@@ @@@@@@ @@    @@    @@@@@@ @@  @@ @@@@@@  | превью экспорта
         *  @@  @@ @@  @@ @@     @@    @@    @@      @@@@  @@  @@  |
         *  @@@@@@ @@@@@@ @@@@@@  @@  @@     @@@@@@   @@   @@@@@@  |
         *  @@     @@ @@  @@       @@@@      @@      @@@@  @@      |
         *  @@     @@  @@ @@@@@@    @@       @@@@@@ @@  @@ @@      |
         */

        $this->rmdir_recursive($temporaryFilePath);  // очистка директории с временными файлами

        /** получение образца экспорта */
        list($mainRequest, $pages) = $this->shopPrice(1, 0);
        $goodsL  = $mainRequest->getList(1, 0);  // получение лимитированного списка товаров

        /** заголовки (без модификаций) */
        $headerCSV = array();
        // Нужно собрать информацию по полям по всем записям
        foreach ($goodsL[0] as $k => $v)
            if (!empty($this->rusCols[$k]) && !in_array($k, array('id', 'shopIdGroup'))) {
                array_push($headerCSV, $this->rusCols[$k]);
            }

        /** прикрепляем столбцы модификаций */
        $modsCols = $this->modsCols();
        foreach ($modsCols as $v) {
            array_push($headerCSV, $v['name']);
        }

        /**
         * ПОЛУЧЕНИЕ СВЯЗИ МОДИФИКАЦИЯ-ПАРАМЕТР  для чекбокс листа
         *
         * Схема:
         * shop_modifications_group  <  shop_modifications  <    shop_modifications_feature    >  shop_feature
         * name                  id  <  id_mod_group    id  <  id_modification     id_feature  >  id      name
         *
         * не доделана : нужно проверять работоспособность запроса
         */

        // $u = new DB('shop_modifications_feature', 'smf');
        // $u->select('smg.name modification, sf.name feature');
        // $u->leftJoin('shop_modifications sm', 'sm.id = smf.id_modification');
        // $u->leftJoin('shop_modifications_group smg', 'smg.id = sm.id_mod_group');
        // $u->leftJoin('shop_feature sf', 'sf.id = smf.id_feature');
        // $u->where('smg.name != ""');
        // $u->orderBy('smg.name, sf.name');
        // $u->groupBy('smg.name, sf.name');
        // $result = $u->getList();
        // unset($u);

        return $headerCSV;
    } // превью экспорта

    public function mainExport($input, $fileName, $filePath, $oldFilePath, $temporaryFilePath)
    {

        /**
         *  @@     @@    @@    @@@@@@ @@    @@    @@@@@@ @@  @@ @@@@@@ @@@@@@ @@@@@@ @@@@@@@@  | Экспорт
         *  @@@   @@@   @@@@     @@   @@@   @@    @@      @@@@  @@  @@ @@  @@ @@  @@    @@     |
         *  @@ @@@ @@  @@  @@    @@   @@@@@ @@    @@@@@@   @@   @@@@@@ @@  @@ @@@@@@    @@     |
         *  @@  @  @@ @@@@@@@@   @@   @@  @@@@    @@      @@@@  @@     @@  @@ @@ @@     @@     |
         *  @@     @@ @@    @@ @@@@@@ @@   @@@    @@@@@@ @@  @@ @@     @@@@@@ @@  @@    @@     |
         *
         * @param $writer;   // данные по временным файлам
         * @param $line;     // номер линии
         * @param $column;   // колонки
         * @param $limit;    // макс выдачи в одном запросе к БД
         * @param $offset;   // начальный номер выдачи
         * @param $cycleNum; // номер нынешней страницы
         * @return $pages;   // кол-во страниц
         */


        // объявление параметров для экспорта
        $cycleNum                = $input['cycleNum'];
        $expModif                = $input['expModif'];
        $params                     = $input['params'];
        $limit                   = 1000;
        $offset                  = $cycleNum * $limit;
        $line                    = 1;
        $formData                = $input['columns'];
        $goodsIndex              = [];
        $column                  = array();
        $headerCSV               = array();
        $writer                  = array();
        $this->temporaryFilePath = $temporaryFilePath;

        /**
         * запрос на получение __листа_товаров__
         * Пакетный запрос к БД с перезагрузкой соединения к БД
         * сбор данных о $limit товарах за проход __цикла__
         */
        $this->getFields();
        list($mainRequest, $pages) = $this->shopPrice($limit, $offset, intval($input['idGroup']), $params);
        $goodsL  = $mainRequest->getList($limit, $offset);  // получение лимитированного списка товаров


        if (!empty($goodsL)) {
            $this->exportCycle(
                $writer,
                $line,
                $goodsL,
                $goodsIndex,
                $filePath,
                $formData,
                $cycleNum,
                $expModif,
                $column,
                $pages,
                $fileName,
                $input
            );  // Запись из БД в файл
        }

        if ($cycleNum == $pages - 1)
            $this->assembly($pages, $filePath);

        return $pages;   // возврат в Ajax колво страниц в формируемом файле
    } // Экспорт

    public function exportCycle(
        $writer,
        $line,
        $goodsL,
        $goodsIndex,
        $filePath,
        $formData,
        $cycleNum,
        $expModif,
        $column,
        $pages,
        $fileName,
        $input
    ) {

        /**
         *  @@@@@@ @@  @@ @@@@@@ @@     @@@@@@  | цикл экспорта
         *  @@     @@  @@ @@     @@     @@      |
         *  @@      @@@@  @@     @@     @@@@@@  |
         *  @@       @@   @@     @@     @@      |
         *  @@@@@@   @@   @@@@@@ @@@@@@ @@@@@@  |
         */


        // фильтрация значений
        $goodsLFilter = [];
        foreach ($goodsL as $i) {
            if (!empty($i['stepCount']) && $i['stepCount'] == 1) {
                $i['stepCount'] = '';
            }
            if (!empty($i['images'])) {
                $images = explode(',', $i['images']);
                $i['images'] = '';
                foreach ($images as $n => $img) {
                    $ext = explode(".", $img);
                    $ext = end($ext);
                    if (!in_array(strtolower($ext), array('png', 'jpg', 'jpeg', 'gif'))) {
                        unset($images[$n]);
                    }
                }
                if (!empty($images))
                    $i['images'] = implode(',', $images);
            }
            array_push($goodsLFilter, $i);
        }
        $goodsL = $goodsLFilter;
        if ($expModif == 'Y') {
            $featCols      = $this->featuresCols(array(intval($input['idGroup'])));      // особенности
            $modsCols      = $this->modsCols(array(intval($input['idGroup'])));      // особенности
        }

        if ($cycleNum == 0) {
            $groups        = $this->groups();        // группы товаров
        }

        /** получаем ids товаров для запроса модификаций */
        $idsProducts = array();
        foreach ($goodsL as $l)
            array_push($idsProducts, $l['id']);

        if ($expModif == 'Y') {
            $features = $this->features($idsProducts); // характеристики товаров
            $modifications = $this->modifications($idsProducts); // модификации товаров
        }
        // Получим список всех существующих полей
        /*
		$header = array();
		if ($cycleNum == 0)
		foreach ($goodsL as $good) {
			$gfields = array_keys($good);
			foreach($gfields as $f) {
				if (!in_array($f, $header) 
				&& !in_array($f, array('id', 'shopIdGroup', 'idModification', 'idGroup', 'idAcc', 'features'))) {
					$header[] = $f;
				}
			}
		}
		
		*/

        $excludingKeys = array("idGroup", "presence", "idModification");
        $rusCols       = $this->rusCols;
        //if ($cycleNum == 0)
        //    $header    = array_keys($goodsL[0]);


        $headerCSV     = [];

        $tempGoodsL = array();

        foreach ($goodsL as $good) {
            if (CORE_VERSION != "5.2") {
                $good["category"] = parent::getGroup53($groups, $good["idGroup"]);
            } else {
                $good["category"] = parent::getGroup($groups, $good["idGroup"]);
            }
            array_push($tempGoodsL, $good);
        }
        $goodsL = $tempGoodsL;
        $tempGoodsL = array();


        foreach ($goodsL as $item) {
            foreach ($modsCols as $col) {
                $item[$col['name']] = null;
            }
            $goodsIndex[$item["id"]] = &$item;
            array_push($tempGoodsL, $item);
        }
        $goodsL = $tempGoodsL;
        unset($tempGoodsL);

        if ($expModif == 'Y') {
            $goodsL = $this->mergerWithFeatures($goodsL, $features, $featCols); // сливаем списки товаров и их характеристик
            $goodsL = $this->mergerWithMoffification($goodsL, $modifications, $modsCols); // сливаем списки товаров и их модификаций
        }
        /*
        if ($cycleNum == 0) {
            foreach ($header as $col)
                if (!in_array($col, $excludingKeys)) {
                    $col         = iconv('utf-8', 'utf-8', $rusCols[$col] ? $rusCols[$col] : $col); // CP1251
                    $headerCSV[] = $col;
                }

            foreach ($featCols as $v) {
                array_push($headerCSV, '#'.$v['name']);
            }
			foreach ($modsCols as $v) {
                array_push($headerCSV, $v['name']);
            }
        }
*/
        /**
         * ФОРМИРОВАНИЕ ФАЙЛА
         *
         * определяем колво заголовков и генерируем список столбцов по длине
         *
         * замена значений на пользовательские
         * размета по столбцам (координаты)
         * записываем заголовки
         * вывод товаров без модификаций
         * вывод товаров с модификациями
         * записываем в файл
         */


        $column_number             = 0;
        $i                         = 0;
        $header                    = null;
        $lastId                    = null;
        $goodsItem                 = [];
        // Было numColumn вместо column
        list($goodsL, $headerCSV) = $this->customValues($formData, $goodsL, $goodsIndex);
        $last_column               = count($headerCSV);
        if ($cycleNum == 0)
            $column                = $this->columnLayout($column_number, $column, $last_column);
        if ($cycleNum == 0)
            list($writer, $line)   = $this->recordHeaders($writer, $headerCSV, $line);
        list($writer, $line)       = $this->recorRow($writer, $goodsL, $excludingKeys, $column, $line);
        $this->writTempFiles($writer, $cycleNum);
    } // цикл / завершение экспорта



    private function getSearch($search)
    {
        $searchFields = [
            ["title" => "Код", "field" => "sp.code"],
            ["title" => "Наименование", "field" => "sp.name", "active" => true],
            ["title" => "Артикул", "field" => "sp.article", "active" => true],
            ["title" => "Группа", "field" => "sg.name"],
            ["title" => "Бренд", "field" => "sb.name"]
        ];

        $searchItem = trim($search);
        if (empty($searchItem))
            return array();
        $where = array();
        $searchWords = explode(' ', $searchItem);
        foreach ($searchWords as $searchItem) {
            $result = array();
            if (!trim($searchItem)) continue;
            if (is_string($searchItem))
                $searchItem = trim(DB::quote($searchItem), "'");

            $time = 0;
            if (strpos($searchItem, "-") !== false) {
                $time = strtotime($searchItem);
            }

            foreach ($searchFields as $field) {
                $result[] = $field["field"] . " LIKE '%{$searchItem}%'";
            }
            if (!empty($result))
                $where[] = '(' . implode(" OR ", $result) . ')';
        }
        return implode(" AND ", $where);
    }

    private function shopPrice($limit, $offset, $idGroup = 0, $params = array())
    {
        /**
         *  @@@@@@@@ @@@@@@ @@@@@     @@    @@@@@@ @@     @ | ПОЛУЧЕНИЕ
         *     @@    @@  @@ @@  @@   @@@@   @@  @@ @@     @ | листа товаров
         *     @@    @@  @@ @@@@@   @@  @@  @@@@@@ @@@@@@ @ |
         *     @@    @@  @@ @@  @@ @@@@@@@@ @@     @@  @@ @ |
         *     @@    @@@@@@ @@@@@  @@    @@ @@     @@@@@@ @ |
         */


        // получаем данные из БД
        $u = new DB('shop_price', 'sp');
        $u->reConnection();  // перезагрузка запроса
        $u->select('COUNT(*) `count`');
        $u->leftJoin('shop_group sg', 'sg.id = sp.id_group');
        $u->leftJoin('shop_brand sb', 'sb.id = sp.id_brand');
        if (intval($idGroup) && empty($params['ids']) && empty($params['allModeLastParams']['filters']))
            $u->where('sp.id_group=?', intval($idGroup));
        elseif (!empty($params)) {
            if (!empty($params['ids'])) {
                $u->where('sp.id IN (?)', join(',', $params['ids']));
            } else {
                $u->where('true');
                foreach ($params['allModeLastParams']['filters'] as $filter) {
                    if ($filter['field'] == 'idGroup') {
                        $u->andWhere('sp.id_group IN (?)', $filter['value']);
                    }
                    if ($filter['field'] == 'idBrand') {
                        $u->andWhere('sp.id_brand IN (?)', $filter['value']);
                    }
                }
                if (trim($params['allModeLastParams']['searchText'])) {
                    $u->andWhere($this->getSearch(trim($params['allModeLastParams']['searchText'])));
                }
            }
            //"params":{"allModeLastParams":{"offset":0,"limit":15,"searchText":"","filters":[{"field":"idGroup","sign":"IN","value":"21,278,79,284,80,307,132,131,130,128,129,216,265,266,267,268,269"}]},"allModeParams":{},"allMode":true}}: 		
        }

        $result = $u->getList();
        $count = $result[0]["count"];
        $pages = ceil($count / $limit);

        // подключение к shop_price
        $u = new DB('shop_price', 'sp');

        // НАЧАЛО ЗАПРОСА
        $select = '
                sp.id id,
                NULL category';

        if (CORE_VERSION != "5.2") {
            // получение дополнительных категорий
            $select .= ',

                    GROUP_CONCAT(DISTINCT
                        spg.id_group
                        ORDER BY spg.is_main DESC
                        SEPARATOR ","
                    ) AS shop_id_group,

                    GROUP_CONCAT(DISTINCT
                        spg.id_group
                        ORDER BY spg.is_main DESC
                        SEPARATOR ","
                    ) AS idGroup,

                    GROUP_CONCAT(DISTINCT
                        sg.code_gr
                        ORDER BY spg.is_main DESC
                        SEPARATOR ","
                    ) AS code_group

                ';
        } else {
            $select .= ',
                    sp.id_group shop_id_group,
                    sp.id_group IdGroup,
                    sg.code_gr code_group
                ';
        }
        $select .= ',

                sp.id, sp.code code, sp.article article,
                sp.name name, 
				sp.price_purchase price_purchase, sp.price_opt price_opt, sp.price_opt_corp price_opt_corp, sp.price price, sp.bonus bonus,
				sb.name name_brand,
				(SELECT GROUP_CONCAT(DISTINCT
                    si.picture
                    SEPARATOR \',\'
                ) FROM shop_img `si` WHERE si.id_price=sp.id ORDER BY si.default DESC) AS images,
                sp.curr codeCurrency, sp.measure measurement,
                sp.presence_count count, sp.step_count step_count,
                sp.presence presence, sp.flag_new, sp.flag_hit, sp.enabled, sp.is_market,
                sp.weight weight, sp.volume volume, sp.page_title,
                CONCAT(
                    IFNULL(smw1.name, \'\'),\',\',
                    IFNULL(smw2.name, \'\')
                ) measuresWeight,
                CONCAT(
                    IFNULL(smv1.name, \'\'),\',\',
                    IFNULL(smv2.name, \'\')
                ) measuresVolume,

                sp.min_count,
				CONCAT_WS(":",sp.delivery_time,sp.signal_dt) delivTime,

                GROUP_CONCAT(DISTINCT
                    sa.id_acc
                    SEPARATOR \',\'
                ) AS idAcc,

                sp.title metaHeader, sp.keywords metaKeywords, sp.description metaDescription,
                sp.note, sp.text, sm.id idModification, "" AS features
            ';

        /** все запршиваемые поля должны использоваться в импорте или удалятся при обработке,
         *  иначе идет сдвиг столбцов и модификации не отображаются
         *  features должен возвращаться! тк если нет в товарах характеристик - столбец пропадает
         */

        if (CORE_VERSION != "5.2") {
            $u->select($select);
            $u->leftJoin("shop_price_group spg", "spg.id_price = sp.id");
            $u->leftJoin('shop_group sg', 'sg.id = spg.id_group');
        } else {
            $u->select($select);
            $u->leftJoin('shop_group sg', 'sg.id = sp.id_group');
        };


        $u->leftJoin('shop_modifications sm', 'sm.id_price = sp.id');
        //$u->leftJoin('shop_img si', 'si.id_price = sp.id');
        $u->leftJoin('shop_brand sb', 'sb.id = sp.id_brand');

        $u->leftJoin('shop_price_measure spm', 'spm.id_price = sp.id');
        $u->leftJoin('shop_measure_weight smw1', 'smw1.id = spm.id_weight_view');
        $u->leftJoin('shop_measure_weight smw2', 'smw2.id = spm.id_weight_edit');
        $u->leftJoin('shop_measure_volume smv1', 'smv1.id = spm.id_volume_view');
        $u->leftJoin('shop_measure_volume smv2', 'smv2.id = spm.id_volume_edit');
        $u->leftJoin('shop_accomp sa', 'sa.id_price = sp.id');

        $u->orderBy('sp.id');
        $u->groupBy('sp.id');
        if (intval($idGroup) && empty($params))
            $u->where('sp.id_group=?', intval($idGroup));
        elseif (!empty($params)) {
            if (!empty($params['ids'])) {
                $u->where('sp.id IN (?)', join(',', $params['ids']));
            } else {
                $u->where('true');
                foreach ($params['allModeLastParams']['filters'] as $filter) {
                    writeLog($filter);
                    if ($filter['field'] == 'idGroup') {
                        $u->andWhere('sp.id_group IN (?)', $filter['value']);
                    }
                    if ($filter['field'] == 'idBrand') {
                        $u->andWhere('sp.id_brand IN (?)', $filter['value']);
                    }
                }
                if (trim($params['allModeLastParams']['searchText'])) {
                    $u->andWhere($this->getSearch(trim($params['allModeLastParams']['searchText'])));
                }
            }
            //"params":{"allModeLastParams":{"offset":0,"limit":15,"searchText":"","filters":[{"field":"idGroup","sign":"IN","value":"21,278,79,284,80,307,132,131,130,128,129,216,265,266,267,268,269"}]},"allModeParams":{},"allMode":true}}: 		
        }
        return [$u, $pages];
    } // получение листа товаров

    private function featuresCols($idsGroups = array())
    {
        $u = new DB('shop_modifications_feature', 'smf');
        $u->reConnection();  // перезагрузка запроса
        //DB::query('SET group_concat_max_len = 8096;'); // увеличить количество символов в характеристиках до N
        $u->select("CONCAT_WS('#', CONCAT_WS(':', sf.name, sf.measure), sf.type) AS `name`");
        $u->innerJoin('shop_price sp', 'sp.id=smf.id_price');
        $u->innerJoin('shop_feature sf',   'smf.id_feature = sf.id AND smf.id_modification IS NULL');
        $u->groupBy('sf.id');
        $u->orderBy('smf.sort', 0);
        if (!empty($idsGroups))
            $u->where('sp.id_group IN (?)', implode(",", $idsGroups));
        $features = $u->getList();
        unset($u); // удаление переменной
        return $features;
    }

    private function modsCols($idsGroups = array())
    {
        /**
         *  @@@@@@@@ @@@@@@ @@@@@     @@    @@@@@@ @@     @     @     | ПОЛУЧЕНИЕ
         *     @@    @@  @@ @@  @@   @@@@   @@  @@ @@     @     @     | особенностей товаров
         *     @@    @@  @@ @@@@@   @@  @@  @@@@@@ @@@@@@ @  @@@@@@@  | групп        товаров
         *     @@    @@  @@ @@  @@ @@@@@@@@ @@     @@  @@ @     @     | модификаций  товаров
         *     @@    @@@@@@ @@@@@  @@    @@ @@     @@@@@@ @     @     |
         */

        //        $u = new DB('shop_modifications', 'sm');
        //        $u->select('GROUP_CONCAT(DISTINCT smg.name, "#", sf.name SEPARATOR "##") AS `name`');
        //        $u->innerJoin('shop_modifications_group smg',   'smg.id = sm.id_mod_group');
        //        $u->innerJoin('shop_modifications_feature smf', 'sm.id = smf.id_modification');
        //        $u->innerJoin('shop_feature sf',                'sf.id = smf.id_feature');
        //        $tempModHeader = $u->getList();
        //        $tempModHeader = explode("##", $tempModHeader[0]['name']);



        $u = new DB('shop_feature', 'sf');
        $u->reConnection();  // перезагрузка запроса
        $u->select('sf.id Id, CONCAT_WS(\'#\', smg.name, sf.name) name');
        $u->innerJoin('shop_group_feature sgf', 'sgf.id_feature = sf.id');
        $u->innerJoin('shop_modifications_group smg', 'smg.id = sgf.id_group');
        $u->innerJoin('shop_modifications_feature smf', 'sf.id=smf.id_feature AND smf.id_modification>0');
        $u->innerJoin('shop_price sp', 'sp.id=smf.id_price');
        $u->groupBy('sgf.id');
        $u->orderBy('smg.name');
        if (!empty($idsGroups))
            $u->where('sp.id_group IN (?)', implode(",", $idsGroups));
        //$u->where();
        $modsCols = $u->getList();
        unset($u);
        return $modsCols;
    } // <особенности, группы, модификации> товаров

    private function groups()
    {
        /** Группы товаров
         * 1 запрос на получение данных
         * 2 обработка пути группы
         */


        /** 1 запрос на получение данных */
        $u = new DB('shop_group', 'sg');
        $u->reConnection();
        /** перезагрузка запроса */
        if (CORE_VERSION != "5.2") {
            $u->select('sg.id, sg.name endname, sgt.level,
                        GROUP_CONCAT(CONCAT_WS(\':\', sgtp.level, sgp.name) SEPARATOR \';\') name');
            $u->innerJoin("shop_group_tree sgt",  "sgt.id_child = sg.id AND sg.id <> sgt.id_parent
                                                   OR sgt.id_child = sg.id AND sgt.level = 0");
            $u->innerJoin("shop_group_tree sgtp", "sgtp.id_child = sgt.id_parent");
            $u->innerJoin("shop_group sgp",  "sgp.id = sgtp.id_child");
            $u->orderBy('sgt.level');
        } else {
            $u->select('sg.*');
            $u->orderBy('sg.id');
        }
        $u->groupBy('sg.id');
        $groups = $u->getList();
        unset($u);


        /** 2 обработка пути группы */
        foreach ($groups as $k => $i) {

            $path = '';
            $pathArray = array();
            $dataname = explode(";", $i['name']);

            foreach ($dataname as $k2 => $i2) {
                $ki = explode(":", $i2);
                $pathArray[$ki[0]] = $ki[1];
            }

            foreach (range(0, count($pathArray) - 1, 1) as $number)
                $path .= $pathArray[$number] . "/";

            /** подстановка окончания, а в родительской - удаление слеша */
            if ($i["level"] == 0)  $path  = substr($path, 0, -1);
            /** удаление последнего знака (в родительской группе) */
            else                   $path .= $i["endname"];

            unset($groups[$k]['level']);
            unset($groups[$k]['endname']);
            $groups[$k]['name'] = $path;
        }


        return $groups;
    } // Группы товаров

    /*private function features($idsProducts)
        $u = new DB('shop_modifications_feature', 'smf');
        $u->reConnection();  // перезагрузка запроса
        DB::query('SET group_concat_max_len = 8096;'); // увеличить количество символов в характеристиках до N
        $u->select("smf.id_price id, GROUP_CONCAT(
                CONCAT_WS('#', sf.name,
                    IF(
                        smf.id_value IS NOT NULL, sfvl.value, CONCAT(
                            IFNULL(smf.value_number, ''),
                            IFNULL(smf.value_bool, ''),
                            IFNULL(smf.value_string, '')
                        )
                    ),
                    sf.type
                ) SEPARATOR '|'
            ) features");
        $u->where('smf.id_price IN (?)', implode(",", $idsProducts));
        $u->innerJoin('shop_feature sf',   'smf.id_feature = sf.id AND smf.id_modification IS NULL');
        $u->leftJoin('shop_feature_value_list sfvl',      'smf.id_value = sfvl.id');
        $u->groupBy('smf.id_price');
        $features = $u->getList();
        unset($u); // удаление переменной
        return $features;
    }
	*/

    private function features($idsProducts)
    {
        /** ХАРАКТЕРИСТИКИ ТОВАРА (оптимизированный) */

        $u = new DB('shop_modifications_feature', 'smf');
        $u->reConnection();  // перезагрузка запроса
        DB::query('SET group_concat_max_len = 8096;'); // увеличить количество символов в характеристиках до N
        $u->select("smf.id_price id, GROUP_CONCAT(
                CONCAT_WS('#', CONCAT_WS(':', sf.name, sf.measure),
                    IF(
                        smf.id_value IS NOT NULL, sfvl.value, CONCAT(
                            IFNULL(smf.value_number, ''),
                            IFNULL(smf.value_bool, ''),
                            IFNULL(smf.value_string, '')
                        )
                    ),
                    sf.type
                ) SEPARATOR '||'
            ) features");
        $u->where('smf.id_price IN (?)', implode(",", $idsProducts));
        $u->innerJoin('shop_feature sf',   'smf.id_feature = sf.id AND smf.id_modification IS NULL');
        $u->leftJoin('shop_feature_value_list sfvl',      'smf.id_value = sfvl.id');
        $u->groupBy('smf.id_price');
        $features = $u->getList();
        unset($u); // удаление переменной
        return $features;
    } // характеристики товара

    private function modifications($idsProducts)
    {
        /**
         * МОДИФИКАЦИИ ТОВАРА
         *
         * расположение : каждая модификация должна записываться отдельной строкой
         * главная стр  : при наличии модификаций - данные с главной не должны записываться
         * ассоциации   : при импорте модификаций, соотноситься должны по ключевому полю с DB (берем за константу "URL товара")
         *
         * shop_modification_group shop_price  shop_modifications  shop_feature  shop_feature_value_list  shop_modifications_feature  shop_modifications_img
         *
         *
         *                                                             shop_price
         *                                                          <id_price - id>
         * shop_feature (в столбцы) <id_feature - id>          shop_modifications_feature  <id - id_modification>   shop_modifications_img
         *                                                       <id_modification - id>
         * shop_feature_value_list (значения) <id_value - id>      shop_modifications
         */

        $u = new DB('shop_modifications', 'sm');
        $u->reConnection();  // перезагрузка запроса
        // GROUP_CONCAT(CONCAT_WS('--', CONCAT_WS('#', smg.name, sf.name), sfvl.value) SEPARATOR '\n') `values`,
        $u->select('sm.id id, sm.id_mod_group idGroup, sm.id_price idProduct, sm.code article,
                sm.value price, sm.value_opt priceOpt, sm.value_opt_corp priceOptCorp,
                sm.count, smg.name nameGroup, smg.vtype typeGroup, sm.description metaDescription,
				GROUP_CONCAT(DISTINCT sf.name, "--", sfvl.value SEPARATOR "##") AS `values`,
				GROUP_CONCAT(DISTINCT si.Picture SEPARATOR ",") AS images');
        $u->where('sm.id_price IN (?)', implode(",", $idsProducts));
        $u->innerJoin('shop_modifications_group smg',   'smg.id = sm.id_mod_group');
        $u->innerJoin('shop_modifications_feature smf', 'sm.id = smf.id_modification');
        $u->innerJoin('shop_feature sf',                'sf.id = smf.id_feature');
        $u->innerJoin('shop_feature_value_list sfvl',   'smf.id_value = sfvl.id');
        $u->leftJoin('shop_modifications_img smi',      'smi.id_modification = sm.id');
        $u->leftJoin('shop_img si',                     'si.id = smi.id_img');
        $u->orderBy('sm.id_price');
        $u->groupBy('sm.id');
        $modifications = $u->getList();
        unset($u); // удаление переменной
        return $modifications;
    } // модификации товара

    private function customValues(
        $formData,
        $goodsL,
        $goodsIndex
    ) {
        /**
         *  @@@@@@ @@@@@@ @@     @@@@@@  | формирование, запись файла
         *  @@       @@   @@     @@      |
         *  @@@@@@   @@   @@     @@@@@@  |
         *  @@       @@   @@     @@      |
         *  @@     @@@@@@ @@@@@@ @@@@@@  |
         */

        if (count($formData) > 1) {
            $nameColumn = array();
            $is_features = false;
            foreach ($formData as $k => $v) {
                if ($v['checkbox'] == 'Y')
                    array_push($nameColumn, $this->fields[$k]);
            }
            if (!empty($goodsL))
                foreach ($goodsL[0] as $k => $v) {
                    if (substr($k, 0, 1) == '#')
                        array_push($nameColumn, $k);
                }

            $goodsLNew = array();
            foreach ($goodsL as $key => $value) {

                unset($value['idModification'], $value['idGroup']); // приходят из shopPrice запроса

                /** при неограниченном количестве - подставлять "текст при неограниченном" */
                if ($value['count'] < 0 or $value['count'] == '') {
                    $value['count'] = $value['presence'];
                    unset($value['presence']);
                }
                $unit    = array();
                foreach ($value as $k => $v) {
                    if (in_array($k, $nameColumn)) {
                        $unit[$k] = $v;
                    }
                }

                if (count($unit) > 1) array_push($goodsLNew, $unit);
            }
            $goodsL        = $goodsLNew;
            $headerCSV = array();
            foreach ($goodsL as $key => $value) {
                foreach ($value as $k => $v) {
                    if (($it = array_search($k, $this->fields)) !== false) {
                        $headerCSV[] = $formData[$it]['column'];
                    } else
                        $headerCSV[] = $k;
                }
                break;
            }
            return [$goodsL, $headerCSV];
        }
    } // замена значений на пользовательские

    private function columnLayout($column_number, $column, $last_column)
    {
        do {
            $column_name = (($t = floor($column_number / 26)) == 0 ? '' : chr(ord('A') + $t - 1)) .
                chr(ord('A') + floor($column_number % 26));
            array_push($column, "{$column_name}");
            $column_number++;
        } while ($column_number != $last_column);
        return $column;
    } // разметка по столбцам (координаты)

    private function recordHeaders($writer, $headerCSV, $line)
    {
        $writer[] = $headerCSV;
        $line++;
        return [$writer, $line];
    } // записываем заголовки

    private function recorRow($writer, $goodsL, $excludingKeys, $column, $line)
    {
        foreach ($goodsL as $row) {
            $out = [];
            if ($row['count'] == "-1" || (empty($row["count"]) && $row["count"] !== "0"))
                $row["count"] = (isset($row['presence'])) ? $row['presence'] : '';
            foreach ($row as $key => $r) {
                if (!in_array($key, $excludingKeys)) {
                    if ($key == "description" || $key == "fullDescription") {
                        $r = preg_replace('/\\\\+/', '', $r);
                        $r = preg_replace('/\r\n+/', '', $r);
                    }
                    $out[] = iconv('utf-8', 'utf-8', $r); // CP1251
                }
            }
            // записываем данные по товарам
            $writer[] = $out;
            $line++;
        }
        return [$writer, $line];
    } // вывод товаров без модификаций

    private function mergerWithFeatures($goodsL, $features, $fields = array())
    {
        /** Добавляем Характеристики в массив (оптимизированный)
         *
         * 1 получаем именной массив id характеристик
         * 2 добавляем характеристики в массив товаров
         *
         * @param array $goodsL массив товаров (до добавления характеристик)
         * @param array $features массив данных по характеристикам (для подстановки)
         * @return array $tempGoodsL массив товаров и характеристик
         */

        $tempGoodsL = array();

        /** 1 */
        $idsFeatsName = array();
        foreach ($features as $k => $v) {
            $idsFeatsName[$v['id']] = $v;
            unset($features[$k]);
        }
        unset($features);

        foreach ($goodsL as $k => $v) {
            foreach ($fields as $f) {
                $v = array_merge($v, array(strval('#' . $f['name']) => ''));
            }
            if (!empty($idsFeatsName[$v['id']])) {
                $feats = explode('||', $idsFeatsName[$v['id']]['features']);
                // Список характеристик товара
                foreach ($feats as $feat) {
                    $har = explode('#', $feat);
                    $name = '#' . $har[0] . '#' . $har[2];
                    $v[$name] = $har[1];
                    //array_merge($v, array($name => $har[1]));
                }
            }
            array_push($tempGoodsL, $v);
            unset($idsFeatsName[$v['id']]);
            unset($goodsL[$k]);
        }

        /** 2 */
        /*
        foreach ($goodsL as $k => $v) {
            if ($idsFeatsName[$v['id']]) $newItem = array_merge($v, $idsFeatsName[$v['id']]);
            else $newItem = $v;
            array_push($tempGoodsL, $newItem);
            unset($idsFeatsName[$v['id']]); unset($goodsL[$k]);
        }
*/
        unset($goodsL);
        return $tempGoodsL;
    } // добавляем характеристики в массив


    private function mergerWithMoffification($goodsL, $modifications)
    {
        /** Добавляем Модификации в массив (оптимизированный)
         *
         * 1 получаем именной массив id модификаций
         * 2 получаем именной массив товаров / получаем список товаров без модификаций
         * 3 дополнение модификаций параметрами из товаров
         * 4 слияние готовых списков модификаций и товаровБезМодификаций
         *
         * @param array $goodsL массив товаров (до добавления модификаций)
         * @param array $modifications массив данных по модификациям (для подстановки)
         * @return array $tempGoodsL массив товаров и модификаций
         */

        $tempGoodsL = array();

        /** 1 */
        $idsModsName = array();
        foreach ($modifications as $i) {
            $idsModsName[$i['idProduct']] = true;
        }

        /** 2 */
        $goodsLName = array();
        $goodsWithoutModification = array();
        foreach ($goodsL as $i) {
            if (array_key_exists($i['id'], $idsModsName)) {
                $goodsLName[$i['id']] = $i;
                unset($idsModsName[$i['id']]);
            } else {
                array_push($goodsWithoutModification, $i);
                unset($idsModsName[$i['id']]);
            }
        }
        unset($goodsL, $idsModsName);

        /** 3 */
        foreach ($modifications as $item) {
            $product = $goodsLName[$item['idProduct']];
            /** $item['typeGroup'] : 0 - добавляет, 1 - умножает цену, 2 - заменяет */
            switch ($item['typeGroup']) {
                case 0:
                    $item['price']        = $item['price'] + $product['price'];
                    $item['priceOpt']     = $item['priceOpt'] + $product['priceOpt'];
                    $item['priceOptCorp'] = $item['priceOptCorp'] + $product['priceOptCorp'];
                    break;
                case 1:
                    $item['price']        = $item['price'] * $product['price'];
                    $item['priceOpt']     = $item['priceOpt'] * $product['priceOpt'];
                    $item['priceOptCorp'] = $item['priceOptCorp'] * $product['priceOptCorp'];
                    break;
                case 2:
                    break;
                default:
                    break;
            }
            unset($item['id']);

            $modFeature = explode("##", $item['values']);
            foreach ($modFeature as $kMF => $vMF) {
                $feat = explode("--", $vMF);
                $item[$item['nameGroup'] . '#' . $feat[0]] = $feat[1];
            }
            unset($item['nameGroup'], $item['values'], $item['idProduct'], $item['typeGroup']);

            $newItem = array_merge($product, $item);
            array_push($tempGoodsL, $newItem);

            unset($product);
        }
        unset($goodsLName, $modifications);

        /** 4 */
        $tempGoodsL = array_merge($goodsWithoutModification, $tempGoodsL);
        unset($goodsWithoutModification);

        return $tempGoodsL;
    } // добавляем модификации в массив

    private function assembly($pages, $filePath)
    {
        /**
         * @@@@@@@@ @@@@@@ @@     @@ @@@@@@ | методы по работе с временными файлами
         *    @@    @@     @@@   @@@ @@  @@ |
         *    @@    @@@@@@ @@ @@@ @@ @@@@@@ |
         *    @@    @@     @@  @  @@ @@     |
         *    @@    @@@@@@ @@     @@ @@     |
         */


        /**
         * читает объекты из файлов "goodsL1.TMP" (число - номер цикла) в директории "files/tempfiles/",
         * кол-во циклов опредеялется общим колвом циклов экспорта;
         * полученные объекты отправляет в Spout на запись файла
         */
        if (!empty($pages)) {
            /**
             * библиотека - Spout
             * https://github.com/box/spout
             *
             * $writer->addRow(['dsfsd','fgdgdf','sdfert']);              добавить строку за раз
             * $writer->addRows([['dsfsd','sdfert'],['fdgfdg','sdfd']]);  добавлять несколько строк за раз
             * $writer->openToBrowser($fileName);                         передавать данные непосредственно в браузер
             */

            $writer = WriterFactory::create(Type::XLSX);
            $writer->setTempFolder($temporaryFilePath);                   // директория хранения временных файлов
            $writer->openToFile($filePath);                               // директория сохраниния XLSX

            for ($cycleNum = 0; $cycleNum < $pages; ++$cycleNum) {
                $goodsL = $this->readTempFiles($cycleNum);
                $writer->addRows($goodsL);
            }
            unset($mainRequest);

            /**
             * сохраняем файл
             * закрываем объект записи
             */
            $writer->close();
            unset($writer);
            unset($mainRequest);
            unset($objWriter);
        }
    } // сборка файла из временных


}
