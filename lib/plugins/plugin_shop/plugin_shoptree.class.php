<?php

/**
 * @filesource edgePartnerTree
 * @copyright EDGESTILE
 */

class plugin_shopTree
{
  private $mainLeft;
  private $mainRight;
  private $mainLevel;
  public $mainId;
  private $main_id = 0;

  public function __construct()
  {
    //$this->main_id = $group_id;
    //if (file_exists(SE_ROOT.'/system/logs/'))
    $sql = "CREATE TABLE IF NOT EXISTS `shop_group_tree` (
    `group_id` int(10) unsigned NOT NULL,
    `left` int(10) unsigned NOT NULL,
    `right` int(10) unsigned NOT NULL,
    `level` int(10) unsigned NOT NULL,
    UNIQUE KEY `group_id` (`group_id`),
    KEY `left` (`left`),
    KEY `right` (`right`),
    KEY `level` (`level`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
    se_db_query($sql);
  }


  public function parseGroups()
  {
    se_db_query("SET FOREIGN_KEY_CHECKS=0;");
    se_db_query("TRUNCATE TABLE `shop_group_tree`");
    se_db_query("SET FOREIGN_KEY_CHECKS=1;");
    $level = 1;
    $this->setGroups(0);
    while ($level > 0) {
      $qr = se_db_query("SELECT group_id FROM shop_group_tree
          WHERE level={$level}");
      if (!empty($qr) && se_db_num_rows($qr) > 0) {
        $fl = 0;
        while ($line = se_db_fetch_row($qr)) {
          $parent_id = intval($line[0]);
          $this->setGroups($parent_id);
          $fl++;
        }
        $level++;
        if (!$fl) break;
      } else {
        break;
      }
    }
  }

  public function setGroups($parent_id = 0)
  {
    if (empty($parent_id)) {
      $where = "`upid`=0 OR `upid` IS NULL";
    } else {
      $where = "`upid`={$parent_id}";
    }
    $q = se_db_query("SELECT `id` FROM shop_group WHERE $where");
    if (!empty($q)) {
      while ($res = se_db_fetch_row($q)) {
        if (empty($res[0])) continue;
        $arr[] = $res[0];
      }
      if (!empty($arr)) {
        $this->setTree(intval($parent_id), implode(',', $arr));
      }
    }
  }

  public function setTree($parent_id, $group_id)
  {
    $groups = explode(',', $group_id);
    //if (se_db_is_item('shop_group_tree', "`group_id` IN ({$group_id})")) {
    //   mysql_query("DELETE FROM shop_group_tree WHERE `group_id` IN ({$group_id})");
    //}

    $left = $right = $level = 0;
    if ($parent_id) {
      list($left, $right, $level) = $this->getMain($parent_id);
    } //if (empty($left))
    //  return;


    $cnt = count($groups);
    $ac = $cnt * 2;

    if (!empty($level)) {
      se_db_query("update `shop_group_tree` set `right`=`right`+{$ac} where `right`>=$right");
      se_db_query("update `shop_group_tree` set `left`=`left`+{$ac} where `left`>$right");
      $level++;
    } else {
      $qr = se_db_query("SELECT MAX(`right`) FROM `shop_group_tree` WHERE `level`='1' LIMIT 1");
      list($right) = se_db_fetch_row($qr);
      $right = $right + 1;
      $level = 1;
    }
    $sql = '';
    foreach ($groups as $id) {
      if (!$id) continue;
      if (!empty($sql)) {
        $sql .= ',';
        $left = $right + 1;
        $right += 2;
      } else {
        $left = $right;
        $right++;
      }
      $sql .= "({$id}, {$left}, {$right}, {$level})";
    }
    if ($sql) {
      $sql = "INSERT INTO `shop_group_tree` (`group_id`,`left`,`right`,`level`) VALUES " . $sql . ';';
      se_db_query($sql);
      echo se_db_error();
    }
  }

  public function getGroups($startLevel = 0, $endLevel = 0, $group_id = false)
  {
    $qr = se_db_query($this->getTreeSQL($startLevel, $endLevel, $group_id));

    $arr = array();
    if (!empty($qr))
      while ($line = se_db_fetch_row($qr)) {
        $arr[] = $line[0];
      }
    return $arr;
  }


  public function getTreeSQL($startLevel = 0, $endLevel = 0, $group_id = false)
  {
    if ($group_id !== false) $this->main_id = $group_id;
    list($left, $right, $level) = $this->getMain($this->main_id);
    $slevel = $level + $startLevel;
    $elevel = $level + $endLevel;

    if ($endLevel == -1) {
      $wherelevel = '';
    } else {
      $wherelevel = " AND tree.`level`<='$elevel'";
    }

    return "(SELECT tree.`group_id` FROM `shop_group_tree` tree 
			WHERE tree.`left`>='$left' 
			AND tree.`right`<='$right' 
			AND tree.`level`>='$slevel'
			$wherelevel)";
  }

  public function getTreeArray($startLevel, $endLevel, $group_id = false)
  {
    if ($group_id !== false) $this->main_id = $group_id;
    $qr = se_db_query($this->getTreeSQL($startLevel, $endLevel, $this->main_id));
    $result = array();
    if (!empty($qr))
      while ($line = se_db_fetch_row($qr)) {
        $result[] = $line[0];
      }
    return $result;
  }

  public function getMain($group_id = false)
  {
    if ($group_id !== false) $this->main_id = $group_id;
    list($left, $right, $level) = se_db_fields_item('shop_group_tree', "`group_id`={$this->main_id}", "`left`,`right`,`level`");
    return array($left, $right, $level);
  }


  public function pathTree($parent_id = 0, $findId)
  {
    list($left, $right, $level) =  $this->getMain($findId);

    $sql = "SELECT `company_id` FROM `shop_group_tree` 
			WHERE `left`<='$left' 
			AND `right`>='$right' ORDER BY level desc";
    $qr = se_db_query($sql);
    $arr = array();
    if (!empty($qr))
      while ($line = se_db_fetch_row($qr)) {
        if ($parent_id == $line[0]) break;
        $arr[] = $line[0];
      }
    return $arr;
  }
}
