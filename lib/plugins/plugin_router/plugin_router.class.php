<?php


class plugin_router
{

    private static $instance = null;
    private $routes = array();

    private $host;

    private $patterns = array(
        'num' => '[0-9]+',
        'str' => '[a-zA-Z\.\-_%]+',
        'all' => '[a-zA-Z0-9\.\-_%]+',
    );

    private $next_page = null, $prev_page = null;

    public function __construct($option = array())
    {
        $this->host = $this->getHost();
    }

    private function getHost()
    {
        return _HOST_;
    }

    private function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        if (SE_DIR != '' && substr($uri, 1, strlen(SE_DIR)) == SE_DIR && seMultiDir() == '') {
            $uri = substr($uri, strlen(SE_DIR), strlen($uri));
        }
        return $uri;
    }

    public static function getInstance($option = array())
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($option);
        }
        return self::$instance;
    }

    public function register($type, $pattern, $params = array())
    {
        $match = '';

        if ($pattern) {
            $match = str_replace('?', '\?', $pattern);

            $match = preg_replace('#\[([^\]]+)\]#', '(?:$1)?', $match);

            $match = preg_replace('#\$(\w+)#', '(?<$1>[a-zA-Z0-9\-_%]+)', $match);

            $match = '#^' . $match . '#s';
        }
        $this->routes[$type] = array(
            'type' => $type,
            'pattern' => $pattern,
            'match' => $match,
            'params' => $params,
        );
    }

    public function registerParams($type, $params = null)
    {
        if (!empty($params) && isset($this->routes[$type])) {
            if (is_string($params)) {
                $this->addParam($type, $params);
            } elseif (is_array($params)) {
                foreach ($params as $val) {
                    $this->addParam($type, $val);
                }
            }
        }
    }

    private function addParam($type, $param = '')
    {
        $this->routes[$type]['params'][$param] = $param;
    }

    public function getCanonical($url = '')
    {
        //print_r($this->routes);
        if (!$url) {
            $url = $this->getRequestUri();
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['match'], $url, $m)) {

                $params = array();
                foreach ($m as $key => $val) {
                    $params['$' . $key] = $val;
                }
                $url = strtr($route['pattern'], $params);

                $url = preg_replace('#(\[.*\$[a-zA-Z0-9\-_%]+\])|([\[\]]*)#s', '', $url);

                if (!empty($route['params']) && strpos($_SERVER['REQUEST_URI'], '?') !== false) {
                    list($url, $query_string) = explode('?', $_SERVER['REQUEST_URI']);

                    parse_str($query_string, $query_params);

                    $result = array_intersect_key($query_params, $route['params']);

                    if ($result) {
                        $url .= '?' . http_build_query($result);
                    }
                }

                break;
            }
        }

        return $this->host . $url;
    }

    public function showCanonical()
    {
        $canonical = $this->getCanonical();

        $link = '';

        if (is_null($this->next_page) && is_null($this->prev_page)) {
            $link = '<link rel="canonical" href="' . $canonical . '">';
        } else {
            if (strpos($canonical, '?') !== false) {
                if (!is_null($this->next_page))
                    $this->next_page = str_replace('?', '&', $this->next_page);
                if (!is_null($this->prev_page))
                    $this->prev_page = str_replace('?', '&', $this->prev_page);
            }

            if (!is_null($this->next_page))
                $link = '<link rel="next" href="' . $canonical . $this->next_page . '">';
            if (!is_null($this->prev_page))
                $link .= '<link rel="prev" href="' . $canonical . $this->prev_page . '">';
        }

        return $link;
    }

    public function setNextPage($page)
    {
        $this->next_page = '?sheet=' . $page;
    }

    public function setPrevPage($page)
    {
        $this->prev_page = ($page == 1) ? '' : '?sheet=' . $page;
    }

    public function createUrl($type, $params = array(), $full = false)
    {

        if (!isset($this->routes[$type]))
            return;

        $route = $this->routes[$type];

        if (preg_match_all('#\$(\w+)#', $route['pattern'], $m)) {
            $replace = array();
            foreach ($m[1] as $key => $val) {
                if (isset($params[$val])) {
                    $replace['$' . $val] = $params[$val];
                    unset($params[$val]);
                }
            }
            $url = strtr($route['pattern'], $replace);
        }

        $url = preg_replace('#(\[[a-zA-Z0-9\-_%/]*\$[a-zA-Z0-9\-_%]+\])|([\[\]]*)#s', '', $url);

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        if (!$full)
            $url = $this->host . $url;


        return $url;
    }
}
