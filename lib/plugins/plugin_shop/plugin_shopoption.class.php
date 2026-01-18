<?php
class plugin_shopoption
{
    private static $instance = null;

    private $cache_dir = '';
    private $option = array();
    private $count = 0;
    private $cache_option;
    private $cache_count;

    public function __construct()
    {
        $this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/option/';
        $this->cache_option = $this->cache_dir . 'option.json';
        $this->cache_count = $this->cache_dir . 'count.txt';
        //$this->id_main = 1;

        if (!is_dir($this->cache_dir)) {
            if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/'))
                mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/');
            if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/'))
                mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/');
            mkdir($this->cache_dir);
        }

        $this->checkCache();

        //print_r($this->option);
    }

    private function checkCache()
    {
        $tables = array(
            'shop_option',
            'shop_option_group',
            'shop_option_value',
        );

        foreach ($tables as $key => $val) {
            $alias = 't' . $key;
            $table = $val . ' AS ' . $alias;
            $tables[$key] = 'SELECT COUNT(*) AS count, UNIX_TIMESTAMP(GREATEST(MAX(' . $alias . '.updated_at), MAX(' . $alias . '.created_at))) AS time FROM ' . $table;
        }

        $query = join(' UNION ALL ', $tables) . ' ORDER BY `time` DESC LIMIT 1';

        $result = se_db_query($query);

        list($this->count, $time) = se_db_fetch_row($result);

        $cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;

        $time = max(filemtime(__FILE__), $time);

        if (!file_exists($this->cache_option) || filemtime($this->cache_option) < $time || $cache_count != $this->count) {
            $this->fetchOptions();
            $this->saveCache();
        } else {
            $this->option = json_decode(file_get_contents($this->cache_option), 1);
        }
    }

    private function fetchOptions()
    {
        $this->option = array(
            'groups' => array(),
            'values' => array(),
        );

        $t = new seTable('shop_option_group');
        $t->select('id, name, description');
        $t->orderBy('sort');
        $list = $t->getList();

        foreach ($list as $val) {
            $val['options'] = array();
            $this->option['groups'][$val['id']] = $val;
        }

        $t = new seTable('shop_option');
        $t->select('id, id_group, code, name, description, image, type, type_price, is_counted');
        $t->orderBy('sort');
        $list = $t->getList();

        foreach ($list as $val) {
            $val['image'] = $this->getImage($val['image']);
            $val['values'] = array();

            if ($val['type'] == 0)
                $val['type'] = 'radio';
            elseif ($val['type'] == 1)
                $val['type'] = 'select';
            else
                $val['type'] = 'checkbox';

            $this->option[$val['id']] = $val;
            $this->option['groups'][(int)$val['id_group']]['options'][] = $val['id'];
        }

        $t = new seTable('shop_option_value');
        $t->select('id, id_option, name, description, price, image');
        $t->orderBy('sort');
        $list = $t->getList();

        foreach ($list as $val) {
            $val['image'] = $this->getImage($val['image']);
            $this->option['values'][$val['id']] = $val;
            $this->option[(int)$val['id_option']]['values'][] = $val['id'];
        }
    }

    private function getImage($image = '')
    {
        if (empty($image))
            return;

        $image_dir = '/images/rus/shopfeature/';

        $image = $image_dir . $image;

        if (!file_exists(SE_ROOT . $image))
            $image = '';

        return $image;
    }

    private function saveCache()
    {
        $file = fopen($this->cache_option, "w+");
        $result = fwrite($file, json_encode($this->option));
        fclose($file);

        $file = fopen($this->cache_count, "w+");
        $result = fwrite($file, $this->count);
        fclose($file);
    }

    public function getGroup($id_group)
    {
        if (isset($this->option['groups'][$id_group]))
            $group = $this->option['groups'][$id_group];

        return $group;
    }

    public function getOption($id_option)
    {
        if (isset($this->option[$id_option]))
            $option = $this->option[$id_option];

        return $option;
    }

    public function getValue($id_value)
    {
        if (isset($this->option['values'][$id_value]))
            $value = $this->option['values'][$id_value];

        return $value;
    }

    public function getProductOptions($id_product = 0)
    {
        $options = array();
        $modifications = !empty($_SESSION['modifications'][$id_product]) ? $_SESSION['modifications'][$id_product] : array();

        $spo = new seTable('shop_product_option', 'spo');
        $spo->select('spo.id_option_value, spo.price, sov.id_option, spo.is_default, so.id_group');
        $spo->innerJoin('shop_option_value sov', 'sov.id=spo.id_option_value');
        $spo->innerJoin('shop_option so', 'so.id=sov.id_option');
        $spo->leftJoin('shop_option_group sog', 'sog.id=so.id_group');
        $spo->where('spo.id_product=?', $id_product);
        if (!empty($modifications))
            $spo->andWhere('(spo.id_modification IS NULL OR spo.id_modification IN (?))', join(',', $modifications));
        $spo->orderBy('sog.sort IS NULL');
        $spo->addOrderBy('sog.sort');
        $spo->addOrderBy('so.sort');
        $spo->addOrderBy('spo.sort');
        $spo->addOrderBy('sov.sort');

        $list = $spo->getList();

        if (!empty($list)) {
            foreach ($list as $val) {
                $id_group = (int)$val['id_group'];
                $id_option = (int)$val['id_option'];
                $id_value = (int)$val['id_option_value'];

                if (!isset($options[$id_group]['options'][$id_option])) {
                    if (!isset($options[$id_group])) {
                        $options[$id_group] = array(
                            'options' => array(),
                        );
                        if ($group = $this->getGroup($id_group)) {
                            $options[$id_group] = array(
                                'id' => $group['id'],
                                'name' => $group['name'],
                                'description' => $group['description'],
                                'options' => array(),
                            );
                        }
                    }
                    $option = $this->getOption($id_option);
                    $options[$id_group]['options'][$id_option] = array(
                        'id' => $id_option,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'type_price' => $option['type_price'],
                        'description' => $option['description'],
                        'image' => $option['image'],
                        'is_counted' => $option['is_counted'],
                        'values' => array(),
                    );
                }

                $value = $this->getValue($id_value);

                $type_price = $options[$id_group]['options'][$id_option]['type_price'];

                $val['price'] = round($val['price']);

                $options[$id_group]['options'][$id_option]['values'][$id_value] = array(
                    'id' => $id_value,
                    'id_group' => $id_group,
                    'name' => $value['name'],
                    'price' => $val['price'] > 0 ? ($type_price == 0 ? se_formatMoney($val['price'], se_getMoney()) : ($val['price'] . '<span class="fMoneyFlang">%</span>')) : 0,
                    'price_value' => $type_price == 0 ? $val['price'] : $val['price'] / 100,
                    'image' => $value['image'],
                    'description' => $value['description'],
                    'is_counted' => $options[$id_group]['options'][$id_option]['is_counted'],
                    'selected' => !empty($val['is_default']),
                );
            }
        }

        return $options;
    }

    public function getSelected($id_product = 0)
    {
        $modifications = !empty($_SESSION['modifications'][$id_product]) ? $_SESSION['modifications'][$id_product] : array();

        $spo = new seTable('shop_product_option', 'spo');
        $spo->select('spo.id_option_value, spo.price, sov.id_option, spo.is_default, so.id_group');
        $spo->innerJoin('shop_option_value sov', 'sov.id=spo.id_option_value');
        $spo->innerJoin('shop_option so', 'so.id=sov.id_option');
        $spo->leftJoin('shop_option_group sog', 'sog.id=so.id_group');
        $spo->where('spo.id_product=?', $id_product);
        if (!empty($modifications))
            $spo->andWhere('(spo.id_modification IS NULL OR spo.id_modification IN (?))', join(',', $modifications));
        $spo->orderBy('sog.sort IS NULL');
        $spo->addOrderBy('sog.sort');
        $spo->addOrderBy('so.sort');
        $spo->addOrderBy('spo.sort');
        $spo->addOrderBy('sov.sort');
        $list = $spo->getList();
        $options = array();

        foreach ($list as $o) {
            if (isset($_REQUEST['option'][$o['id_option']]) || $o['is_default']) {
                if (!isset($options[$o['id_option']])) {
                    $options[$o['id_option']] = $this->getOption($o['id_option']);
                }

                if ($options[$o['id_option']]['type'] == 'checkbox') {
                    $values = $_REQUEST['option'][$o['id_option']];
                    foreach ($values as $v) {
                        if ($v == $o['id_option_value']) {
                            $value = $this->getValue($v);
                            $value['price'] = $o['price'];
                            $value['count'] = $options[$o['id_option']]['is_counted'] ? max(1, (int)$_REQUEST['ocount'][$v]) : 1;
                            $options[$o['id_option']]['selected'][$v] = $value;
                        }
                    }
                } else {
                    if ($o['is_default'] && empty($options[$o['id_option']]['selected'])) {
                        $value = $this->getValue($o['id_option_value']);
                        $value['price'] = $o['price'];
                        $value['count'] = 1;
                        $options[$o['id_option']]['selected'][0] = $value;
                    }

                    if (isset($_REQUEST['option'][$o['id_option']])) {
                        $values = (int)$_REQUEST['option'][$o['id_option']];

                        if ($values == $o['id_option_value']) {
                            $value = $this->getValue($values);
                            $value['price'] = $o['price'];
                            $value['count'] = $options[$o['id_option']]['is_counted'] ? max(1, (int)$_REQUEST['ocount'][$values]) : 1;
                            $options[$o['id_option']]['selected'][0] = $value;
                        }
                    }
                }
            }
        }

        return $options;
    }

    public function getListTable($id_product = 0)
    {
        $result = array();

        $options = $this->getSelected($id_product);

        if (!empty($options) || true) {
            $selected = !empty($_SESSION['modifications'][$id_product]) ? $_SESSION['modifications'][$id_product] : '';
            $p = new plugin_shopamount($id_product, false, 0, 1, $selected);

            $amount = $product_price = $p->getPrice();

            $percent = array();

            $t = new seTable('shop_price');
            $t->find($id_product);

            $result[0] = array(
                'name' => $t->name,
                'count' => '',
                'realprice' => $product_price,
                'price' => se_formatMoney($product_price, se_getMoney()),
            );
            $i = 0;
            $group_name = '';
            foreach ($options as $option) {
                if ($option['id_group'] && !$group_name) {
                    $group_name = $this->option['groups'][$option['id_group']]['name'];
                }
                foreach ($option['selected'] as $v) {
                    $i++;
                    //if ($option['type']=='checkbox')
                    //    $name = $v['name'];
                    //else
                    $name = $option['name'] . ': ' . $v['name'];
                    $count = '';
                    if ($option['is_counted'])
                        $count = $v['count'] . ' шт';

                    $price = $v['price'] * $v['count'];

                    if ($option['type_price'] == '1') {
                        $percent[$i] = $price / 100;
                    } else {
                        $amount += round($price);
                    }

                    $fprice = se_formatMoney($price, se_getMoney());

                    $result[$i] = array(
                        'name' => $name,
                        'count' => $count,
                        'realprice' => $price,
                        'price' => $fprice,
                    );
                }
            }

            $option_amount = $amount;

            foreach ($percent as $key => $val) {
                $amount += round($option_amount  * $val);
                $result[$key]['price'] = se_formatMoney($option_amount * $val, se_getMoney());
            }
            $result[] = array(
                'name' => 'Итого:',
                'count' => '',
                'realprice' => $amount,
                'price' => se_formatMoney($amount, se_getMoney()),
            );
        }

        return array($result, $group_name);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
