<?php
/**
*  lib_database.php,v 8.12 2006/02/04
*
*  EDGESTILE SiteEdit,
*  http://www.edgestile.com
*
*  Copyright (c) 2006 EDGESTILE
**/



  function se_db_connect($link = 'db_link') {
    global $$link, $CONFIG;
      include SE_SYS_ROOT . '/config_db.php';
      $server = $CONFIG['PgHostName'];
      $username=$CONFIG['PgDBUserName'];
      $password=$CONFIG['PgDBPassword'];
      $database=$CONFIG['PgDBName'];
      $connstr="host=$server port=5432 dbname=$database user=$username password=$password";
      $$link = pg_connect($connstr);

    return $$link;
  }

  function se_db_close($link = 'db_link') {
    global $$link;

    return pg_close($$link);
  }



  // ##################### se_db_query
  function se_db_query($sql,$link = 'db_link') {
    global $$link;
    $flcount=false;
	$sql=str_replace('`','"',$sql);
	if (strpos($sql,'CREATE TABLE')!==false) {
	  $sql=str_replace(' varchar',' character varying',$sql);
	  $sql=preg_replace("/ int\((.+?)\)/m",' integer',$sql);
	  $sql=preg_replace("/enum\((.+?)\)/m",' character(1)',$sql);
	}

	if (strpos($sql,'SQL_CALC_FOUND_ROWS')) {
	   $sql=str_replace('SQL_CALC_FOUND_ROWS','',$sql);
	   $flcount=true;
	}

    if (strpos($sql,'SHOW COLUMNS FROM')!==false) {
	  $sql=str_replace('"',"'",$sql);
	  $sql=str_replace('SHOW COLUMNS FROM','SELECT column_name FROM information_schema.columns WHERE table_name =',$sql);
	}

    if (preg_match("/DELETE FROM(.+?)WHERE([\w\W\S\s\d]{1,})/im",$sql,$res_math)) {
	  $table=trim(str_replace('"','',@$res_math[1]));
	  if ($table!='session') {
    	    $where=@$res_math[2];
	    $sqq="select id from $table where $where";
	    $res=pg_query($$link,"select id from $table where $where");
	  }
    }
	      $result = pg_query($$link,$sql); //,$$link
	      
	      

          if ($flcount) {
		      list($sql)=explode('LIMIT',$sql);
		      $posfrom=strpos($sql,'FROM');
			   $newsql='SELECT count(a.*) '.substr($sql,$posfrom, strlen($sql)-$posfrom).' GROUP BY id';

			  $rowcount=pg_fetch_array(pg_query($$link,$newsql));

		  }
		  if (!$result) echo $sql;


		if ($result!==false)
		{
		  return array($result,0);
		}
		else
		{
		  //echo "<!--\n $sql\n -->"; 
		  return $result;
		}
  }


  function se_db_fetch_array(&$query,$typres=PGSQL_BOTH){
   if ($query==false) return false;
   return @pg_fetch_array($query[0],($query[1]++),$typres);
  }

  function se_db_num_rows(&$query){
    return (@pg_num_rows($query[0]));
  }

  function se_db_fetch_row(&$query){
	return pg_fetch_row($query[0],($query[1]++));
  }

  function se_db_fetch_assoc(&$query){
	if (!empty($query[0]))
		return @pg_fetch_array($query[0], ($query[1]++), PGSQL_ASSOC);
  }

  function se_db_result(&$query,$row,$field){
	return pg_fetch_result($query[0],$row,$field);
  }


  function se_db_found_rows(&$query){
	return pg_result(pg_query("SELECT FOUND_ROWS();"),0,0);
  }


  function se_db_data_seek(&$query, $row){
	return $query[1]=$row;
  }


// ####################### function  SE

  function se_db_delete($table,$where = '',$link = 'db_link') {
    global $$link;
	//$res=pg_query($$link,"select id from $table where $where;");
      if (pg_query($$link,"DELETE FROM $table WHERE $where")){
	//if (!empty($res))
	//while (@$id_rec=pg_fetch_array($res)) log_update($table,$id_rec[0],'d');
        return true;
      } else return false;
  }

  function se_db_edit($action='INSERT',$table,$where,$fields,$datas,$link = 'db_link') {
    $action=strtolower($action);
    $fields=explode(",",$fields);
    $datas=explode(",",$datas);
    $data=array();
    for ($i=0; $i<count($fields);$i++)
     $data[$fields[$i]]=$datas[$i];

    se_db_perform($table, $data, $action , $where,$link);
  }

  function se_db_input($string, $link = 'db_link') {
    global $$link;
    //return $string;
    $string = htmlspecialchars($string, ENT_QUOTES);
    if (function_exists('pg_real_escape_string')) {
      return pg_real_escape_string($string, $$link);
    } elseif (function_exists('pg_escape_string')) {
      return pg_escape_string($string);
    }
    return $string;
  }

  function se_db_output($string) {
    return htmlspecialchars(stripslashes($string));
  }

  function se_db_perform($table, $data, $action = 'insert', $where = '',$link = 'db_link') {
    global $$link;
    
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      
      foreach ($data as $columns => $value) {
      	$columns = preg_replace("/[`\"]/", '', $columns);
        $query .= '"'.$columns . '", ';
      }
      
      $query = substr($query, 0, -2) . ') values (';
      
      foreach ($data as $value) {
        if (empty($value)) {
    	    $query .= 'null, ';
        } else  
   	    if (strtolower($value) == 'now()')
	    {
        	$query .= 'now(), ';
            } else {
        	if (is_string($value) || is_object($value)) {
        	   $query .= '\'' . se_db_input($value) . '\', ';
        	} else {        	
		    if (is_int($value)) {
        		$query .= $value .', ';
    		    } else {
        		$query .= '\'' .$value .'\', ';
		    }
		}    	    
    	    }
      }
      
      
      $query = substr($query, 0, -2) . ')';
      
      //echo "<!-- $query -->";      
      $result = se_db_query($query, $link);

      //$res=se_db_fetch_array(se_db_query("SELECT LAST_INSERT_ID()",$link));
	  if ($result)
	  {
	  	return  se_db_insert_id($table, $link);
	  }
      return $result;
    
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      //while (list($columns, $value) = each($data)) {
      
      foreach ($data as $columns => $value) {
      	$columns = preg_replace("/[`\"]/", '', $columns);
        if (empty($value)) {
    	    $query .= $columns .' = null, ';
        } else {
        
	//switch (strtolower($value)) {
        if (strtolower($value) == 'now()')
		{
	 // case 'now()':
            $query .= $columns . ' = now(), ';
        //    break;
        //  default:
        } else {
	    if (is_string($value) || is_object($value)) {
        	$query .= $columns . ' = \'' . se_db_input($value) . '\', ';
            } else
            if (is_int($value)) {    
        	$query .= $columns .' = '. $value .', ';
            } else
        	$query .= $columns .' =\'' .$value .'\', ';
            
        }
        }
	 // log_update($table,$id_rec[0],'d');
	  //updates_wlog("u",$table,$where);
	  }
        $query = substr($query, 0, -2) . ' where ' . $where;
        $result= se_db_query($query,$link);

	//$res=se_db_query("select id from $table where $where;",$$link);
	//if (!empty($res))
	//while (@$id_rec=se_db_fetch_array($res)) log_update($table,$id_rec[0],'u');
      //return $result;
    }

    return $result;//pg_query($query,$$link);
  }

  function se_db_is_item($table,$where,$link = 'db_link') {
    global $$link;
    return (@se_db_num_rows(se_db_query("SELECT * FROM $table WHERE $where",$link))>0);
  }

  function se_db_insert_id($table,$link = 'db_link') {
    global $$link;
  	  	$seqName = $table . '_id_seq';
		$query = se_db_query("SELECT CURRVAL('" . $seqName . "')");
		if (!empty($query)) list($result,) = se_db_fetch_row($query);
		return $result;
  }

  function se_db_next_id($table, $filed = null)
  {
  	  	$seqName = $table . '_id_seq';
		$query = se_db_query("SELECT CURRVAL('" . $seqName . "')");
		if (!empty($query)) list($result,) = se_db_fetch_row($query);
		return $result;
  }


 function se_db_fields($razdel,$table,$where,$select,$fieldreplace='',$link = 'db_link')
 {
  global $obj,$object_extern,$table_field_error,$tablevalues,$$link;
  $tablevalues="";
  $fieldrepl=explode(";",$fieldreplace);
  if($result=se_db_query("select $select FROM $table WHERE $where",$link))
  {
      $count=se_db_num_rows($result);
      for ($nn=0; $nn<$count; $nn++){
        $tabl=se_db_fetch_array($result);
        $j=0;
        while (@$linerepl=$fieldrepl[$j]){
           $linerepl=explode(",",$linerepl);
           if (@$tabl[$linerepl[0]]==@$linerepl[1])
             @$tabl[$linerepl[0]]=$linerepl[2];
           $j++;
        };
      $tablevalues=$tabl;
      $obj[$razdel][$nn]=replace_obj(0,$tabl,$razdel,$nn,"","");
      }
  } else $table_field_error="Ошибка чтения таблицы";

  $object_extern[$razdel]=true;
}

function se_db_fields_list($table,$where,$select,$maska,$itemtext="",$itemfield=-1,$fieldreplace="",$styles="",$link = 'db_link')
{
  global $$link,$table_field_error,$itemselect;
  $tabvalue="";
  if ($where==1) $where='(true)';
  $fieldrepl=explode(";",$fieldreplace);
  $itemselect=-1;
  $mstyles = array();
  if (!empty($styles)) $mstyles = explode(",", $styles);
  $cnstyle = count($mstyles);

  $sql="select $select FROM $table WHERE $where";
  $result=se_db_query($sql,$link);


  // конструкция [@select:value] / [@select] в аргументе $maska
  // подставляет значение value
  if (preg_match("/\[@select:?([\w\d]+)?\]/i",$maska,$sel))
  	 $select = $sel[1];

  // в зависимости от номера итерации выводится подставляется значение вместо
  // конструкции [@dev,value1,value2,..,valueN] в маске
  // например, [@dev,Class1,Class2,Class3] - если текущая итерация кратна 3, то
  // подставится элемент из массива [Class1,Class2,Class3] с индексом итерация/кол-во элементов массива
  if (preg_match("/\[@dev([,\w+\d+]{2,})\]/i",$maska,$dev))
  	$dev=explode(',',@$dev[1]);

  $coldev=count($dev)-1;
  if ($coldev<2) $coldev=2;

  if(!empty($result))
  {
  $nn=0;
     $cn = 0; $nrow=1;
    while ($tabl=se_db_fetch_array($result)){
        $j=0;

		if (!empty($fieldreplace))
	      while (@$linerepl=$fieldrepl[$j]){
           $linerepl=explode(",",$linerepl);
           @$tabl[$linerepl[0]]=str_replace($linerepl[1],$linerepl[2],$tabl[$linerepl[0]]);
           $j++;
        };

      $fmas=$maska;
      if ($cn == $cnstyle) $cn=0;
      @$getstyle = $mstyles[$cn];
      $cn++;

     if ((@$itemfield>=0) && ($itemtext==@$tabl[$itemfield])) {
     	if (!$select)$select = "SELECTED";

	    $itemselect=@$tabl[$itemfield];
      } else $select="";
      //$i=0;

	  foreach($tabl as $k=>$dline){
          $fmas=str_replace("[@col".$k."]",$dline,$fmas);
      };

	  //echo se_db_output($fmas);
      $fmas=str_replace("[@row]",$nn+1,$fmas);

      $fmas = preg_replace("/(\[@select:?([\w\d]+)?\])/i",$select,$fmas);
      $fmas = str_replace("[@style]", $getstyle, $fmas);
      if (preg_match("/\[@dev([,\w+\d+]{2,})\]/i",$maska,$devm))
      	 $fmas = str_replace($devm[0],$dev[$nrow],$fmas);
      	 //echo $devm[0];
      $tabvalue.=preg_replace("/(\[@col)\d{1,2}\]/m","",$fmas)."\r";
      unset($fmas);
      $nrow++;
      if ($nrow>$coldev) $nrow=1;
      $nn++;
     };
  } else $table_field_error="Ошибка чтения таблицы";

   return $tabvalue;
}


function se_db_fields_item($table,$where,$select,$link = 'db_link')
{
  global $$link,$table_field_error;
  $itemval="";
  $varsel=explode(",",$select);
  $table=htmlspecialchars($table);
  $select=htmlspecialchars($select);
  if ($where=='1') $where='true';
  if($result=se_db_query("select $select FROM $table WHERE $where LIMIT 1",$link)){
     @$itemval=se_db_fetch_array($result);
     if ((count($varsel)==1) && ($select!="*")) @$itemval=$itemval[0];
  } else $table_field_error="Ошибка чтения таблицы";
  return ($itemval);
}


function se_db_limit($offset = 0, $limit)
{
	$limit = ' LIMIT '. $limit;
	if ($offset >0 ) $limit .= ' OFFSET '. $offset;
	return $limit;
}


function log_update($table,$id_rec=0,$typech='i',$id_clman=0,$id_log=0){

}

function parstobytia($userfiletmp){
$dmp=array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
  $res='';
  //echo $userfiletmp;
  $sc=filesize($userfiletmp);
  $m=fread(fopen($userfiletmp, "rb"), filesize($userfiletmp));
  for($i = 0; $i < $sc; $i ++) {
    $ch = ord(@$m[$i]);
    $h = floor($ch/16);
    $res .= $dmp[$h] . $dmp[($ch-$h*16)];
  }
  return $res;
}


function bytiatobyte($bytestr){
$dmp = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);
  $l = strlen($bytestr);
  $res = array();
  for ($i = 0; $i < ($l - 1); $i = $i + 2) {
   $s1 = substr($bytestr, $i, 1);
   $s2 = substr($bytestr, $i + 1, 1);
   $res[] = chr($dmp[$s1] * 16 + $dmp[$s2]);
  }
  return join('',$res);
}


?>