<?php

$SE_BANK_REKV_LIST="";
$table = new seTable();
/*
se_db_connect();
 $sql="SELECT
  `user_rekv`.value,
  `user_rekv_type`.title,
  `user_rekv_type`.size,
  `user_rekv_type`.code
FROM
  `user_rekv_type`
  LEFT OUTER JOIN `user_rekv` ON (`user_rekv`.rekv_code = `user_rekv_type`.code)
  AND (`user_rekv`.id_author = '$ID_AUTHOR')
WHERE
  (`user_rekv_type`.lang = '$lang')";
$query=se_db_query($sql);

$even=false;

//foreach($query as $field) {//
while ($field=se_db_fetch_array($query)) {
   if ($field['size']<30)
     $fieldsize="size=\"".$field['size']; else $fieldsize=" style=\"width:100%\"";
   if ($even) $tr="id=tableRowEven"; else $tr="id=tableRowOdd";
   $even=(!$even);
     
$SE_BANK_REKV_LIST.="<tr class=tableRow $tr><TD class=\"titl\">".$field['title'].
"</TD><TD width=1%>&nbsp</TD><TD><INPUT type=\"text\" maxlength=\"".$field['size'].
"\" id=\"inp\" $fieldsize\" name=\"".$field['code']."\"
value=\"".$field['value']."\"></TD></TR>";
}

//*/        

$table->from("user_rekv_type","urt");
$table->select("ur.value,urt.title,urt.size,urt.code");
$table->leftjoin("user_rekv ur","(ur.rekv_code = urt.code) AND (ur.id_author = '$id_author')");
$table->addWhere("urt.lang = '?'",$lang);
$query = $table->getList();
// print_r($query);
//$even=false;

//* 
foreach($query as $field) {
    $SE_BANK_REKV_LIST.="<div class=\"obj\"><label>".$field['title']."</label><div><input type=\"text\" maxlength=\"".$field['size']."\" name=\"".$field['code']."\" value=\"".$field['value']."\"></div></div>";
}
//*/

$table->from("user_urid","uu");
$table->select();
$table->addWhere("`id`='?'",$id_author);
// $result = se_db_fields_item("user_urid","`id_author`='$ID_AUTHOR'","*");
 $result = $table->fetchOne();
 $_plant = $result['company'];//se_db_output($result['company']);
 $_director = $result['director'];//se_db_output($result['director']);
 $_uradres = $result['uradres'];//se_db_output($result['uradres']);
 $_tel = $result['tel'];//se_db_output($result['tel']);
 $_fax = $result['fax'];//se_db_output($result['fax']);

 unset($table);
?>
