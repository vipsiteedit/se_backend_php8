<?php

class yaTurboPage
{
    private $description;
    private $title;
    private $host;
    private $lang = "ru";
    private $prj;


    public function __construct($folder = '', $filename)
    {
        $pagelist = array();
        $breadlist = array();
        $this->host = _HTTP_ . $_SERVER['HTTP_HOST'];
        $path = SE_SAFE . 'projects/' . SE_DIR;

        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'project.xml')) {
            $this->prj = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . 'project.xml');
            $this->title = $this->prj->vars->sitetitle;
            $this->description = $this->prj->gdescription;
        }
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'project.xml')) {
            $this->pages = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . 'pages.xml');
        }

        if (!empty($this->pages)) {
            foreach ($this->pages->page as $page) {
                if (!$page->indexes || $page->groupslevel > 0) continue;
                $name = strval($page['name']);
                //echo "{$name}\n";
                $breadlist[$name]['level'] = intval($page->level);
                $sect = array();
                foreach ($page->modules as $modul) {
                    if ($modul['id'] < 1000) {
                        $sect[] = array('id' => intval($modul['id']), 'name' => strval($modul['name']));
                    }
                }

                //echo SE_SAFE . 'projects/' . SE_DIR . 'pages/'+$name+'.xml';
                if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'pages/' . $name . '.xml')) {
                    $pg = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . 'pages/' . $name . '.xml');

                    $body = $this->getContent($pg);
                    if ($body) {
                        $p = array(
                            'name' => strval($page['name']),
                            'title' => $this->parseValue(strval($page->title)),
                            'url' => $this->host . '/' . strval($page['name']),
                            'pubDate' => '',
                            'breadcrumblist' => [],
                            'context' => $this->parseValue($body)
                        );
                        $pagelist[] = $p;
                    }
                }
            }
        }
        $body = $this->make($pagelist);
        file_put_contents($filename, $body);
    }

    private function parseValue($text)
    {
        if (SE_DB_ENABLE && file_exists(SE_LIBS . 'plugins/plugin_geo/plugin_geovalues.class.php')) {
            $gval = plugin_geovalues::getInstance();
            $text = $gval->parseValues($text);
        }
        return $text;
    }


    private function getContent($pg)
    {
        $result = '';
        foreach ($pg->sections as $section) {
            if (strval($section->id) < 1000 && strval($section->text)) {
                if (trim($section->title))
                    $result .= "<h3>" . strval($section->title) . "</h3>\n";
                $result .= strval($section->text) . "\n";
            }
        }
        //$result = str_replace("[page.title]", strval($pg->title), $result);
        return $result;
    }

    private function getObjects($pg)
    {

        $result = array();
        foreach ($pg->sections as $section) {
            //            switch $section->type {
            //                case 'apublication': {}
            //            }
            if ($section->type == 'apublication') {
            }
            if (count($section->objects) && $section->showobject) {
            }
        }
        $result = str_replace("[page.title]", strval($pg->title), $result);
        return $result;
    }


    private function make($pagelist)
    {
        $new = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $new .= "<rss xmlns:yandex=\"http://news.yandex.ru\"\n";
        $new .= "xmlns:media=\"http://search.yahoo.com/mrss/\"\n";
        $new .= "xmlns:turbo=\"http://turbo.yandex.ru\"\n";
        $new .= "version=\"2.0\">\n";
        $new .= "<channel>\n";
        $new .= "\t<title>" . htmlspecialchars($this->title) . "</title>\n";
        $new .= "\t<link>" . $this->host . "</link>\n";
        $new .= "\t<description>" . htmlspecialchars($this->description) . "</description>\n";
        $new .= "\t<language>" . $this->lang . "</language>\n";
        $new .= "\t<turbo:analytics></turbo:analytics>\n";
        $new .= "\t<turbo:adNetwork></turbo:adNetwork>\n";
        foreach ($pagelist as $item) {
            $new .= "\t<item turbo=\"true\">\n";
            $new .= "\t\t<link>" . $item['url'] . "/</link>\n";
            $new .= "\t\t<turbo:source></turbo:source>\n";
            $new .= "\t\t<turbo:topic></turbo:topic>\n";
            if ($item['pubDate'])
                $new .= "\t\t<pubDate>" . $item['pubDate'] . "</pubDate>\n";
            $new .= "\t\t<metrics>\n";
            $new .= "\t\t\t<yandex schema_identifier=\"" . htmlspecialchars($item['title']) . "\">\n";
            foreach ($item['breadcrumblist'] as $br) {
                $new .= "\t\t\t\t<breadcrumb url=\"{$br['url']}\" text=\"" . htmlspecialchars($br['title']) . "\"/>\n";
            }
            //$new .= "\t\t\t<yandex schema_identifier=\"{$item['title']}\">\n";
            $new .= "\t\t\t</yandex>\n";
            $new .= "\t\t</metrics>\n";
            $new .= "\t\t<yandex:related></yandex:related>\n";
            $new .= "\t\t<turbo:content>\n";
            $new .= "\t\t<![CDATA[{$item['context']}]]>\n";
            $new .= "\t\t</turbo:content>\n";
            $new .= "\t</item>\n";
        }
        $new .= "</channel></rss>\n";

        return $new;
        // $file = fopen('rss-'.$name . '.xml', "w+");
        // fwrite($file, $new);
        // fclose($file);
    }
}
