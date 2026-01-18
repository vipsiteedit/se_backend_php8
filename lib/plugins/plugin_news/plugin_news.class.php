<?php

/** -------------------------------------------------------------- //
 * Плагин новостей и публикаций
 * @param string $opt -    Массив параметров
 **/
class plugin_news
{

    private $newskod = array();
    private $cache_dir;
    private $cache_sections;
    private static $instance = null;
    private static $sections = array();
    private static $groups = array();
    private $lang = 'rus';
    private static $options = array();
    private $count = 0;
    private $id_gcontact = 0;
    private $current_page;
    //private $opts = array();
    private $cache_count;
    private $cache_group;


    public function __construct($opt = array(), $pagename = false)
    {
        $this->current_page = ($pagename) ? $pagename : seData::getInstance()->getPageName();
        //$this->opts = $opt;

        $this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/news/' . $opt['lang'] . '/';
        $this->cache_sections = $this->cache_dir . 'news.json';
        $this->cache_count = $this->cache_dir . 'count.json';
        $this->cache_group = $this->cache_dir . 'groups.json';
        if (!is_dir($this->cache_dir)) {
            if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/'))
                mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/');
            if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/news/'))
                mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/news/');
            mkdir($this->cache_dir);
        }

        $this->UpdateDB();

        $this->checkCache();
        $this->id_gcontact = intval($_SESSION['user_region']['id_city']);
        if ($this->id_gcontact > 0) {
            $gc = new seTable('shop_contacts_geo');
            $gc->select('id_contact');
            $gc->where('id_city = ?', $this->id_gcontact);
            $gc->fetchOne();
            $this->id_gcontact = $gc->id_contact;
        }
    }

    public function UpdateDB()
    {
        if (!file_exists(SE_ROOT . '/system/logs/news_public.upd')) {
            se_db_query("CREATE TABLE IF NOT EXISTS `news_gcontacts` (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `id_news` int(10) UNSIGNED NOT NULL,
              `id_gcontact` int(10) UNSIGNED NOT NULL,
              `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `id_news` (`id_news`),
              KEY `id_gcontact` (`id_gcontact`),
              CONSTRAINT `news_gcontact_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `news_gcontact_ibfk_2` FOREIGN KEY (`id_gcontact`) REFERENCES `shop_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            $sql = "CREATE TABLE IF NOT EXISTS `news_userfields` (
                  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `id_news` int(10) UNSIGNED NOT NULL,
                  `id_userfield` int(10) UNSIGNED NOT NULL,
                  `value` text,
                  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `FK_person_userfields_se_user_id` (`id_news`),
                  KEY `FK_person_userfields_shop_userfields_id` (`id_userfield`),
                  CONSTRAINT `news_userfields_ibfk_1` FOREIGN KEY (`id_news`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                  CONSTRAINT `news_userfields_ibfk_2` FOREIGN KEY (`id_userfield`) REFERENCES `shop_userfields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $result = se_db_query($sql);

            file_put_contents(SE_ROOT . '/system/logs/news_public.upd', date('Y-m-d H:i:s - ') . $result);
        }
    }

    public function checkUrl()
    {
        global $SE_REQUEST_NAME;
        $url = explode('?', $_SERVER['REQUEST_URI']);
        $url = str_replace(seMultiDir() . '/' . $this->current_page . '/', '',  $url[0]);
        if (substr($url, -1, 1) == URL_END) $url = substr($url, 0, -1);
        $u = explode('/', $url);
        $result = $this->getIdUrl($url);

        if ($result) {
            $SE_REQUEST_NAME[$u[0]] = 1;
        }
        return $result;
    }

    private function checkCache()
    {
        $sql_cache = "SELECT
			  'news' AS type,
			  COUNT(*) AS cnt,
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ss.updated_at, 0)), MAX(ss.created_at))) AS time
			FROM news ss
			UNION ALL
			SELECT
			  'news_category',
			  COUNT(*),
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ssi.updated_at, 0)), MAX(ssi.created_at)))
			FROM news_category ssi
			UNION ALL
			SELECT
			  'news_img',
			  COUNT(*),
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ssp.updated_at, 0)), MAX(ssp.created_at)))
			FROM news_img ssp
			UNION ALL
			SELECT
			  'news_gcontacts',
			  COUNT(*),
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ngc.updated_at, 0)), MAX(ngc.created_at)))
			FROM news_gcontacts ngc
			";

        $result = se_db_query($sql_cache);

        $cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;

        $update_time = 0;


        if (!empty($result)) {
            while ($line = se_db_fetch_assoc($result)) {
                $this->count += $line['cnt'];
                $update_time = max($update_time, $line['time']);
            }
        }

        $update_time = max(filemtime(__FILE__), $update_time);

        if (!file_exists($this->cache_sections) || filemtime($this->cache_sections) < $update_time || $cache_count != $this->count) {
            $this->parseNewsFromDB();
        } else {
            $this->parseNewsFromCache();
        }
    }

    public function isModerator()
    {
        return (seUserGroup() > 2 || (seUserRole(self::$options['moderator_group']) && seUserGroup() > 0));
    }

    private function parseNewsFromDB()
    {
        $news = new seTable('news', 'n');
        $news->addField('is_date_public', 'tinyint(1)', '0', 1);
        $news->addField('sort', 'integer', '0', 1);
        $news->select("nc.ident, nc.lang, `n`.`id_category`, `n`.`id`, `n`.`short_txt` AS `note`, `n`.`text`, `n`.`img`, `n`.`title`, 
            `n`.`news_date`, `n`.`pub_date`, `n`.`is_date_public`, `n`.`sort`, GROUP_CONCAT(ngc.id_gcontact) AS gcontacts");
        $news->innerjoin("news_category nc", "`n`.id_category = `nc`.id");
        $news->leftjoin("news_gcontacts ngc", "`n`.id = `ngc`.id_news");
        $news->Where("nc.lang = '?'", self::$options['lang']);
        $news->andWhere("n.active = 'Y'");
        $news->groupBy('n.id');
        $news->orderBy('sort', 0);
        $news->addOrderBy('news_date', 1);
        $newslist = $news->getList();
        echo se_db_error();

        $ids = array();
        foreach ($newslist as $val) {
            $ids[] = $val['id'];
        }

        $newsuf = new seTable('news_userfields', 'nuf');
        $newsuf->select('nuf.id_news, suf.code, nuf.value');
        $newsuf->innerjoin('shop_userfields suf', 'nuf.id_userfield=suf.id');
        $newsuf->where('nuf.id_news IN (?)', join(',', $ids));
        $flist = $newsuf->getList();
        $fields = array();
        foreach ($flist as $fld) {
            $fields[$fld['id_news']][$fld['code']] = $fld['value'];
        }


        foreach ($newslist as $val) {

            $val['news_date'] = date('Y-m-d H:i:s', $val['news_date']);
            $val['pub_date'] = date('Y-m-d H:i:s', $val['pub_date']);
            $val['fields'] = $fields[$val['id']];
            self::$sections[] = $val;
            self::$groups[$val['ident']][] = $val['id'];
        }
        $this->saveCache();
    }

    private function parseNewsFromCache()
    {
        self::$sections = json_decode(file_get_contents($this->cache_sections), 1);
    }

    private function saveCache()
    {
        file_put_contents($this->cache_sections, json_encode(self::$sections));
        file_put_contents($this->cache_group, json_encode(self::$groups));
        //$this->writeLog($this->cache_sections . ' - ' . $result);
        file_put_contents($this->cache_count, $this->count);
    }

    public static function getInstance($opt = array(), $pagename = false)
    {
        if (empty($opt['size_image'])) $opt['size_image'] = '200x200';
        if (empty($opt['lang'])) $opt['lang'] = 'rus';
        self::$options = $opt;
        if (empty(self::$options['size_image_full'])) self::$options['size_image_full'] = 800;

        if (is_null(self::$instance)) {
            self::$instance = new self($opt, $pagename = false);
        }
        return self::$instance;
    }

    public function getIdUrl($url)
    {
        $news = new seTable('news', 'n');
        $news->select('n.id');
        $news->innerjoin("news_category nc", "`n`.id_category = `nc`.id");
        $news->where("CONCAT_WS('/', nc.ident, n.url)='?'", $url);
        $news->fetchOne();
        echo se_db_error();
        return $news->id;
    }

    private function writeLog($text)
    {
        $file_log = fopen($this->cache_dir . 'news.log', 'a+');
        fwrite($file_log, date('[Y-m-d H:i:s] ') . $text . "\r\n");
        fclose($file_log);
    }

    private function getRealImgPath()
    {
        return '/images/' . self::$options['lang'] . '/newsimg/';
    }

    private function getPictimage($image, $size, $res = 'm')
    {
        global $CONFIG;
        if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
            return $image;
        }
        $img_name = $this->getRealImgPath() . $image;
        if (!empty($CONFIG['DBLink']) && !empty($image)) {
            return 'http://' . $CONFIG['DBLink'] . $img_name;
        }
        if ($size == 0) {
            return $img_name;
        }
        if (!empty($image) && file_exists(getcwd() . $img_name)) {
            return se_getDImage($img_name, $size, $res);
        } else {
            return '';
        }
    }

    public function getItem($id)
    {
        $news = new seTable('news', 'n');
        $news->addField('seotitle', 'varchar(255)');
        $news->addField('keywords', 'varchar(255)');
        $news->addField('description', 'varchar(255)');
        $news->select("n.id, n.title, n.short_txt as note, n.text, n.img, n.active, n.pub_date, n.news_date,
        `n`.seotitle, `n`.keywords, `n`.description, GROUP_CONCAT(ngc.id_gcontact) AS gcontacts");
        $news->leftjoin("news_gcontacts ngc", "`n`.id = `ngc`.id_news");
        $news->leftjoin("news_userfields nuf", "`n`.id = `nuf`.id_news");
        $val = $news->find($id);
        if ($val['gcontacts']) {
            $gc = explode(',', $val['gcontacts']);
            if (!in_array($this->id_gcontact, $gc)) {
                seData::getInstance()->go404();
            }
        }

        $val['news_date'] = date('Y-m-d H:i:s', $val['news_date']);
        $val['pub_date'] = date('Y-m-d H:i:s', $val['pub_date']);
        $val['image'] = ($val['img']) ? $this->getPictimage($val['img'], self::$options['size_fullimage'], 'm') : '';

        $newsuf = new seTable('news_userfields', 'nuf');
        $newsuf->select('suf.code, nuf.value');
        $newsuf->innerjoin('shop_userfields suf', 'nuf.id_userfield=suf.id');
        $newsuf->where('nuf.id_news=?', $id);
        $fields = $newsuf->getList();
        foreach ($fields as $fld) {
            $val['field_' . $fld['code']] = $fld['value'];
        }


        $newsimg = new seTable('news_img', 'ni');
        $newsimg->select();
        $newsimg->where('id_news=?', $id);
        $imglist = $newsimg->getList();
        $val['imagelist'] = $imglist;
        return $val;
    }

    public function edit($id)
    {
        $result = array();
        if (!$this->isModerator()) {
            $result['status'] = 'error';
            $result['errorcode'] = 'No access';
            return $result;
        }
        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $userfile = $_FILES['userfile']['tmp_name'];
            $userfile_size = $_FILES['userfile']['size'];
            $user = mb_strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES), 'UTF-8');
            $extendfile = 'png';
            $sz = GetImageSize($userfile);
            if (preg_match("/([^.]+)\.(gif|jpeg|jpg|png)$/u", $user, $m) && (($sz[2] == 1) || ($sz[2] == 2) || ($sz[2] == 3))) {
                $extendfile = $m[2];
            } else {
                $result['status'] = 'error';
                $result['errorcode'] = 'It is not an image';
                return $result;
            }
            if ($userfile_size > 10240000) {
                $result['status'] = 'error';
                $result['errorcode'] = 'Large file size';
                return $result;
            }
            $file = true;
        }

        $date = getRequest('date');
        $time = strtotime($date);
        $title = getRequest('title', 4);
        $newstext = trim(getRequest('text', 3));
        $imgname = 'news' . $time . '.' . $extendfile;

        if ($file) {
            $uploadfile = getcwd() . $this->getRealImgPath() . $imgname;
            move_uploaded_file($userfile, $uploadfile);
            ThumbCreate($uploadfile, $uploadfile, '', self::$options['size_image_full']);
        }

        $checkbox = ((getRequest('publics', 3) == 'on') ? 0 : $time);
        $news = new seTable('news', 'n');
        if (empty($id)) {
            $cat_name = "[param20]";
            $newscat = new seTable('news_category', 'nc');
            $newscat->where("nc.ident = '$cat_name'");
            $newscat->andWhere("nc.lang = '?'", se_getlang());
            $newscat->fetchOne();
            $id_cat = $newscat->id;
            if (!$id_cat) {
                $newscat->ident = $cat_name;
                $newscat->title = $cat_name;
                $newscat->lang = se_getlang();
                $id_cat = $newscat->save();
            }
            $news->insert();
            $news->id_category = $id_cat;
        } else {
            $news->find($id);
        }
        $news->news_date = $time;
        $news->pub_date = $checkbox;
        $news->title = $title;
        $news->text = $newstext;
        $news->img = $imgname;
        if ($id_save = $news->save()) {
            $id = (!$id) ? $id_save : $id;
            $result['status'] = 'success';
            $result['result'] = $id;
        } else {
            $result['status'] = 'error';
            $result['errorcode'] = 'An error in the base record';
        }
        return $result;
    }

    public function delete($id)
    {
        $result = array();
        if (!$this->isModerator()) {
            $result['status'] = 'error';
            $result['errorcode'] = 'No access';
            return $result;
        }
        $news = new seTable('news', 'n');
        $news->find($id);
        $filename = $news->img;
        if (!empty($filename)) {
            $temp = explode(".", $filename);
            $filename = getcwd() . $this->getRealImgPath() . $filename;
            if (file_exists($filename)) {
                @unlink($filename);
            }
        }
        if ($news->delete($id)) {
            $result['status'] = 'success';
            $result['result'] = $id;
        } else {
            $result['status'] = 'error';
            $result['errorcode'] = 'An error in the base record';
        }
        return $result;
    }

    public function getItems($code = '', $offset = 0, $limit = 30)
    {
        $items = array();
        $codearr = false;
        if (strpos($code, ',') === false) {
            $codearr = array($code);
        } elseif (!empty($code)) {
            $codearr = explode(',', $code);
        }
        $ff_id = 0;
        foreach (self::$sections as $item) {
            if ($item['gcontacts']) {
                $gc = explode(',', $item['gcontacts']);
                if (!in_array($this->id_gcontact, $gc)) {
                    continue;
                }
            }
            if (empty($codearr) || in_array($item['ident'], $codearr)) {
                if (strtotime($item['pub_date']) < time() || !$item['is_date_public']) {
                    $ff_id++;
                    if ($offset > $ff_id - 1) continue;
                    $item['image_prev'] = ($item['img']) ? $this->getPictimage($item['img'], self::$options['size_image'], 'm') : '';
                    //$item['image'] = ($item['img']) ? $this->getRealImgPath() . $item['img'] : '';
                    foreach ($item['fields'] as $n => $f) {
                        $item['field_' . $n] = $f;
                    }
                    unset($item['img'], $item['fields']);
                    $items[] = $item;
                    if (count($items) >= $limit) break;
                }
            }
        }
        return $items;
    }

    public function getList($code = false, $limit = 30)
    {
        $news = new seTable('news', 'n');
        $news->addField('is_date_public', 'tinyint(1)', '0', 1);
        $news->addField('sort', 'integer', '0', 1);
        $news->addField('url', 'varchar(255)', '', 2);
        $news->addField('seotitle', 'varchar(255)');
        $news->addField('keywords', 'varchar(255)');
        $news->addField('description', 'varchar(255)');
        $news->select("nc.ident, n.url, nc.lang, `n`.`id_category`, `n`.`id`, `n`.`short_txt` AS `note`, `n`.`text`, `n`.`img`, `n`.`title`,
            `n`.`news_date`, `n`.`pub_date`, `n`.`is_date_public`, `n`.`sort`, GROUP_CONCAT(ngc.id_gcontact) AS gcontacts");
        $news->innerjoin("news_category nc", "`n`.id_category = `nc`.id");
        $news->leftjoin("news_gcontacts ngc", "`n`.id = `ngc`.id_news");
        //$news->Where("nc.lang = '?'", self::$options['lang']);
        $news->Where("n.active = 'Y'");
        if (!empty($code))
            $news->andWhere("nc.ident IN ('?')", $code);
        if (self::$options['lang'])
            $news->andWhere("nc.lang = '?'", self::$options['lang']);
        $news->andWhere("((SELECT COUNT(ngc1.id) FROM news_gcontacts ngc1 WHERE `ngc1`.id_news=`n`.id) < 1 OR ngc.id_gcontact=?)", intval($this->id_gcontact));
        $news->groupBy('n.id');
        $news->orderBy('sort', 0);
        $news->addOrderBy('news_date', 1);
        $pnav = $news->pageNavigator($limit);
        $newslist = $news->getList();
        echo se_db_error();

        $ids = array();
        foreach ($newslist as $val) {
            $ids[] = $val['id'];
        }

        $newsuf = new seTable('news_userfields', 'nuf');
        $newsuf->select('nuf.id_news, suf.code, nuf.value');
        $newsuf->innerjoin('shop_userfields suf', 'nuf.id_userfield=suf.id');
        $newsuf->where('nuf.id_news IN (?)', join(',', $ids));
        $flist = $newsuf->getList();
        $fields = array();
        foreach ($flist as $fld) {
            $fields[$fld['id_news']][$fld['code']] = $fld['value'];
        }
        $result = array();
        foreach ($newslist as $val) {
            $val['image_prev'] = ($val['img']) ? $this->getPictimage($val['img'], self::$options['size_image'], 'm') : '';
            $val['news_date'] = date('Y-m-d H:i:s', $val['news_date']);
            $val['pub_date'] = date('Y-m-d H:i:s', $val['pub_date']);
            $val['fields'] = $fields[$val['id']];
            $val['url'] = seMultiDir() . '/' . $this->current_page . '/' . $val['ident'] . '/' . $val['url'] . SE_END;
            $result[] = $val;
        }
        return array($result, $pnav);
    }
}
