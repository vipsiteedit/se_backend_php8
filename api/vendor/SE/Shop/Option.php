<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

class Option extends Base
{
    protected $tableName = "shop_option";
    protected $sortBy = "sort";
    protected $sortOrder = "asc";

    protected function getSettingsFetch()
    {
        $result["select"] = "so.*, sog.name AS groupName";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_option_group sog',
            "condition" => 'so.id_group = sog.id'
        );
        $result["joins"] = $joins;
        return $result;
    }

    public function fetch()
    {
        parent::fetch();

        foreach ($this->result['items'] as &$item) {
            if ($item['image']) {
                if (strpos($item['image'], "://") === false) {
                    $item['imageUrl'] = '//' . $this->hostname . "/images/rus/options/" . $item['image'];
                    $item['imageUrlPreview'] = "//{$this->hostname}/lib/image.php?size=64&img=images/rus/options/" . $item['image'];
                } else {
                    $item['imageUrl'] = $item['image'];
                    $item['imageUrlPreview'] = $item['image'];
                }
            }
        }
    }

    public function save()
    {
        if (!$this->input['idGroup']) $this->input['idGroup'] = null;
        if (!$this->input["code"])
            $this->input["code"] = se_translite_url($this->input["groupName"] . ' ' . $this->input["name"]);
        parent::save();
    }
}
