<?php

/**
 * @author Sergey Schelkonogov
 * @copyright 2012
 */

class plugin_shopgroup
{
	private static $instance = null;
	private $groups = '';
	private $group_id = 0;
	public $footertext = '';
	public $title = '';
	public $keywords = '';
	public $description = '';
	private $lang = "rus";

	/** Конструктор
	 *   @string  page           текущая страница
	 *   @string  group_name     код группы, переданное принудительно
	 *   @string  base_group     стартовая страница
	 *   @boolean oldformat      старый или новый формат
	 **/
	public function __construct($basegroup, $block_group = false)
	{
		$basegroup = (!getRequest('cat') || $block_group) ? $basegroup : getRequest('cat');
		$this->lang = se_getLang();
		if (isRequest('show')) {
			$goodcode = getRequest('show');
			$group = new seTable('shop_price');
			$group->select('id_group');
			$group->where("code='?'", $goodcode);
			$group->andwhere("`lang`='?'", $this->lang);
			$group->fetchOne();
			$this->group_id = $group->id_group;
		} else {
			if ($basegroup) {
				$group = $this->getThisGroup($basegroup);
				$this->group_id = $group['id'];
			} else {
				$this->group_id = 0;
			}
		}
	}

	public function getId()
	{
		return $this->group_id;
	}

	public function checkUrl()
	{
		global $SE_REQUEST_NAME;
		$url = str_replace('/' . seMultiDir(), '',  $_SERVER['REQUEST_URI']);
		if (substr($url, -1, 1) == URL_END) $url = substr($url, 0, -1);
		$u = explode('/', $url);
		$group = new seTable('shop_group');
		$group->select('id');
		$group->where("code_gr='?'", $url);
		$group->andwhere("`lang`='?'", $this->lang);
		$result = $group->id;
		if ($result) {
			$SE_REQUEST_NAME[$u[0]] = 1;
		}
		return $result;
	}

	private function getThisGroup($groupname)
	{
		$group = new seTable('shop_group');
		$group->select('id, footertext, title, keywords, description');
		$group->where("code_gr='?'", $groupname);
		$group->andwhere("`lang`='?'", $this->lang);
		$groups = $group->fetchOne();
		$this->footertext = $group->footertext;
		$this->title = $group->title;
		$this->keywords = $group->keywords;
		$this->description = $group->description;
		return $groups;
	}

	public function getGroupId($group_id, $first = false)
	{

		$list = array();
		if ($first && is_numeric($group_id) && intval($group_id)) $list[] = $group_id;
		$shgroup = new seShopGroup();
		$shgroup->select('GROUP_CONCAT(id) as idlist');

		if (!$group_id) {
			$shgroup->where("`upid` IS NULL");
		} else {
			$where = " OR sg.id IN (SELECT group_id FROM shop_crossgroup WHERE id IN (?))";
			$shgroup->where("upid IN (?){$where}", $group_id);
			$shgroup->andwhere("active='Y'");
		}
		$shgroup->fetchOne();
		$idlist = $shgroup->idlist;
		if (!empty($idlist))
			$list = array_merge($list, $this->getGroupId($idlist), explode(',', $idlist));
		return $list;
	}


	public static function getInstance($basegroup = '')
	{
		if (self::$instance === null) {
			self::$instance = new self($basegroup);
		}
		return self::$instance;
	}

	public function getGroups()
	{
		if (!$this->groups) {
			$this->groups = join(',', $this->getGroupId($this->group_id, true));
		}
		return $this->groups;
	}
}
