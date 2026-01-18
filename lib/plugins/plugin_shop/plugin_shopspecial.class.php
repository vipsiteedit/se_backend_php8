<?php

class plugin_shopspecial
{
    private $selection = 'all';
    private $modification_mode;

    public function __construct($selection)
    {
        if (!empty($selection))
            $this->selection = $selection;
        $this->modification_mode = plugin_shopsettings::getInstance()->getValue('modification_mode');
    }

    public function getSpecial($options)
    {
        $select_list = 'DISTINCT sp.id, sp.code, sp.id_group, sp.flag_hit, sp.flag_new, 
		(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications,
		concat_ws("/",sg.code_gr, sp.code) AS url';

        if (!empty($options['field_price'])) {
            $select_list .= ', sp.presence_count, sp.price, sp.price_opt, sp.price_opt_corp, sp.curr, sp.discount, sp.step_count, sp.max_discount';
        }
        if (!empty($options['field_name'])) {
            $select_list .= ', sp.name';
        }
        if (!empty($options['field_article'])) {
            $select_list .= ', sp.article';
        }
        if (!empty($options['field_note'])) {
            $select_list .= ', sp.note';
        }
        if (!empty($options['field_image'])) {
            $select_list .= ', (SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img';
        }
        if (!empty($options['field_rating'])) {
            $select_list .= ', (SELECT AVG(sr.mark) FROM shop_reviews sr WHERE sr.id_price=sp.id) AS rating';
        }
        if (!empty($options['field_group_name'])) {
            $select_list .= ', (SELECT name FROM shop_group WHERE id=sp.id_group) AS group_name, (SELECT code_gr FROM shop_group WHERE id=sp.id_group) AS code_gr';
        }

        $shop_price = new seTable('shop_price', 'sp');
        $shop_price->select($select_list);
        $shop_price->where('sp.enabled="Y"');
        $shop_price->innerJoin('shop_group sg', 'sp.id_group = sg.id');
        //$shop_price->andWhere('sp.lang="?"', se_getLang());
        $id_list = false;
        $day_week = date('w');

        if ($day_week == 0)
            $day_week = 7;

        //upd discount

        switch ($this->selection) {
            case 'all':
                break;
            case 'special':
                //$shop_price->innerJoin('shop_leader sl', 'sp.id = sl.id_price');
                $shop_price->andWhere('sp.special_offer ="?"', 'Y');
                break;
            case 'hit':
                $shop_price->andWhere('sp.flag_hit ="?"', 'Y');
                break;
            case 'new':
                $shop_price->andWhere('sp.flag_new ="?"', 'Y');
                break;
            case 'discount':
                $shop_price->innerJoin('shop_discount_links sdl', 'sp.id = sdl.id_price OR sp.id_group = sdl.id_group');
                $shop_price->innerJoin('shop_discounts sd', 'sdl.discount_id=sd.id');
                $shop_price->andWhere('sp.discount="Y"');
                //$shop_price->andWhere("((sd.date_from <= '?' OR sd.date_from IS NULL) AND (sd.date_to >= '?' OR sd.date_to IS NULL))", date('Y-m-d H:i:s'));
                $shop_price->andWhere("((sd.date_from <= '?' OR sd.date_from IS NULL OR sd.date_from=0) AND (sd.date_to >= '?' OR sd.date_to IS NULL OR sd.date_to=0))", date('Y-m-d H:i:s'));
                $shop_price->andWhere('(SUBSTRING(sd.week, ?, 1) > 0 OR sd.week IS NULL)', $day_week);
                break;
            case 'oldOrders':
                $id_list = true;
                $shop_price->select('sp.id');
                $shop_price->orderBy('(SELECT so.updated_at FROM shop_tovarorder st INNER JOIN shop_order so ON (so.id = st.id_order) WHERE st.id_price = sp.id AND so.status <> "N" ORDER BY st.created_at DESC LIMIT 1)', 1);
                break;
            case 'topOrders':
                $id_list = true;
                $shop_price->select('sp.id');
                $shop_price->innerJoin('(SELECT st.id_price, COUNT(*) AS cnt FROM shop_tovarorder st INNER JOIN shop_order so ON so.id = st.id_order WHERE so.status <> \'N\' GROUP BY st.id_price ) counts', 'counts.id_price = sp.id');
                $shop_price->orderBy('COALESCE(counts.cnt, 0)', 1);

                //$shop_price->orderBy('(SELECT count(*) FROM shop_tovarorder st INNER JOIN shop_order so ON (so.id = st.id_order) WHERE st.id_price = sp.id AND so.status <> "N" GROUP BY st.id_price LIMIT 1)', 1);
                break;
            case 'topVotes':
                $id_list = true;
                $shop_price->select('sp.id');
                $shop_price->orderBy('sp.votes', 1);
                break;
            case 'topLooks':
                $id_list = true;
                $shop_price->select('sp.id');
                $shop_price->orderBy('sp.vizits', 1);
                break;
            case 'nowLooks':
                $id_list = true;
                $shop_price->select('sp.id');
                $shop_price->leftjoin('shop_nowlooks sn', 'sp.id=sn.id');
                $shop_price->orderBy('sn.count_looks', 1);
                $shop_price->addorderBy('sn.time_expire', 1);
                break;
            case 'userLooks':
                $id_goods = false;
                if (isset($_COOKIE['look_goods'])) {
                    foreach ($_COOKIE['look_goods'] as $key => $val) {
                        $arr = explode('#', $val);
                        $list[$key] = (int)$arr[0] + (int)$arr[1] * 60;
                    }
                    arsort($list);
                    $id_goods = join(', ', array_keys($list));
                    unset($list, $arr);
                }
                $shop_price->where("sp.id IN ($id_goods)");
                break;
        }

        $sort_field = $options['sort'][0];
        $sort_dir = $options['sort'][1] == 'desc';

        if (!$id_list) {
            $shop_price->orderBy($sort_field, $sort_dir);
        }

        if ($options['group_type'] != 'all') {
            $shop_price->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');

            $plugin_group = plugin_shopgroups::getInstance();
            $group_id = $plugin_group->getGroupId($options['start_group']);

            if ($group_id) {
                if ($options['group_type'] == 'Y') {
                    $shop_price->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    $shop_price->andWhere('sgt.id_parent=?', $group_id);
                } else {
                    $shop_price->andWhere('spg.id_group=?', $group_id);
                }
            } elseif (!empty($options['start_group'])) {
                return;
            }
        }

        $pricelist = $shop_price->getList(0, $options['count']);
        echo se_db_error();
        //echo $shop_price->getSql();

        if ($id_list && !empty($pricelist)) {
            $in_id = array();
            foreach ($pricelist as $val) {
                $in_id[] = $val['id'];
            }
            if (!empty($in_id)) {
                $shop_price->select($select_list);
                $shop_price->where('sp.id IN (?)', join(',', $in_id));
                $shop_price->orderBy($sort_field, $sort_dir);
                $pricelist = $shop_price->getList();
            }
        }
        return $this->eachSpecial($pricelist, $options);
    }

    private function eachSpecial($list, $options)
    {
        if (!empty($list)) {
            $plugin_image = new plugin_shopimages();
            //$img_style = $plugin_image->getSizeStyle($options['image_size']);
            foreach ($list as $key => $line) {
                if (!empty($options['field_rating'])) {
                    $line['ratio'] = round(100 / 5 * $line['rating']);
                    $line['rating'] = round($line['rating'], 2);
                }

                if (!empty($options['field_image'])) {
                    $line['image_prev'] = $plugin_image->getPictFromImage($line['img'], $options['image_size'], 's');
                }

                if ($line['modifications']) {
                    $plugin_modifications = new plugin_shopmodifications($line['id'], true);
                    $plugin_modifications->getModifications(true);
                }

                $selected = !empty($_SESSION['modifications'][$line['id']]) ? $_SESSION['modifications'][$line['id']] : '';

                $plugin_amount = new plugin_shopamount(0, $line, null, 1, $selected);

                $line['count'] = (int)$plugin_amount->getPresenceCount();

                if (!empty($options['field_price'])) {
                    $line['new_price'] = $plugin_amount->showPrice(true, $options['round'], ' ');

                    $discount = $plugin_amount->getDiscount();

                    if ($discount > 0) {
                        $line['old_price'] = $plugin_amount->showPrice(false, $options['round'], ' ');
                        $line['percent'] = 0 - $plugin_amount->getDiscountProc();
                    }
                }

                $line['name'] = htmlspecialchars($line['name']);

                if (!empty($options['field_group_name']))
                    $line['view_group'] = seMultiDir() . '/' . $options['page_shop'] . '/' . urlencode($line['code_gr']) . SE_END;

                $line['view_goods'] = seMultiDir() . '/' . $options['page_shop'] . '/' . $line['url'] . SE_END;

                $line['price_label'] = $this->getPriceLabel($line['id'], $options['price_label']);

                $list[$key] = $line;
            }
        }
        return $list;
    }

    public function getPriceLabel($id_price, $default = 'Цена:')
    {
        $label = $default;
        if ($this->modification_mode == 'placeholder' && empty($_SESSION['modifications'][$id_price])) {
            $label = 'Цена за кв.м.:';
        }
        return $label;
    }
}
