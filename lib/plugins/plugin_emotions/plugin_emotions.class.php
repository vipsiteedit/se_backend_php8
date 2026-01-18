<?php

/**
 * Created by PhpStorm.
 * User: Ponomarev
 * Date: 06.02.2015
 */

class plugin_emotions
{

    public $id_group = array();

    public function __construct()
    {
    }

    public function getGroups()
    {
        $groups = new seTable('emotion_groups');
        $groups->select('id, img, img_alt, title');
        if (!empty($this->id_group))
            $groups->where("`id` IN (?)", implode(",", $this->id_group));
        $groupsList = $groups->getList();

        return $groupsList;
    }

    public function getEmotions($id_groups = array())
    {
        $emotions = new seTable('emotions');
        $emotions->select('id, id_group, code, img, img_alt, abbr');
        if (!empty($id_groups))
            $this->setGroup($id_groups);
        if (!empty($this->id_group))
            $emotions->where("`id_group` IN (?)", implode(",", $this->id_group));
        $emotionsList = $emotions->getList();

        return $emotionsList;
    }

    public function setGroup($group = array())
    {
        if (!is_array($group))
            $this->id_group = array();
        else
            $this->id_group = $group;
    }

    public function code2emotion($text = '', $path = '')
    {
        if (empty($text)) return $text;
        if (empty($path)) $path = '/lib/emotion/';
        $emotionsList = $this->getEmotions(array());
        $search = $replace = array();
        foreach ($emotionsList as $line) {
            $search[] = $line['code'];
            $replace[] = "<img src='" . $path . $line['img'] . "' class='emotionClass' alt='" . base64_decode($line['img_alt']) . "' title='" . base64_decode($line['img_alt']) . "'>";
            if (!empty($line['abbr'])) {
                $search[] = $line['abbr'];
                $replace[] = "<img src='" . $path . $line['img'] . "' class='emotionClass' alt='" . base64_decode($line['img_alt']) . "' title='" . base64_decode($line['img_alt']) . "'>";
            }
        }
        $text = str_replace($search, $replace, $text);

        return $text;
    }
}
