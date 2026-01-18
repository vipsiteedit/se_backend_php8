<?php

namespace SE\Shop;

use SE\DB;
use SE\Exception;

// особенность?
class Feature extends Base
{
	protected $tableName = "shop_feature";
	protected $sortBy = "sort";
	protected $sortOrder = "asc";

	public function merge()
	{
		try {
			DB::beginTransaction();
			if (!isset($this->input["ids"]))
				return;
			$ids = $this->input["ids"];
			if (count($ids) < 2) return;
			$baseId = intval($ids[0]);

			$sf = new DB('shop_feature');
			$sf->select('id, type');
			$sf->where('id IN (?)', join(',', $ids));
			$types = array();
			foreach ($sf->getList() as $it) {
				$types[$it['id']] = $it['type'];
			}
			if ($types[$baseId] == 'list' || $types[$baseId] == 'colorlist') {
				$bList = array();
				$sfvl = new DB('shop_feature_value_list', 'sfvl');
				$sfvl->select('sfvl.id, sfvl.value');
				$sfvl->where('sfvl.id_feature=?', $baseId);
				foreach ($sfvl->getList() as $item) {
					$bList[$item['value']] = $item['id'];
				}
				unset($sfvl);
				foreach ($ids as $i => $id) {
					$id = intval($id);
					if ($i < 1) continue;
					if ($types[$baseId] !== $types[$id]) continue;

					// Смотрим, есть ли у данного параметра клоны
					$sfvl = new DB('shop_feature_value_list', 'sfvl');
					$sfvl->select('id, value');
					$sfvl->where('id_feature=?', $id);
					foreach ($sfvl->getList() as $item) {
						$value = trim($item['value']);
						if (!empty($bList[$value])) {
							DB::query("UPDATE shop_modifications_feature SET id_feature='{$baseId}', id_value='{$bList[$value]}' WHERE id_value='{$item['id']}'");
						} else {
							// Переносим этот параметр
							DB::query("UPDATE shop_feature_value_list SET id_feature='{$baseId}' WHERE id='{$item['id']}'");
							DB::query("UPDATE shop_modifications_feature SET id_feature='{$baseId}' WHERE id_feature='{$id}' AND id_value='{$item['id']}'");
							$bList[$value] = $item['id'];
						}
					}
					DB::query("DELETE FROM shop_feature WHERE id='{$id}'");
				}
			} else {
				foreach ($ids as $i => $id) {
					$id = intval($id);
					if ($i < 1) continue;
					if ($types[$baseId] !== $types[$id]) continue;
					$sfvl = new DB('shop_modifications_feature', 'sfvl');
					$sfvl->select('id');
					$sfvl->where('id_feature=?', $id);
					foreach ($sfvl->getList() as $item) {
						DB::query("UPDATE shop_modifications_feature SET id_feature='{$baseId}' WHERE id='{$item['id']}'");
					}
					DB::query("DELETE FROM shop_feature WHERE id='{$id}'");
				}
			}
			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
			$this->error = "Не удаётся объеденить значения параметров!";
			throw new Exception($this->error);
		}
	}
	// получить настройки
	protected function getSettingsFetch()
	{
		return array(
			"select" => 'sf.*, sfg.name name_group, count(smf.id) AS val_count',
			"joins" => array(
				array(
					"type" => "left",
					"table" => 'shop_feature_group sfg',
					"condition" => 'sfg.id = sf.id_feature_group'
				),
				array(
					"type" => "left",
					"table" => 'shop_group_feature sgf',
					"condition" => 'sgf.id_feature = sf.id'
				),
				array(
					"type" => "left",
					"table" => 'shop_modifications_group smg',
					"condition" => 'sgf.id_group = smg.id'
				),
				array(
					"type" => "left",
					"table" => 'shop_modifications_feature smf',
					"condition" => 'sf.id = smf.id_feature'
				),
			)
		);
	}

	public function save($isTransactionMode = true)
	{

		if ($this->replaceTypeValues()) {
			parent::save($isTransactionMode);
		} else {
			$this->error = "Не удаётся сохранить параметр!";
			throw new Exception($this->error);
		}
	}

	private function replaceTypeValues()
	{
		// Проверка на тип данных
		try {
			DB::beginTransaction();
			$id = intval($this->input["id"]);
			$u = new DB('shop_feature', 'sf');
			$u->select('sf.type');
			$u->where('sf.id=?', $id);
			$res = $u->fetchOne();
			unset($u);
			if (!empty($res['type']) && $this->input["type"] !== $res['type'] && ($this->input["type"] == 'list' || $this->input["type"] == 'colorlist' || count($this->input["values"]) == 0
			)) {
				// Делаем переопределение данных
				$Dlist = array();
				if ($res['type'] !== 'list' && $res['type'] !== 'colorlist') {
					$smf = new DB('shop_modifications_feature', 'smf');
					if ($res['type'] == 'number') {
						$smf->select('smf.id, smf.value_number AS `value`');
					} else if ($res['type'] == 'string') {
						$smf->select('smf.id, smf.`value_string` AS `value`');
					}

					$smf->where('smf.id_modification IS NULL');
					$smf->andwhere('smf.id_feature=?', $id);
					$reslist = $smf->getList();
					unset($smf);
					if ($this->input["type"] == 'list' || $this->input["type"] == 'colorlist') {
						$u = new DB('shop_feature_value_list', 'sfvl');
						$u->select('sfvl.id, sfvl.value');
						$u->where('id_feature=?', $id);
						$vallist = $u->getList();
						if (!empty($vallist))
							foreach ($vallist as $item) {
								$Dlist[strval($item['value'])] = $item['id'];
							}
						unset($u);
					}
					foreach ($reslist as $r) {
						$value = trim($r['value']);
						$fId = $r['id'];
						//writeLog($r);
						if (empty($value)) continue;
						if (empty($Dlist[$value])) {
							$data = array('idFeature' => $id, 'value' => $value);
							$u = new DB('shop_feature_value_list');
							$u->setValuesFields($data);
							$id_value = $u->save();
							$Dlist[$value] = $id_value;
						} else $id_value = intval($Dlist[$value]);
						if ($id_value && $fId) {
							DB::query("UPDATE shop_modifications_feature SET id_value='{$id_value}' WHERE id='{$fId}'");
						}
					}
					unset($this->input['values']);
					//$Dlist
				}
			} else if (!empty($res['type'])) {
				$this->input["type"] = $res['type'];
			}
			DB::commit();
			return true;
		} catch (Exception $e) {
			DB::rollBack();
			return false;
		}
	}

	// получить информацию по настройкам
	protected function getSettingsInfo()
	{
		return $this->getSettingsFetch();
	}

	// получить значения
	private function getValues()
	{
		$featureValue = new FeatureValue();
		return $featureValue->fetchByIdFeature($this->input["id"]);
	}

	// получить добавленную информацию
	protected function getAddInfo()
	{
		$result["values"] = $this->getValues();
		return $result;
	}

	// сохранить значения
	private function saveValues()
	{
		if (!isset($this->input["values"]))
			return;

		try {
			$idFeature = $this->input["id"];
			$values = $this->input["values"];

			$idsStore = "";
			$valuelist = array();
			foreach ($values as $ii => $value) {
				if ($value["color"]) {
					$value["color"] = str_replace('#', '', $value["color"]);
				}
				if ($value["id"] > 0) {
					if (strpos($value['value'], ',') !== false && is_numeric(str_replace(',', '.', $value['value']))) {
						$value['value'] = str_replace(',', '.', $value['value']);
					}
					if (!empty($valuelist[trim($value['value'])])) {
						// Объеденяем одинаковые значения
						$id_value = $valuelist[trim($value['value'])];
						DB::query("UPDATE `shop_modifications_feature` SET id_value='{$id_value}' WHERE id_value='" . intval($value['id']) . "'");
						unset($values[$ii]);
					} else {
						$valuelist[trim($value['value'])] = $value["id"];
						if (!empty($idsStore))
							$idsStore .= ",";
						$idsStore .= $value["id"];
						$u = new DB('shop_feature_value_list');
						$u->setValuesFields($value);
						$u->save();
					}
				}
			}

			if (!empty($idsStore)) {
				$u = new DB('shop_feature_value_list');
				$u->where("id_feature = {$idFeature} AND NOT (id IN (?))", $idsStore)->deleteList();
			} else {
				$u = new DB('shop_feature_value_list');
				$u->where("id_feature = ?", $idFeature)->deleteList();
			}

			$data = array();
			foreach ($values as $value)
				if (empty($value["id"]) || ($value["id"] <= 0)) {
					$data[] = array(
						'id_feature' => $idFeature, 'value' => $value["value"], 'color' => $value["color"],
						'sort' => (int) $value["sort"], 'image' => $value["image"]
					);
				}
			if (!empty($data)) {
				DB::insertList('shop_feature_value_list', $data);
			}
		} catch (Exception $e) {
			$this->error = "Не удаётся сохранить значения параметра!";
			throw new Exception($this->error);
		}
	}

	// сохранить добавленную информацию
	public function saveAddInfo()
	{
		$this->saveValues();
		return true;
	}
}
