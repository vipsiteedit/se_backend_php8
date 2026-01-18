<?php

/**
 * @author sergey
 * @copyright 2009
 */

class seModule39
{

  private $num;
  //private $namefile;
  //private $section;
  private $id_content;
  private $data;
  //private $vars = array();
  public function __construct($id_content)
  {
    $this->data = seData::getInstance();
    $id_content = strval($id_content);
    list($num_cont,) = explode('.', $id_content . '.');
    $this->num = floor($num_cont / 1000);
    $this->id_content = $id_content;
  }

  public function execute($num)
  {
    //	include $filemodule;
    $se = $this->data;
    $object_id = strval($se->req->object);
    $sub_name = strval($se->req->sub);
    $content_id = strval($se->req->razdel);

    $section = $se->getSection(strval($this->id_content));
    $page = $se->req->page;
    if (empty($page)) {
      $page = 'home';
    }

    $result = '';


    if (($sub_name || ($object_id && strpos($section->body, '[showrecord ') === false) || isRequest('arhiv')) && $this->id_content == $content_id && !$num) {
      $section = $se->getSection($content_id);
      if ($sub_name && !empty($section->subpage->$sub_name->form)) {
        $group = $section->subpage->$sub_name->group;

        if (getLoginAccess($group, '', seUserGroup(), seUserGroupName(), seUserLogin())) {

          $result = $this->template_parse($section, $section->subpage->$sub_name->form);
        } else {
          $result = seAuthorizeForm();
        }
      } else
        if (!empty($section->objects) && $object_id) {
        $result = $this->parse_records($this->data->getObject($section, $object_id), $section->formshow);
      } else
          if (!empty($section->objects) && isRequest('arhiv')) {
        //$result = se_record_arhiv($section,;
        $section->objectcount = 30;
        $result = $this->template_parse($section, $section->formarhiv);
        $result = str_replace('[SE_PARTSELECTOR]', (string)SE_PARTSELECTOR($section->id, count($section->objects), $section->objectcount, getRequest('item', 1), getRequest('sel', 1)), (string)$result);
      }
    } else {


      $result = str_replace('[records]', '<repeat:objects>[records]</repeat:objects>', (string)$section->body);
      $result = $this->template_parse($section, $result);
      $result = str_replace('[SE_PARTSELECTOR]', (string)SE_PARTSELECTOR($section->id, count($section->objects), $section->objectcount, getRequest('item', 1), getRequest('sel', 1)), (string)$result);
    }

    while (preg_match("/\{macros\}(.+?)\{\/macros\}/usi", $result, $m)) {
      $m[1] = str_replace(array('&#91;', '&#93;'), array('[', ']'), $m[1]);
      // m[1] := match_function(template_parse(m[1]));
      $result = str_replace($m[0], $this->template_parse($section, $m[1]), $result);
    }

    $result = $this->conditions($result);

    return str_replace(array('&#91;', '&#93;'), array('[', ']'), $result);
  }


  private function getSubPage($section, $sub_name)
  {
    $section->subname = $sub_name;

    if (!empty($section->subpage->$sub_name->form) && $section->subpage->$sub_name->group <= seUserGroup()) {
      return $section->subpage->$sub_name->form;
    }
  }

  private function getExplode($text, $item, $find)
  {
    if (strpos($text, $find) !== false) {
      $result = explode($find, $text);
      return $result[$item];
    } else  return $text;
  }

  public function template_parse($section, $text)
  {
    $object_id = strval($this->data->req->object);
    $sub_name = strval($this->data->req->sub);
    $thispage = $this->data->getPageName();

    $text = str_replace('[thisreq.page]', $thispage, $text);
    // ����� ������� �� �������
    $text = str_replace('[thisreq.section]', $this->data->req->razdel, $text);
    // ����� ����������� �� �������
    $text = str_replace('[thisreq.sub]', $sub_name, $text);
    // ����� ������� �� �������
    $text = str_replace('[thisreq.object]', $object_id, $text);

    $text = str_replace(array('[this_url]', '[project.path]'), '/', $text);

    if (preg_match("/\[(this_url_modul|module_url)\]/", $text, $m)) {
      if (file_exists('lib/modules/' . $section->type . '/')) {
        $dirmodule = '/lib/modules/' . $section->type . '/';
      } else {
        $dirmodule = '/modules/' . $section->type . '/';
      }

      $text = str_replace($m[0], $dirmodule, $text);
    }
    $text = str_replace('[system.path]', SE_CORE, $text);


    while (preg_match("/\[subpage[\s+]?name=([\d\w_\-]+)[\s+]?\]/iu", $text, $m)) {
      if (seData::getInstance()->req->sub != trim($m[1])) {
        $text = str_replace($m[0], $this->template_parse($section, str_replace(
          $m[0],
          '',
          $section->subpage->$m[1]->form
        )), $text);
      } else $text = str_replace($m[0], '', $text);
    }

    while (preg_match("/<repeat:pages>(.+?)<\/repeat:pages>/usi", $text, $m)) {
      $pages = $this->data->getPages();
      $rep = '';
      foreach ($pages as $p) {
        if (!intval($p->indexes)) continue;
        $res = str_replace('[page.title]', $p->title, $m[1]);
        $res = str_replace('[page.link]', seMultiDir() . '/' . $p['name'][0], $res);
        $rep .= str_replace('[page.mapid]', 'maplinks' . $p->level, $res);
      }

      $text = str_replace($m[0], $rep, $text);
    }

    $text = str_replace(array('[arhiv.link]', '[link.arhiv]'), seMultiDir() . '/' . $thispage . '/' . $section->id . '/arhiv/', $text);

    while (preg_match("/\[\@subpage_?([\d\w]+)\]/", $text, $m)  || preg_match("/\[link\.subpage=([\d\w]+)\]/", $text, $m)) {
      $text = str_replace($m[0], seMultiDir() . '/' . $this->data->getPageName() . '/' . $section->id . '/sub' . $m[1] . '/', $text);
    }

    $text = str_replace('[sys.date]', Date('d.m.Y'), $text);
    $text = str_replace('[sys.time]', Date('H:i:s'), $text);
    $text = str_replace(array('[namepage]', '[firstnamepage]'), $thispage, $text);

    $text = str_replace(array('[thispage.link]', '[link.page]'), seMultiDir() . '/' . $thispage . '/', $text);
    $text = str_replace('[thispage.name]', $thispage, $text);
    $text = str_replace('[thispage.title]', $this->data->page->title, $text);

    while (preg_match("/<arhiv:link>(.+?)<\/arhiv:link>/usi", $text, $m)) {
      if (count($section->objects)  <= $section->objectcount || $section->objectcount == 0) {
        $m[1] = '';
      }
      $text = str_replace($m[0], $m[1], $text);
    }


    $text = preg_replace("/\[link\.record=([\d]+)\]/", seMultiDir() . "/$thispage/{$section->id}/$1/", $text);
    $text = preg_replace("/\[@subpage([\d\w]+)\]/", seMultiDir() . "/$thispage/{$section->id}/sub$1/", $text);

    ////////////////////
    while (preg_match("/\[matchread\.(.+?)\/matchread\]/usi", $text, $m)) {
      $res = '';
      $text = str_replace($m[0], $res, $text);
    }

    $text = $this->parse_part($section, $text);
    while (preg_match("/<repeat:(objects|records)>(.+?)<\/repeat:\\1>/usi", $text, $m)) {
      if ($m[2] != '') {
        $m[2] = str_replace('[records]', $this->template_parse($section, $section->formobject), $m[2]);
      }
      $text = str_replace($m[0], $this->getRecords($m[2], intval($m[1])), $text);
    }

    while (preg_match("/<arhiv:item>(.+?)<\/arhiv:item>/usi", $text, $m)) {
      $text = str_replace($m[0], $this->getRecords($m[1], 0), $text);
    }

    while (
      preg_match("/<repeat:(.+?)>/usi", $text, $m)
      && preg_match("/<repeat:(" . $m[1] . ")>(.+?)<\/repeat:" . $this->getExplode($m[1], 0, ' ') . ">/usi", $text, $m)
    ) {
      $listname = $this->getExplode($m[1], 0, ' ');

      if (!strpos($m[1], ' name=')) $alias = $this->getExplode($m[1], 0, ' ');
      else $alias = $this->getExplode($m[1], 1, ' name=');
      $objectlist = '';

      if (!empty($section->$listname)) {
        foreach ($section->$listname as $record) {
          $mm = $this->parse_records($record, $m[2], $alias);
          $objectlist .= $this->parse_records($record, $this->template_parse($section, $mm), $alias);
        }
      }
      $text = str_replace($m[0], $objectlist, $text);
    }


    while (preg_match("/([\'\"\(])([\w\d\-_]+)\.html/", $text, $m)) {
      $m[2] = $m[1] . seMultiDir() . '/' . join('/', explode('_', $m[2])) . '/';
      $text = str_replace($m[0], $m[2], $text);
    }

    while (preg_match("/\[%([\w\d\-_]+)%\]/", $text, $m)) {
      $text = str_replace($m[0], $this->data->prj->{$m[1]}, $text);
    }

    while (preg_match("/\[showrecord[\s+]?name=([\d\w]+)[\s+]?\]/", $text, $m)) {
      if ($m[1] == 'this') {
        if ($object_id < 1) {
          $record = $this->getFirstRecord($section);
        } else $record = $object_id;
        $text = str_replace($m[0], $this->parse_records(
          $this->data->getObject($section, $record),
          $this->template_parse($section, $section->formshow)
        ), $text);
      } else {
        $record = intval($m[1]);
        $text = str_replace($m[0], $this->parse_records(
          $this->data->getObject($section, $record),
          $this->template_parse($section, $section->formshow)
        ), $text);
      }
    }

    return str_replace("\r\n\r\n", "\r\n", $text);
  }

  private function getFirstRecord($section)
  {
    foreach ($section->objects as $record) {
      return intval($record->id);
    }
  }

  private function parse_part($section, $text)
  {
    while (preg_match("/<noempty:part\.([\w\d\-_]+)>(.+?)<\/noempty>/usi", $text, $m)) {
      $field = $m[1];
      if (trim(strval($section->$field))  == '') {
        $m[2] = '';
      }
      $text = str_replace($m[0], $m[2], $text);
    }

    while (preg_match("/\"\[(part\.[\w\d\-_]+)\]\"/", $text, $m)) {
      $field = $m[1];
      $text = str_replace($m[0], '"' . htmlspecialchars($this->getValue($field)) . '"', $text);
    }

    while (preg_match("/\[(part\.[\w\d\-_]+)\]/", $text, $m)) {
      $field = $m[1];
      $text = str_replace($m[0], $this->getValue($field), $text);
    }
    return $text;
  }

  private function parse_records($record, $text, $namevar = 'record')
  {
    $text = preg_replace("/\[obj\..+?\]/", '', $text);
    while (preg_match("/<noempty:(" . $namevar . "|record)\.([\w\d\-_]+)>(.+?)<\/noempty>/usi", $text, $m)) {
      if (strval($record->{$m[2]})  == '') $m[3] = '';
      $text = str_replace($m[0], $m[3], $text);
    }

    while (preg_match("/<empty:(" . $namevar . "|record)\.([\w\d\-_]+)>(.+?)<\/empty>/usi", $text, $m)) {
      if (strval($record->{$m[2]})  != '') $m[3] = '';
      $text = str_replace($m[0], $m[3], $text);
    }


    while (preg_match("/\"\[(" . $namevar . "|record)\.([\d\w\-_]+)\]\"/ui", $text, $m)) {
      $text = str_replace($m[0], '"' . htmlspecialchars($this->getValue('record.' . $m[2], $record)) . '"', $text);
    }

    while (preg_match("/\[(" . $namevar . "|record)\.([\d\w\-_]+)\]/ui", $text, $m)) {
      $text = str_replace($m[0], $this->getValue('record.' . $m[2], $record), $text);
    }
    $text = str_replace(array('[objedit]', '[*edobj]'), '', $text);
    $text = $this->conditions($text, $record);


    while (preg_match("/\[textline\.(.+?)\/textline\]/usi", $text, $m)) {
      $note = explode("\n", str_replace(array("\r", '<br>'), '', $record->note));
      $value = $m[1];
      $num = 1;
      $lineresult = '';
      if (!empty($note)) foreach ($note as $line) {
        $ss = $value;
        if (strpos($line, '*')  === 0) {
          $ss = str_replace(array('%selected%', '%checked%'), array('SELECTED', 'CHECKED'), $ss);
          $line = utf8_substr($line, 1);
        } else {
          $ss = str_replace(array('%selected%', '%checked%'), '', $ss);
        }
        $expl = explode('%%', $line);
        $ss = str_replace('@textline', @$expl[0], $ss);
        $ss = str_replace('@textlineval', @$expl[1], $ss);
        $lineresult .= str_replace('@textline_num', $num, $ss);
        $num++;
      }
      $text = str_replace($m[0], $lineresult, $text);
    }
    return str_replace("\r\n\r\n", "\r\n", $text);
  }

  private function getRecords($objform, $num_rec = 0)
  {
    $section = $this->data->getSection($this->id_content);
    $objform = preg_replace("/\[@col_(.+?)\]/", "[record.$1]", $objform);

    $result = '';
    $_item = getRequest('item', 1);
    if ($_item < 1) {
      $_item = 1;
    }

    @$objectcount = intval($section->objectcount);
    if ($objectcount) {
      if ($_item * intval($objectcount)  >= count($section->objects)) $_item = ceil(count($section->objects) / $objectcount);

      if ($_item < 1) {
        $_item = 1;
      }

      $startitem = ($_item - 1) * $objectcount;
      $enditem = ($_item * $objectcount);
    } else {
      $startitem = 0;
      $enditem = count($section->objects);
    }
    $i = 0;
    foreach ($section->objects as $record) {
      $i++;
      if ($i <= $startitem) continue;
      if ($i > $enditem) break;
      $record['row'] = $i;
      $result .= $this->parse_records($record, $objform);
    }
    return $result;
  }


  private function getValue($name, $record = null)
  {
    $value = $name;
    $section = $this->data->getSection($this->id_content);
    if (preg_match("/(record|parent)\.([\d\w\-_]+)/ui", $name, $m)  && $record != null) {
      if ($m[2] == 'link_detail') {
        return  seMultiDir() . '/' . $this->data->getPageName() . '/' . $section->id . '/' . $record->id . '/';
      }
      if ($m[2] == 'first') $value = $this->getFirstRecord($section);
      elseif ($m[2] == 'image_prev') {
        $value = $record->image_prev;
        if ($value == '') {
          $value = $record->image;
          if (!empty($value)) {
            $img = explode('.', $value);
            @$value = $img[0] . '_prev.' . $img[1];
          }
        }
      } else {
        $field = $m[2];
        $value = $record->$field;
        $value = str_replace(array('[', ']'), array('&#91;', '&#93;'), $value);
      }
    }
    if (preg_match("/part\.([\w\d\-_]+)/ui", $name, $m)) {
      $value = strval($section->{$m[1]});
      $value = str_replace(array('[', ']'), array('&#91;', '&#93;'), $value);
    }
    return $value;
  }

  private function logic($val, $record)
  {
    $larr = array('==', '!=', '!+', '!+=', '!-', '!-=');
    $val = strtolower($val);
    if ((trim($val) == '!') || (trim($val) == '1') || (trim($val) == '!0') || (trim($val) == 'yes') || (trim($val) == 'y') || (trim($val) == '!no') || (trim($val) == '!n') || (trim($val) == 'true') || (trim($val) == '!true')) {
      return true;
    }
    if (trim($val) == '' || $val == '0' || $val == 'no' || $val == 'false') return false;

    $id_larr = 255;
    $i = 0;
    foreach ($larr as $f) {
      $preg = "/[\"\']" . $f . "[\"\']/uim";
      if (preg_match($preg, $val)  || (!preg_match("/[\'\"]/", $val) && preg_match("/" . $f . "[\"\']?/uim", $val))) {
        $id_larr = $i;
        break;
      }
      $i++;
    }

    $i = 0;
    if ($id_larr == 255) foreach ($larr as $f) {
      if (preg_match("/" . $f . "/uim", $val)) {
        $id_larr = $i;
        break;
      }
      $i++;
    }


    list($var1, $var2) = explode($f, $val);
    list($var1,) = explode('|', $this->getValue($var1, $record));
    list($var2,) = explode('|', $this->getValue($var2, $record));
    $var1 = $this->convstr($var1);
    $var2 = $this->convstr($var2);

    switch ($id_larr) {
      case 0:
        return $var1 == $var2;
      case 1:
        return $var1 != $var2;
      case 2:
        return $var1 > $var2;
      case 3:
        return $var1 >= $var2;
      case 4:
        return $var1 < $var2;
      case 5:
        return $var1 <= $var2;
    }
  }

  private function convstr($text)
  {
    if ($text == '' || is_float($text) || is_integer($text)) return $text;
    return md5(str_replace(array('"', "'"), '', $text));
  }

  private function conditions($text, $record = null)
  {
    while (preg_match("/@notif\(\s?([^\}]{2,})\}/uim", $text, $m)) {
      if (!empty($m[1])) {
        list($val) = explode(')', $m[1]);
        list(, $res) = explode('{', $m[1]);
        list($res) = explode('}', $res);

        if (!$this->logic($val, $record)) {
          $text = str_replace($m[0], $res, $text);
        } else $text = str_replace($m[0], '', $text);
      }
    }

    while (preg_match("/@if\(([^\}]{2,})\}/im", $text, $m)) {
      if (!empty($m[1])) {
        list($val) = explode(')', $m[1]);
        list(, $res) = explode('{', $m[1]);
        list($res) = explode('}', $res);

        if ($this->logic($val, $record)) $text = str_replace($m[0], $res, $text);
        else $text = str_replace($m[0], '', $text);
      }
    }


    while (preg_match("/<if:([^\>]+)>(.+?)<\/if>/usi", $text, $m)) {
      if (preg_match("/<if:([^\>]+)>/usi", $m[2])) {
        $res = $m[2] . '</if>';
        $text = str_replace($res, $this->conditions($res, $record), $text);
      } else {
        if (strpos($m[2], '<else/>')  !== false) $dl = '<else/>';
        else $dl = '<else>';

        if ($m[1] != '' && $this->logic(trim($m[1]), $record)) @list($m[2],) = explode($dl, $m[2]);
        else @list(, $m[2]) = explode($dl, $m[2]);
        $text = str_replace($m[0], $m[2], $text);
      }
    }

    return str_replace(array('&#123;', '&#125;'), array('{', '}'), $text);
  }
}
