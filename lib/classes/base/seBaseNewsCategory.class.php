<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Категорий Новостей
 * @filesource seBaseNewsCategory.class.php
 * @copyright EDGESTILE
 */
class seBaseNewsCategory extends seBase {

	protected function configure()
	{
		$this->table_name = 'news_category';
		
		$this->fields = array(
  		'id','perent_id','user_rules_id','kod','title',
  		'lang','active');

		$this->crfields = array();
	}
}	
	
?>