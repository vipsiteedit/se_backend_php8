<?php



$types = array();
$showtypes = false;
if ($section->parametrs->param9!='N' || $section->parametrs->param10!='N' || $section->parametrs->param11!='N') {
$showtypes = true;
$types[] = array(
        'name'=>'',
        'title'=>$section->language->lang006
    );
if ($section->parametrs->param9!='N') $types[] = array(
        'name'=>'name',
        'title'=>$section->language->lang002
    );
if ($section->parametrs->param10!='N') $types[] = array(
        'name'=>'article',
        'title'=>$section->language->lang001
    );
if ($section->parametrs->param11!='N') $types[] = array(
        'name'=>'text+note',
        'title'=>$section->language->lang003
    );
$__data->setList($section,'types',$types);
}
?>