<?php
function module_voting_txt($razdel, $section = null)
{
  $__module_subpage = array();
  $__data = seData::getInstance();
  $thisreq = $__data->req;
  $_page = $thisreq->page;
  $_razdel = $thisreq->razdel;
  $_sub = $thisreq->sub;
  if (strpos(dirname(__FILE__), '/lib/modules'))
    $__MDL_URL = 'lib/modules/voting_txt';
  else $__MDL_URL = 'modules/voting_txt';
  $__MDL_ROOT = dirname(__FILE__) . '/voting_txt';
  $this_url_module = $__MDL_ROOT;
  $url_module = $__MDL_URL;
  if (file_exists($__MDL_ROOT . '/php/lib.php')) {
    require_once $__MDL_ROOT . '/php/lib.php';
  }
  if (count($section->objects))
    foreach ($section->objects as $record) {
      $__record_first = $record->id;
      break;
    }
  if (file_exists($__MDL_ROOT . '/i18n/' . se_getlang() . '.xml')) {
    append_simplexml($section->language, simplexml_load_file($__MDL_ROOT . '/i18n/' . se_getlang() . '.xml'));
    foreach ($section->language as $__langitem) {
      foreach ($__langitem as $__name => $__value) {
        $__name = strval($__name);
        $__value = strval($section->traslates->$__name);
        if (!empty($__value))
          $section->language->$__name = $__value;
      }
    }
  }
  if (file_exists($__MDL_ROOT . '/php/parametrs.php')) {
    include $__MDL_ROOT . '/php/parametrs.php';
  }
  // START PHP
  $voting_text = new voting($section, $section->parametrs->param2, $section->parametrs->param1);
  $voting_text->votinglist();
  $res = $voting_text->execute();
  $colortext = $section->parametrs->param4;
  $show = false;

  if (empty($colortext)) {
    $colortext = "#000000";
  }

  if ($res) {
    $show = true;
    //Если есть IP, выводим результат
    $number = $res['numlist'];
    $summ = $res['summ'];

    foreach ($section->objects as $value) {
      $id = intval($value->id);
      if (!isset($number[$id])) {
        $number[$id] = 0;
      }
      $value->colortext = strval($value->field);
      $value->res = round($number[$id] / $summ * 100);
    }
  }

  // include content.tpl
  if ((empty($__data->req->sub) || $__data->req->razdel != $razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")) {
    if (file_exists($__MDL_ROOT . "/php/content.php"))
      include $__MDL_ROOT . "/php/content.php";
    ob_start();
    include $__MDL_ROOT . "/tpl/content.tpl";
    $__module_content['form'] =  ob_get_contents();
    ob_end_clean();
  } else $__module_content['form'] = "";
  //Final PHP
  $__module_content['form'] = str_replace('[records]', $objlist, $__module_content['form']);

  return  array(
    'content' => $__module_content,
    'subpage' => $__module_subpage
  );
}
