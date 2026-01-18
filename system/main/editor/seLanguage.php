<?php
//require_once SE_ROOT . 'lib/yaml/seYaml.class.php';
function getTextLanguage($word, $key='ed_'){
    $lang = se_getLang();
    $ymlfile = SE_CORE . 'editor/i18n/' . $lang .'.yml';
    if (file_exists( $ymlfile ))
    {
        $ymlres = seYAML::Load( $ymlfile );
        foreach($ymlres as $classname=>$mess);
    }
    if (!empty($mess[$key.$word]))
	return $mess[$key.$word];
    else return $word;
}