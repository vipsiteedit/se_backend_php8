<?php

class plugin_geosites
{

    private static $instance = null;
    private $contactlist;
    private $domain;
    private $city;
    private $id_city = 0;
    private $id_contact = 0;
    private $basedomain;

    public function __construct($isMulti = false, $noGeo = false)
    {
        $plugin_geoip = new plugin_geoip();
        $sc = new seTable('shop_contacts', 'sc');
        $sc->select('sc.id, sc.name, sc.url, scg.id_city');
        $sc->leftjoin('shop_contacts_geo scg', 'scg.id_contact=sc.id');
        $sc->where('sc.is_visible=1');
        if (isRequest('find')) {
            $sc->andwhere("sc.name LIKE '?%'", getRequest('find', 3));
        }
        $sc->orderBy('name');
        $this->contactlist = $sc->getList();
        if (!count($this->contactlist)) return;

        // Получение базового домена
        $this->basedomain = $this->getBaseDomain();

        // Выбираем город когда базовый домен
        if ($this->isDomain($this->basedomain) && !empty($_SERVER['HTTP_REFERER']) || $noGeo || true) {
            // Получаем выбранный ID контакта
            $it = $this->getContactUrl(true);
            $this->storeSession($it);
        } else {
            // Находим город локализации по IP
            $city = $plugin_geoip->getCity();
            $this->id_city = $city['id'];
            $this->id_contact = $this->getContactId($city['id']);
        }

        $tmp_it = false;
        $this->city = '';

        // Получим базовый сайт
        if (!$this->id_contact) {
            foreach ($this->contactlist as $it) {
                if (!$this->city && !$it['url']) {
                    // Получим город с неопределенным доменом
                    $tmp_it = $it;
                }
                if ($it['id_city'] && $this->id_city == $it['id_city']) {
                    $tmp_it = $it;
                    break;
                }
            }
            if ($tmp_it) {
                $this->storeSession($tmp_it);
            }
        } else {
            if ($it = $this->getContact($this->id_contact)) {
                $this->storeSession($it);
            }
        }
        $this->getContactDomain();
    }

    private function go301($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $url);
        exit;
    }

    // Это текущий домен
    private function isDomain($domain)
    {
        $host = str_replace(array('http://', 'https://', '//'), '', $domain);
        if (strpos($domain, '://') !== false) {
            list($http,) = explode(':', $domain);
            $http .= '://';
        } else {
            $http = _HTTP_;
        }
        return (($http == _HTTP_) && ($host == $_SERVER['HTTP_HOST'] || 'www.' . $host == $_SERVER['HTTP_HOST']));
    }

    private function getContactDomain()
    {
        if ($this->isDomain($this->basedomain)) {
            // Это базовый домен
            if ($this->id_contact && !$this->isBot()) {
                if ($it = $this->getContactUrl()) {
                    // Если базовый домен есть в списке
                    $this->storeSession($it);
                } else {
                    //echo $this->id_contact;
                    $it = $this->getContact($this->id_contact);
                    $url = $this->getUrl($it['url']);
                    if ($url != $this->basedomain) {
                        //$this->go301($url . $_SERVER['REQUEST_URI']);
                    }
                    $this->storeSession($it);
                }
            }
        } else {
            // Это мультидомен
            if ($it = $this->getContactUrl()) {
                $this->storeSession($it);
            } else {
                // Домен не найден, редиректимся на базовый домен
                $this->go301($this->basedomain . $_SERVER['REQUEST_URI']);
            }
        }
    }

    public function getBaseDomain()
    {
        $this->basedomain = se_getAdmin('domain');
        if (!$this->basedomain) {
            $dm = explode('.', $_SERVER['HTTP_HOST']);
            if ($dm[0] !== 'www' || count($dm) > 2)
                $this->basedomain = _HTTP_ . $dm[count($dm) - 2] . '.' . $dm[count($dm) - 1];
            else
                $this->basedomain = _HTTP_ . $_SERVER['HTTP_HOST'];
        }
        $this->domain = $this->basedomain;
        return $this->basedomain;
    }

    public function getContactId($id_city)
    {
        if (!$id_city) {
            foreach ($this->contactlist as $it) {
                if ($id_city == $it['id_city']) {
                    return $it['id'];
                }
            }
        }
        return 0;
    }

    public function getContactUrl($isBase = false)
    {
        foreach ($this->contactlist as $it) {
            if (($it['url'] || $isBase) && $this->isDomain($this->getUrl($it['url']))) {
                return $it;
            }
        }
        return false;
    }


    public function getContact($id)
    {
        if ($id) {
            foreach ($this->contactlist as $it) {
                if ($id == $it['id']) {
                    return $it;
                }
            }
        }
        return array();
    }

    private function storeSession($it)
    {
        $this->city = $it['name'];
        $this->domain = $this->getUrl($it['url']);
        $this->id_contact = $it['id'];
        $this->id_city = $it['id_city'];
        $_SESSION['user_region']['id_city'] = $it['id_city'];
        $_SESSION['user_region']['id_contact'] = $it['id'];
    }

    public function getUrl($url)
    {
        if ($url) {
            if (substr($url, strlen($url) - 1, 1) == '/') $url = substr($url, 0, -1);
            $thisurl = (strpos($url, '.') === false) ? $url . '.' . str_replace(array('https://', 'http://', '//'), '',  $this->basedomain) : $url;
            //$thisurl = (strpos($thisurl, '://') !== false) ? end(explode('://', $thisurl)) : $thisurl;
            if (strpos($thisurl, '://') === false)
                $thisurl = 'http://' . $thisurl;
            return $thisurl;
        } else {
            return $this->basedomain;
        }
    }

    private function isBot()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/(^(?!.*\(.*(\w|_|-)+.*\))|bot[^a-z])/i', $user_agent)) {
            return true;
        }

        $bots = array(
            'crawler', 'rambler', 'googlebot', 'aport', 'yahoo', 'msnbot', 'turtle', 'mail.ru', 'omsktele',
            'yetibot', 'picsearch', 'sape.bot', 'sape_context', 'gigabot', 'snapbot', 'alexa.com',
            'megadownload.net', 'askpeter.info', 'igde.ru', 'ask.com', 'qwartabot', 'yanga.co.uk',
            'scoutjet', 'similarpages', 'oozbot', 'shrinktheweb.com', 'aboutusbot', 'followsite.com',
            'dataparksearch', 'google-sitemaps', 'appEngine-google', 'feedfetcher-google',
            'liveinternet.ru', 'xml-sitemaps.com', 'agama', 'metadatalabs.com', 'h1.hrn.ru',
            'googlealert.com', 'seo-rus.com', 'yaDirectBot', 'yandeG', 'yandex',
            'yandexSomething', 'Copyscape.com', 'AdsBot-Google', 'domaintools.com',
            'Nigma.ru', 'bing.com', 'dotnetdotcom'
        );
        foreach ($bots as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    public function getIdContact()
    {
        return $this->id_contact;
    }

    public function getIdCity()
    {
        return $this->id_city;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getName()
    {
        return $this->city;
    }

    public function getList()
    {
        return $this->contactlist;
    }

    public static function getInstance($isMulti = false, $noGeo = false)
    {
        if (self::$instance === null) {
            self::$instance = new self($isMulti, $noGeo);
        }
        return self::$instance;
    }
}
