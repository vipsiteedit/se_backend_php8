<?php

			    			      

class MySQLCache {
   //Путь к директории кэш-файлов
   private $CachePath='/cachedb';     //Необходимо ввести полный путь

   //Имя файла с информацией о пиковой нагрузке
   private $PeakFilename='!peak.txt';

   //Флаг, при установке которого ошибки запросов выводятся на экран
   public $Debug=false;

   //Флаг, указывающий, что данные выдаются из кэша
   public $FromCache=false;

   //Дата формирования данных
   public $DataDate=0;

   //Численный код ошибки выполнения последней операции с MySQL
   public $errno=0;

   //Строка ошибки последней операции с MySQL
   public $error='';

   //Информация о пиковой нагрузке
   private $Peak=array(
      0,    //Время выполнения
      '',   //Дата выполнения
      '',   //Запрос
      '',   //Вызвавший скрипт
   );

   //Номер следующей выдаваемой строки
   public $NextRowNo=0;


   //Массив результатов запроса
   public $ResultData=array(
      'fields'=>array(),
      'data'=>array(),
      'foundrows'=>0
   );

/*
 *--------------------------------------------------------------------------
 * Конструктор
 * Принимает в качестве параметра запрос SELECT
 * и время валидности предыдущего запроса в секундах, если такой существует.
 * Возвращает логическое значение результата запроса.
 * Если запрос не SELECT, то возвращает результат выполнения этого запроса.
 * Это просто заглушка; никакие атрибуты класса при этом не затронутся.
 *--------------------------------------------------------------------------
 */
   public function MySQLCache($query, $valid=10, $link = 'db_link'){ //, $link = 'db_link'
      global $MEM, $$link;
      if ($this->CachePath==''){
         $this->CachePath=dirname(__FILE__);
      }
	  $query = trim($query);
      if (!preg_match("/^SELECT/i", $query)){
         return mysqli_query($$link, $query); //, $$link
      }
      $hashkey = md5($_SERVER['HTTP_HOST'].$query);
      $filename = getcwd().$this->CachePath.'/'.$hashkey.'.txt';
      if (!is_dir(getcwd().$this->CachePath)) mkdir(getcwd().$this->CachePath);

      if (!empty($MEM)){
        $serial = @$MEM->get($hashkey);
        if (!empty($serial)){
            $this->ResultData = unserialize($serial);
            $this->DataDate = time();
            $this->FromCache = true;
	    $this->logWork(1);
            return true;
        }
        $this->setData($query);
        if ($valid>0) {
           $MEM->set($hashkey, serialize($this->ResultData), false, $valid);
	   $this->logWork(0);
        }
        //$memcache_obj->close();
      } else {
        /* Попытка чтения кэш-файла */
        if ((@$file = fopen($filename, 'r')) && filemtime($filename)>(time()-$valid)){
            flock($file, LOCK_SH);
            $serial = file_get_contents($filename);
            $this->ResultData = unserialize($serial);
            $this->DataDate = filemtime($filename);
            $this->FromCache = true;
            fclose($file);
            return true;
        }
        if ($file){
            fclose($file);
    	    $this->delete_badfile(getcwd().$this->CachePath, $valid);
        }
        
        /* Выполнение запроса */
        $this->setData($query);

        /* Запись кэша */
        if ($valid>0) {
           $file = fopen($filename, 'w');
           flock($file, LOCK_EX);
           fwrite($file, serialize($this->ResultData));
           fclose($file);
        }
      }
      return true;
   }


   private function logWork($flag = 0)
   {
        return;
        //if (!$this->Debug) return;
        $peak_filename = getcwd().$this->CachePath.'/db_log.dat';
        if (file_exists($peak_filename)) {
    	    $loglist = file($peak_filename);
    	    $log = explode('|', join('',$loglist));
    	} else {
    	    $log = array(0,0);
    	}
           $log[$flag] = intval($log[$flag]) + 1;
           $file = fopen($peak_filename, 'w+');
	   flock($file, LOCK_EX);
           fwrite($file, join('|', $log));
           fclose($file);
   }

   private function setData($query, $link = 'db_link')
   {
        global $$link;
        $time_start = microtime(true);
        @$SQLResult = mysqli_query($$link, $query);
        $time_end = microtime(true);
        $this->DataDate = time();
        $time_exec = $time_end - $time_start;
        /* Обработка ошибки запроса */
        if (!$SQLResult){
         if ($this->Debug){
            die('Error from query "'.$query.'": '.mysqli_error($$link));
         } else {
            $this->errno=mysqli_errno($$link);
            $this->error=mysqli_error($$link);
            return false;
         }
        }
        /* Проверка пиковой нагрузки */
        $peak_filename = getcwd().$this->CachePath.'/'.$this->PeakFilename;
        if (@$file = fopen($peak_filename, 'r')){
			flock($file, LOCK_SH);
			$fdata=file($peak_filename);
			foreach ($fdata as $key=>$value){
				$this->Peak[$key]=trim($value);
			}
			$this->Peak[0] = floatval($this->Peak[0]);
			fclose($file);
        }
        if ($time_exec>$this->Peak[0]){
			$this->Peak=array(
				$time_exec,
				date('r'),
				$query,
				$_SERVER['SCRIPT_FILENAME'],
			);
		 
			$file=fopen($peak_filename, 'w');
			flock($file, LOCK_EX);
			fwrite($file, implode("\n", $this->Peak));
			fclose($file);
        }
        /* Получение названия полей */

        $nf = mysqli_num_fields($SQLResult);
        for ($i=0; $i<$nf; $i++){
			$this->ResultData['fields'][$i]=mysqli_fetch_field($SQLResult);
        }

        /* Получение данных */
        $nr = mysqli_num_rows($SQLResult);
        for ($i=0; $i<$nr; $i++){
			$this->ResultData['data'][$i] = mysqli_fetch_row($SQLResult);
        }

        $nf=mysqli_query($$link, "SELECT FOUND_ROWS();");
        if (mysqli_num_rows($nf)>0){
			list($this->ResultData['foundrows'],) =  mysqli_fetch_row($nf);
        } else $this->ResultData['foundrows']=$nr;
   } 


   /*** Количество полей в запросе ***/
   public function num_fields(){
      return sizeof($this->ResultData['fields']);
   }

   /*** Название указанной колонки результата запроса ***/
   public function field_name($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->name;
      }else{
         return false;
      }
   }

   /*** Информация о колонке из результата запроса в виде объекта ***/
   public function fetch_field($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num];
      }else{
         return false;
      }
   }

   /*** Длина указанного поля ***/
   public function field_len($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->max_length;
      }else{
         return false;
      }
   }

   /*** Тип указанного поля результата запроса ***/
   public function field_type($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->type;
      }else{
         return false;
      }
   }

   /*** Флаги указанного поля результата запроса ***/
   public function field_flags($num){
      if (!isset($this->ResultData['fields'][$num])){
         return false;
      }
      $result=array();
      if ($this->ResultData['fields'][$num]->not_null){
         $result[]='not_null';
      }
      if ($this->ResultData['fields'][$num]->primary_key){
         $result[]='primary_key';
      }
      if ($this->ResultData['fields'][$num]->unique_key){
         $result[]='unique_key';
      }
      if ($this->ResultData['fields'][$num]->multiple_key){
         $result[]='multiple_key';
      }
      if ($this->ResultData['fields'][$num]->blob){
         $result[]='blob';
      }
      if ($this->ResultData['fields'][$num]->unsigned){
         $result[]='unsigned';
      }
      if ($this->ResultData['fields'][$num]->zerofill){
         $result[]='zerofill';
      }
      if ($this->ResultData['fields'][$num]->binary){
         $result[]='binary';
      }
      if ($this->ResultData['fields'][$num]->enum){
         $result[]='enum';
      }
      if ($this->ResultData['fields'][$num]->auto_increment){
         $result[]='auto_increment';
      }
      if ($this->ResultData['fields'][$num]->timestamp){
         $result[]='timestamp';
      }
      return implode(' ', $result);
   }

   /* Количество рядов результата запроса */
   public function num_rows(){
      return sizeof($this->ResultData['data']);
   }

   /* Обрабатывает ряд результата запроса и возвращает неассоциативный массив */
   public function fetch_row(){
      if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      }
      $this->NextRowNo++;
      return $this->ResultData['data'][$this->NextRowNo-1];
   }

   /* Обрабатывает ряд результата запроса и возвращает ассоциативный массив */
   public function fetch_assoc(){
      if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      }
      for ($i=0; $i<$this->num_fields(); $i++){
         $result[$this->ResultData['fields'][$i]->name]=
            $this->ResultData['data'][$this->NextRowNo][$i];
      }
      $this->NextRowNo++;
      return $result;
   }

   public function fetch_array(){
	if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      } else
	if ($this->num_rows()>0) {
      	  for ($i=0; $i<$this->num_fields(); $i++){

       
	  $result[$this->ResultData['fields'][$i]->name]=
            $this->ResultData['data'][$this->NextRowNo][$i];
         $result[$i]=
            $this->ResultData['data'][$this->NextRowNo][$i];
       } 
      } else return false;
      $this->NextRowNo++;
      return $result;
   }

   public function found_rows(){
        return $this->ResultData['foundrows'];
   }

   public function delete_badfile($dir, $tim) {
	$d=opendir($dir);
	while(($f=readdir($d))!==false) {  
	    if ($f=='.'||$f=='..') continue;
	    if (is_link($dir.'/'.$f)) continue;
	    if (is_dir($dir.'/'.$f)) continue;
	    if ($f=='!peak.txt') continue;
	    if (is_file($dir.'/'.$f) && (filemtime($dir.'/'.$f)+$tim<time())) unlink($dir.'/'.$f);
        }
	closedir($d);
   }


}

// ##############################################################################################################

?>