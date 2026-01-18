<?php
function module_tabs($razdel, $section = null)
{
	$__module_subpage = array();
	$__data = seData::getInstance();
	$thisreq = $__data->req;
	$_page = $thisreq->page;
	$_razdel = $thisreq->razdel;
	$_sub = $thisreq->sub;
	if (strpos(dirname(__FILE__), '/lib/modules'))
		$__MDL_URL = 'lib/modules/tabs';
	else $__MDL_URL = 'modules/tabs';
	$__MDL_ROOT = dirname(__FILE__) . '/tabs';
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
		$__langlist = simplexml_load_file($__MDL_ROOT . '/i18n/' . se_getlang() . '.xml');
		append_simplexml($section->language, $__langlist);
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
	// Получаем номер раздела к которому был обращен запрос


	// Получаем номер записи которую хочет получить пользователь
	// и попутно определяем запрос был отправлен скриптом или нет
	if (isRequest('obj') && $razdel == $_razdel) {
		// Если запрос отправлен не скриптом
		$obj = getRequest('obj', 1);
	} else {
		// Если запрос отправлен скриптом
		if (isRequest('load')) {
			$obj = getRequest('load', 1);
		}
	}

	// Если запроса вообще не происходило и переменная не найдена назначаем ей значение по умолчанию
	if (!$obj) $obj = 0;


	if (isRequest('load') && $razdel == $_razdel) {
		$_SESSION['last_select_object']['r' . $razdel] = $obj;
	} else {
		// Модуль определяет запрос пришел для него или нет
		if ($razdel == $_razdel) {
			// Если запрос пишел для этого модуля, то записываем номер записи которую выбрал пользователь
			if ($obj) $_SESSION['last_select_object']['r' . $razdel] = $obj;
		} else {
			// Если запрос пришел не для этого молуля, то извлекаем номер записи которую пользователь выбрал в предыдущий раз
			$obj = $_SESSION['last_select_object']['r' . $razdel];
		}
	}


	if (isRequest('load') && $_razdel == $razdel) {
		foreach ($section->objects as $record)
			if (($record->id) == $obj) {
				$record->note = tabs_parser_link($record->note);
				$record->text = tabs_parser_link($record->text);
				//$record->note = replace_link($record->note);
				$record->image_prev = "/" . SE_DIR . $record->image_prev;
				break;
			}
		include $__MDL_ROOT . "/tpl/subpage_1.tpl";
		exit();
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
	// include show.tpl
	ob_start();
	if ($razdel == $_razdel && $__data->req->object) {
		$record = $__data->getObject($section, $__data->req->object);
		include $__MDL_ROOT . "/tpl/show.tpl";
	}
	$__module_content['show'] =  ob_get_contents();
	ob_end_clean();
	//BeginSubPage4
	$__module_subpage['4']['admin'] = "";
	$__module_subpage['4']['group'] = 0;
	$__module_subpage['4']['form'] =  '';
	if (
		$razdel == $__data->req->razdel && !empty($__data->req->sub)
		&& $__data->req->sub == '4' && file_exists($__MDL_ROOT . "/tpl/subpage_4.tpl")
	) {
		include $__MDL_ROOT . "/php/subpage_4.php";
		ob_start();
		include $__MDL_ROOT . "/tpl/subpage_4.tpl";
		$__module_subpage['4']['form'] =  ob_get_contents();
		ob_end_clean();
	} //EndSubPage4
	//BeginSubPage1
	$__module_subpage['1']['admin'] = "";
	$__module_subpage['1']['group'] = 0;
	$__module_subpage['1']['form'] =  '';
	if (
		$razdel == $__data->req->razdel && !empty($__data->req->sub)
		&& $__data->req->sub == '1' && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")
	) {
		include $__MDL_ROOT . "/php/subpage_1.php";
		ob_start();
		include $__MDL_ROOT . "/tpl/subpage_1.tpl";
		$__module_subpage['1']['form'] =  ob_get_contents();
		ob_end_clean();
	} //EndSubPage1
	//BeginSubPage2
	$__module_subpage['2']['admin'] = "";
	$__module_subpage['2']['group'] = 0;
	$__module_subpage['2']['form'] =  '';
	if (
		$razdel == $__data->req->razdel && !empty($__data->req->sub)
		&& $__data->req->sub == '2' && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")
	) {
		include $__MDL_ROOT . "/php/subpage_2.php";
		ob_start();
		include $__MDL_ROOT . "/tpl/subpage_2.tpl";
		$__module_subpage['2']['form'] =  ob_get_contents();
		ob_end_clean();
	} //EndSubPage2
	//BeginSubPage3
	$__module_subpage['3']['admin'] = "";
	$__module_subpage['3']['group'] = 0;
	$__module_subpage['3']['form'] =  '';
	if (
		$razdel == $__data->req->razdel && !empty($__data->req->sub)
		&& $__data->req->sub == '3' && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")
	) {
		include $__MDL_ROOT . "/php/subpage_3.php";
		ob_start();
		include $__MDL_ROOT . "/tpl/subpage_3.tpl";
		$__module_subpage['3']['form'] =  ob_get_contents();
		ob_end_clean();
	} //EndSubPage3
	//BeginSubPage5
	$__module_subpage['5']['admin'] = "";
	$__module_subpage['5']['group'] = 0;
	$__module_subpage['5']['form'] =  '';
	if (
		$razdel == $__data->req->razdel && !empty($__data->req->sub)
		&& $__data->req->sub == '5' && file_exists($__MDL_ROOT . "/tpl/subpage_5.tpl")
	) {
		include $__MDL_ROOT . "/php/subpage_5.php";
		ob_start();
		include $__MDL_ROOT . "/tpl/subpage_5.tpl";
		$__module_subpage['5']['form'] =  ob_get_contents();
		ob_end_clean();
	} //EndSubPage5
	return  array(
		'content' => $__module_content,
		'subpage' => $__module_subpage
	);
}
