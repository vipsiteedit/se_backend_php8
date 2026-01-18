<?php
date_default_timezone_set('Europe/Moscow');

function convert_curr($name)
{
    return str_replace(array('KAT', 'BER', 'RUR'), array('KZT', 'BYR', 'RUB'), $name);
}

function shoppage($folder)
{
    //check business//
    if (!file_exists('system/business')) {
        return "Not Business";
    }
    //check pages//
    $pages = simplexml_load_file('projects/' . $folder . 'pages.xml');
    foreach ($pages->page as $page) {
        $pagecontent = simplexml_load_file('projects/' . $folder . 'pages/' . $page["name"] . ".xml");
        foreach ($pagecontent->sections as $section) {
            if (strpos($section->type, "mshop_vitrine") !== false) {
                return array('page' => $page["name"], 'id' => $section->id);
            }
        }
    }
}

function isUpdateFile($filename)
{
    if (file_exists($filename)) {
        $date = date('Y-m-d H:i:s', filemtime($filename));
        $q = se_db_query("SELECT count(*) FROM shop_price WHERE updated_at>'{$date}'");
        if (!empty($q) && $line = se_db_fetch_row($q)) {
            return ($line[0] > 0);
        } else
            return false;
    } else {
        return true;
    }
}

function replace($text)
{
    $search = array("&nbsp;", "&", "\"", ">", "<", "'");
    $replace = array(" ", "&amp;", "&quot;", "&gt;", "&lt;", "&apos;");
    $text = str_replace($search, $replace, $text);
    return $text;
}

function se_googlemarket()
{
    if (!SE_DB_ENABLE) return;
    $filename = 'google.xml';
    if (isUpdateFile($filename)) {
        if (file_exists('sitelang.dat'))
            $thisprj = trim(join('', file('sitelang.dat')));
        if (!empty($thisprj)) $thisprj .= '/';

        //se_db_connect();
        $link = se_db_query("SELECT * FROM `main` WHERE `lang`='rus'");
        if (!empty($link))
            $line = se_db_fetch_assoc($link);
        $basecurr = $line['basecurr'];
        $main_id = $line['id'];
        $is_store = $line['is_store'];
        $is_pickup = $line['is_pickup'];
        $is_delivery = $line['is_delivery'];
        $local_delivery_cost  = $line['local_delivery_cost'];
        if (!$is_store && !$is_pickup) {
            $is_delivery = true;
        }

        if (empty($line['domain'])) {
            $shopurl = "http://" . iconv('UTF-8', 'CP1251', $_SERVER["HTTP_HOST"]);
        } else {
            $shopurl = "http://" . iconv('UTF-8', 'CP1251', $line['domain']);
            $hosts = file('hostname.dat');
            foreach ($hosts as $host) {
                list($dom, $prj) = explode("\t", $host);
                if (str_replace("www.", "", $line['domain']) == str_replace("www.", "", $dom)) {
                    $thisprj = trim($prj) . '/';
                }
            }
        }

        $page = shoppage($thisprj);
        if ($page['page'] == "Not Business") return;


        //$compname = simplexml_load_file('projects/' . $thisprj . 'project.xml');
        //$name = iconv('UTF-8', 'CP1251', $compname->vars->sitesmallcompany);

        $name = replace($line['shopname']);
        if (!$name) $name = $shopurl;
        $company = $line['company'];
        $catpage = $page['page'];

        //$link = se_db_query("SELECT * FROM `shop_price` WHERE id_main={$main_id}");
        //if (!empty($link))
        //        while($line = se_db_fetch_assoc($link)){
        //    	    echo print_r($line),"<br>";
        //        }
        $text = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
        $text .= "<rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">\n";
        $text .= "\t<channel>\n";
        $text .= "\t\t<title>{$name}</title>\n";
        $text .= "\t\t<description>{$company}</description>\n";
        $text .= "\t\t<url>{$shopurl}</url>\n";

        $link = se_db_query("SELECT `id`, `upid`, `name` FROM `shop_group`");
        $grouplist = array();
        if (!empty($link))
            while ($line = se_db_fetch_assoc($link)) {
                if (empty($line['upid']))
                    $grouplist['g_0'] = array('g_' . $line['id'] => $line['name']);
                else
                    $grouplist['g_' . $line['upid']] = array('g_' . $line['id'] => $line['name']);
            }
        $link = se_db_query("SELECT (SELECT newproc FROM `shop_special` WHERE expires_date<CURDATE() AND (id_group=sp.id_group OR id_price=sp.id) AND sp.special_price='Y' AND sg.special_price='Y') AS spec,sp.id, sp.code, sp.price, sp.curr, sp.id_group, sp.name, sp.note, sp.img,.
        sp.manufacturer, sp.enabled, sp.presence_count, sg.lang
        FROM `shop_price` `sp` 
        INNER JOIN shop_group sg ON (sg.id=sp.id_group)
        WHERE sp.`enabled` = 'Y' AND sg.`active` = 'Y' AND sp.`price`>0 AND sp.`name`<>'' AND sg.lang='rus'"); //sp.is_market=1 AND 
        //echo mysql_error().'111';
        if (!empty($link))
            while ($line = se_db_fetch_assoc($link)) {
                if (empty($line['lang'])) $line['lang'] = 'rus';
                $proc = floatval($line['spec']);
                if ($line["enabled"] == "Y") {
                    $groups = array('test', 'new'); // Список дерева групп
                    $available = ($line["presence_count"] > 0 || $line["presence_count"] == '') ? "true" : "false";
                    $price = number_format($line['price'] * ((100 - $proc) / 100), 2, '.', '');
                    $text .= "\t\t<item>\n";
                    $text .= "\t\t\t<g:id>{$line['id']}</g:id>\n";
                    $text .= "\t\t\t<g:title>" . replace($line['name']) . "</g:title>\n";
                    if (!empty($line["note"]))
                        $text .= "\t\t\t<g:description>" . replace($line['note']) . "</g:description>\n";
                    $text .= "\t\t\t<g:link>{$shopurl}/{$catpage}/show/{$line['code']}/</g:link>\n";
                    if (!empty($line["img"]))
                        $text .= "\t\t\t<g:image_link>{$shopurl}/images/{$line['lang']}/shopprice/{$line['img']}</g:image_link>\n";
                    $text .= "\t\t\t<g:condition>new</g:condition>\n";
                    $text .= "\t\t\t<g:availability>in stock</g:availability>\n";
                    $text .= "\t\t\t<g:price>{$price} " . convert_curr($line['curr']) . "</g:price>\n";
                    // доставка
                    //$text .= "\t\t\t<g:shipping>\n";
                    //$text .= "\t\t\t</g:shipping>\n";
                    $text .= "\t\t\t<g:google_product_category>" . htmlspecialchars(join(' > ',  $groups)) . "</g:google_product_category>\n";
                    $text .= "\t\t\t<g:product_type>" . $groups[0] . "</g:product_type>\n";

                    //                if (!empty($line["manufacturer"]))
                    //                   $text .= "\t\t\t\t<vendor>" . replace(iconv('UTF-8', 'CP1251', $line["manufacturer"])). "</vendor>\n";
                    $text .= "\t\t</item>\n";
                }
            }
        $text .= "\t</channel>\n";
        $text .= "</rss>";

        $out = fopen($filename, 'w');
        fwrite($out,  $text);
        fclose($out);
    } else {
        $text = join('', file($filename));
    }
    return $text;
}
