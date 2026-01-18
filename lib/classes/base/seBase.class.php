<?php

/**
 * SiteEdit core version 5.3
 * @filesource seShopPrice.class.php
 * @copyright EDGESTILE
 */
abstract class seBase
{
    protected $table_id = 0;
    public $data;
    protected $is_find = false;
    protected $datalist;
    protected $where = '';
    protected $having = '';
    protected $ljoin = '';
    protected $rjoin = '';
    protected $ijoin = '';
    protected $select = '';
    protected $orderby = '';
    protected $groupby = '';
    protected $offset = 0;
    protected $limit = 0;
    protected $update = null;
    protected $is_table = false;


    protected $table_name;
    protected $table_alias;

    protected $fields = array();
    protected $fieldalias = array();

    protected $crfield = array();
    protected $tablefields = array();
    
    private $updatedata = array();

    public function __construct()
    {
        $this->table_id = 0;
        $this->configure();
        return $this;
    }

    public function getTable($name, $alias = '')
    {
        return $this->from($name, $alias);
    }

    public function from($name, $alias = '')
    {
        $this->table_id = 0;
        $this->table_name = $name;
        if ($alias == '') $alias = '__' . $name;
        $this->table_alias = $alias;
        return $this;
    }

    public function fetchOne()
    {
        if (!SE_DB_ENABLE) return;
        $this->is_find = false;
        $this->table_id = 0;
        //unset($this->data);
        $this->data = array();
        if (empty($this->select)) {
            $select = '`' . $this->table_alias . '`.*';
        } else {
            $select = join(',', $this->select);
        }

        $where = '';
        if (!empty($this->where)) {
            $where = ' WHERE ' . $this->where;
        } elseif ($this->table_id > 0) {
            $where = " WHERE `{$this->table_alias}`.`id` = '$this->table_id'";
        }

        $sql = "select $select FROM `$this->table_name` `$this->table_alias` " . strval($this->ijoin) . strval($this->ljoin) . strval($this->rjoin) . $where . " LIMIT 1";

        $qr = se_db_query($sql);
        if (!empty($qr)) {
            $this->data = se_db_fetch_assoc($qr);
            $this->is_find = !empty($this->data);

            if (isset($this->data['id'])) {
                $this->table_id = $this->data['id'];
            } else {
                $this->where = '';
            }

        } else {
            $this->where = '';
        }
        return $this->data;
    }

    public function isFind()
    {
        return $this->is_find;
    }

    public function getRow()
    {
        return $this->data;
    }

    public function insert($fields = '')
    {
        $this->table_id = 0;
        $this->data = array();
        $this->update = null;
        $this->is_find = false;
        if (!empty($fields)) {
            $this->fields = explode(',', str_replace(' ', '', $fields));
        }
        return $this;
    }

    public function update($field, $value = '')
    {
        $this->update = array();
        if (!empty($value)) {
            $this->update[$field] = $value;
        }
        return $this;
    }

    public function addUpdate($field, $value = '')
    {
        if (!empty($value)) {
            $this->update[$field] = $value;
        }
        return $this;
    }

    public function find($id)
    {
        $this->table_id = $id;
        $this->where($this->table_alias . '.id=?', $id);
        return $this->fetchOne();
    }

    /**********************************************************************
     *  Добавление полей в базу
     *  fieldname - имя столбца
     *  type - тип
     *  index : 0 - без индекса, 1 - INDEX, 2 - UNIQUE
     **********************************************************************/
    public function addField($fieldname, $type = 'string', $index = false)
    {
        if (!SE_DB_ENABLE) return;
        if (!isset($this->fields[$fieldname])) {
            $this->fields[$fieldname] = $type;
            if (!se_db_is_field($this->table_name, $fieldname)) {
                se_db_add_field($this->table_name, $fieldname, $type);
                if ($index) {
                    se_db_add_index($this->table_name, $fieldname, $index);
                }
            }
        }
    }

    public function getFields()
    {
        if (!SE_DB_ENABLE) return;
        $this->tablefields = se_db_columns_field($this->table_name);
        return $this->tablefields;
    }

    public function isFindField($fieldname)
    {
        if (empty($this->tablefields)) {
            $this->getFields();
        }
        return in_array($fieldname, $this->tablefields);
    }


    private function validate()
    {
        foreach ($this->updatedata as $name => $value) {
            if (isset($this->fields[$name]) && strpos($this->fields[$name], 'int') !== false) {
                $this->updatedata[$name] = intval($value);
            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'key') !== false) {
                if (intval($value))
                    $this->updatedata[$name] = intval($value);
                else $this->updatedata[$name] = 'null';
            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'str') !== false) {
                $this->updatedata[$name] = strval($value);
            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'float') !== false) {
                $this->updatedata[$name] = str_replace(',', '.', $value);
            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'bool') !== false) {
                if ($value > 0) {
                    $this->updatedata[$name] = 1;
                } else {
                    $this->updatedata[$name] = 0;
                }
            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'datetime') !== false) {
                if (preg_match("/[\d]{4}\-[\d]{1,2}\-[\d]{1,2}(\s\d{2}:\d{2}:\d{2})?$/", $value))
                    $this->updatedata[$name] = $value;
                else
                    unset($this->updatedata[$name]);

            } elseif (isset($this->fields[$name]) && strpos($this->fields[$name], 'date') !== false) {
                if (preg_match("/[\d]{4}\-[\d]{1,2}\-[\d]{1,2}$/", $value))
                    $this->updatedata[$name] = $value;
                else
                    unset($this->updatedata[$name]);
            } elseif ($this->is_table == false) {
                unset($this->updatedata[$name]);
            }
        }
    }

    public function save()
    {
        if (!SE_DB_ENABLE) return;
        if (empty($this->update)) {
            $this->validate();

            if ($this->table_id > 0 || $this->is_find) {
                $this->updatedata['updated_at'] = date('Y-m-d H:i:s', time());

                if ($this->table_id) $where = "`id` = " . $this->table_id;
                else $where = $this->where;
                return se_db_perform($this->table_name, $this->updatedata, 'update', $where);
            } else {
                $this->data['created_at'] = date('Y-m-d H:i:s', time());
                $this->table_id = se_db_perform($this->table_name, $this->data);
                return $this->table_id;
            }
        } else {
            // update
            if (!empty($this->where))
                $where = ' where ' . $this->where;
            $sql = 'update `' . $this->table_name . '` set ';
            foreach ($this->update as $field => $value) {
                $sql .= $field . '=' . $value . ',';
            }
            $sql .= "`updated_at`='" . date('Y-m-d H:i:s', time()) . "'";
            $sql .= ' ' . $where;
            $rez = se_db_query($sql);
            $id = se_db_fields_item($this->table_name, $this->where, 'id');
            log_update($this->table_name, $id, 'u');
            return $rez;
        }
    }


    public function delete($id = 0)
    {
        if (!SE_DB_ENABLE) return;
        if (is_array($id)) {
            $id = join(',', $id);
        } else
            if ($id == 0) {
                $id = $this->table_id;
            }
        se_db_delete($this->table_name, "id IN ($id)");
        return $this;
    }

    public function deleteList()
    {
        if (!SE_DB_ENABLE) return;
        se_db_delete($this->table_name, $this->where);
        return $this;
    }

    public function findList($findtext = '')
    {
        if (is_numeric($findtext))
            $this->where = "`id` = '$findtext'";
        else
            if (!empty($findtext))
                $this->where = $findtext;
            else {
                $this->where = '';
            }
        return $this;
    }


    public function getListCount()
    {
        if (!SE_DB_ENABLE) return;
        $count = 0;
        $where = $having = '';
        if (!empty($this->where))
            $where = ' WHERE ' . $this->where;
        if (!empty($this->having))
            $having = ' HAVING ' . $this->having;

        if ((strval($this->ijoin) . strval($this->ljoin) . strval($this->rjoin)) != '')
            $select = 'COUNT(DISTINCT `' . $this->table_alias . '`.id)';
        else $select = 'COUNT(*)';
        $sql = "SELECT $select FROM `" . $this->table_name . "` `" . $this->table_alias . "` " .
            strval($this->ijoin) . strval($this->ljoin) . strval($this->rjoin) . $where . $having . ' LIMIT 1';

        $qr = se_db_query($sql);
        if (!empty($qr))
            list($count) = se_db_fetch_row($qr);
        return intval($count);
    }

    public function getSql()
    {
        $this->datalist = array();

        if ($this->offset >= 0 && $this->limit > 0) {
            $limitstr = se_db_limit($this->offset, $this->limit);
        } else
            $limitstr = '';

        if (empty($this->select)) {
            $select = '`' . trim($this->table_alias) . '`.*';
        } else {
            $select = join(',', $this->select);
        }

        $orderby = (!empty($this->orderby)) ? ' ORDER BY ' . $this->orderby : '';
        $groupby = (!empty($this->groupby)) ? ' GROUP BY ' . $this->groupby : '';
        $where = (!empty($this->where)) ? ' WHERE ' . $this->where : '';
        $having = (!empty($this->having)) ? ' HAVING ' . $this->having : '';

        return "SELECT $select FROM `" . $this->table_name . '` `' . $this->table_alias . '` ' . strval($this->ijoin) . strval($this->ljoin) . strval($this->rjoin) . $where . $groupby . $having . $orderby . $limitstr;

    }

    public function getList($offset = 0, $limit = 0)
    {
        if (!SE_DB_ENABLE) return;
        if ($limit > 0) {
            $this->offset = $offset;
            $this->limit = $limit;
        }
        $qr = se_db_query($this->getSQL());
        if (!empty($qr))
            while ($line = se_db_fetch_assoc($qr)) {
                $this->datalist[] = $line;
            }
        return $this->datalist;
    }

    private function setCov($text)
    {

        if (strpos($text, '.') === false) {
            //$text = trim(str_replace(array('`','"'), array('',''), $text));
            //$text = preg_replace("/([\w\d_]+)(\sAS\s)([\w\d_]+)/m", "`$1`$2`$3`", $text);
            //$text = '`'.$this->alias . '`.`' . $text.'`';
        } else {
            //$text = trim(str_replace(array('`','"'), array('',''), $text));
            //$text = preg_replace("/([\w\d_]+)\.([\w\d_]+)(\sAS\s)([\w\d_]+)/m", "`$1`.`$2`$3`$4`", $text);
            //$text = '`'. $text . '`';
        }
        return $text;
    }

    function __set($name, $value)
    {
        if (!empty($this->fieldalias[$name])) {
            $name = $this->fieldalias[$name];
        } 
        $this->data[$name] = $value;
        
        $this->updatedata[$name] = $value;
    }

    function __get($name)
    {
        if (isset($this->fieldalias[$name]) && isset($this->data[$this->fieldalias[$name]])) {
            return $this->data[$this->fieldalias[$name]];
        } 
        elseif (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    public function select($select = '')
    {
        $this->select = array();
        if (!empty($select)) {
            $selects = explode(',', $select);
            foreach ($selects as $item) {
                $this->select[] = $this->setCov($item);
            }
        }
        //$this->ljoin = $this->rjoin = $this->ijoin = '';
        return $this;
    }
    
    public function addSelect($select = '')
    {
        if (!empty($select)) {
            $selects = explode(',', $select);
            foreach ($selects as $item) {
                $this->select[] = $this->setCov($item);
            }
        }
        return $this;
    }

    public function leftJoin($table, $withselect = '')
    {
        if (strpos($withselect, '=') === false)
            $withselect = $withselect . '=' . $table . '.id';

        $this->ijoin .= ' LEFT JOIN ' . $table . ' ON (' . $withselect . ' )';
        return $this;
    }

    public function rightJoin($table, $withselect = '')
    {
        if (strpos($withselect, '=') === false)
            $withselect = $withselect . '=' . $table . '.id';
        $this->ijoin .= ' RIGHT JOIN ' . $table . ' ON (' . $withselect . ')';
        return $this;
    }

    public function innerJoin($table, $withselect = '')
    {
        if (strpos($withselect, '=') === false)
            $withselect = $withselect . '=' . $table . '.id';
        $this->ijoin .= ' INNER JOIN ' . $table . ' ON (' . $withselect . ' )';
        return $this;
    }

    public function where($where = '', $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->where = str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function addWhere($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->where = str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function andWhere($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->where .= ' AND ' . str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function orWhere($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->where .= ' OR ' . str_replace('?', se_db_input($value), $where);
        return $this;
    }


    public function having($where = '', $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->having = str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function addHaving($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->having = str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function andHaving($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->having .= ' AND ' . str_replace('?', se_db_input($value), $where);
        return $this;
    }

    public function orHaving($where, $value = '')
    {
        if (!SE_DB_ENABLE) return;
        $this->having .= ' OR ' . str_replace('?', se_db_input($value), $where);
        return $this;
    }


    public function orderBy($orderby = 'id', $sort = 0)
    {
        if ($sort == 0)
            $sort = 'ASC';
        else
            $sort = 'DESC';

        if (strpos($orderby, '.') === false && strpos($orderby, '`') === false) {
            $orderby = '`' . $orderby . '`';
        }
        $this->orderby = $orderby . ' ' . $sort;
        return $this;
    }

    public function addOrderBy($orderby = 'id', $sort = 0)
    {
        if ($sort == 0)
            $sort = 'ASC';
        else
            $sort = 'DESC';

        if (strpos($orderby, '.') === false && strpos($orderby, '`') === false) {
            $orderby = '`' . $orderby . '`';
        }

        if (empty($this->orderby)) {
            $this->orderby = $orderby . ' ' . $sort;
        } else {
            $this->orderby .= ',' . $orderby . ' ' . $sort;
        }

        return $this;
    }

    public function groupBy($groupby = 'id')
    {
        $this->groupby = $groupby;
        return $this;
    }

    public function pageNavigator($limit = 30, $section_id = false, $all = false)
    {
        if ($limit > 0) {
            $this->limit = $limit;
        } else
            return;

        $this->cnrowfull = $this->getListCount();

        $count_pages = ceil($this->cnrowfull / $this->limit);

        $sheet = max(isRequest('sheet', 2) ? getRequest('sheet', VAR_INT, 2) : getRequest('sheet', VAR_INT), 1);
        //$sheet = min($sheet, $count_pages);

        if ($sheet > $count_pages && $sheet > 1)
            seData::getInstance()->go404();

        $this->offset = ($sheet - 1) * $this->limit;

        $pagination = se_Navigator($this->cnrowfull, $this->limit, $sheet, $section_id);

        if ($all) {
            return array(
                'pagination' => $pagination,
                'next' => ($sheet + 1) > $count_pages ? 0 : $sheet + 1,
                'prev' => $sheet - 1,
                'current' => $sheet,
                'count' => $count_pages
            );
        }

        return $pagination;
    }

}
