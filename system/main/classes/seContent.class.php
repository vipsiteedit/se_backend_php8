<?php

class seContent
{

    private $num;
    private $se;

    public function __construct($num = 0)
    {
        $this->num = $num;
        $this->se = seData::getInstance();
    }

    public function execute()
    {
        $razdel = $this->se->req->razdel;
        $object = $this->se->req->object;
        $sub = $this->se->req->sub;

        $result = '';


        // Если активизирован
        // if (getRequest('sub') && !$this->num)
        //   $result .= @$raz_subpage[$razdel][$sub];
        // else // вызываем архив

        if (!$this->num && $razdel && ($object || $sub || isRequest('arhiv'))) {
            // Подробный просмотр
            if ($this->getAccessRecord($razdel)) {
                $modul = new seModule39($razdel);
                $result .= $modul->execute(0);
                unset($modul);
            } else {
                $result .= seAuthorizeForm();
            }
        } else
            if (getRequest('arh') && !$this->num) {
                if ($this->getAccessRecord($razdel)) {
                    $result .= getArchiv(getRequest('arh', 1));
                }
            } else {

                foreach ($this->se->sections as $section_id => $section) {
                    $section_id = strval($section->id);
                    if (strpos($section_id, '.')) continue;
                    //if (!$this->num && $razdel && $section_id!=$razdel) continue;

                    if ($this->getAccessModule($section_id)
                        && $section_id > $this->num * 1000 && $section_id < $this->num * 1000 + 1000
                    ) {
                        $modul = new seModule39($section_id);
                        $result .= $modul->execute($this->num);
                        unset($modul);
                    }
                }
            }

        return $result;
    }


    private function getAccessModule($section_id)
    {
        $section = $this->se->getSection($section_id);
        $access = getLoginAccess($section->accessgroup, $section->accessname, seUserGroup(), seUserGroupName(), seUserLogin());
        return (($section->accessgroup < 1) || ($access || !intval($section->showsection)));
    }

    private function getAccessRecord($section_id)
    {
        $section = $this->se->getSection($section_id);
        $access = getLoginAccess($section->accessgroup, $section->accessname, seUserGroup(), seUserGroupName(), seUserLogin());
        return (($section->accessgroup < 1) || ($access || !intval($section->showobject)));
    }


    /**
     * Обработка модуля
     * @param $section object секция раздела
     * @param $id_section integer номер раздела
     * @return string HTML текст раздела;
     */
    private function getModule($section, $section_id, $ajaxexecute)
    {
        // Исполняем модуль
        return $section->modulecontent;// $this->se->sections[$section_id]->modulecontent;
    }
}

?>