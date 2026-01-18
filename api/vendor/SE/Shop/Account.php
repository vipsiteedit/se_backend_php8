<?php

namespace SE\Shop;

use SE\DB;
use SE\Exception;

// аккаунт
class Account extends Base
{
  protected $tableName = "accounts";
  protected $sortOrder = "asc";

  // получить
  public function fetch()
  {
    $items = array();
    $project = str_replace(".e-stile.ru", "", HOSTNAME);
    $items[] = array(
      "alias" => $project, "project" => $project, "login" => $_SESSION["login"],
      "hash" => $_SESSION["hash"], "isMain" => true
    );
    $this->result["items"] = $items;
    $this->result["count"] = count($items);
  }
}
