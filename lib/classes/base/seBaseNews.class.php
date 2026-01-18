<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Новостей
 * @filesource seBaseNews.class.php
 * @copyright EDGESTILE
 */
class seBaseNews extends seBase {

	protected function configure()
	{
		$this->table_name = 'news';
		
		$this->fields = array(
  		'id','id_category','date','pub_date','title',
  		'short_txt','text','img','active');

		$this->crfields = array(
  			'category_id'=>'id_category', 'date_news'=>'date', 'date_pub'=>'pub_date',
  			'short_text'=>'short_txt', 'full_text'=>'text','picture'=>'img');
	}

	public function findlist($findtext = '')
	{
		$thisdate = time() + 86400;
		$newsWhere = " AND `pub_date`<='$thisdate'";
		if (is_numeric($findtext))
		{
			$this->where = "`id` = '$findtext' $newsWhere";
		}
		else 
		if (!empty($findtext))
			$this->where = $findtext . ' ' . $newsWhere;
		else $this->where = '1 '. $newsWhere;
		return $this;
	}

}	
	
?>