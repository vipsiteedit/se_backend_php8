<?php

class plugin_shop_atol
{
    private static $instance = null;

    private $cache_dir = '';
    private $price = array();
    private $count = 0;

    private $atol = null;

    public function __construct()
    {
        $this->updateDB();

        $this->addSettings();

        $this->atol = new plugin_atol();
        /*
        $this->sell(34);
        sleep(1);
        
        $this->report(34);
        */
    }

    public function updateDB()
    {
        if (!file_exists(SE_ROOT . '/system/logs/shop_atol.upd')) {
            $sql = 'CREATE TABLE IF NOT EXISTS shop_atol_operation (
              id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              id_order int(10) UNSIGNED NOT NULL,
              operation varchar(255) NOT NULL,
              uuid varchar(255) NOT NULL,
              log text DEFAULT NULL,
              updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id),
              CONSTRAINT FK_shop_atol_id_order FOREIGN KEY (id_order)
              REFERENCES shop_order (id) ON DELETE NO ACTION ON UPDATE RESTRICT
            )
            ENGINE = INNODB
            AUTO_INCREMENT = 1
            CHARACTER SET utf8
            COLLATE utf8_general_ci;';
            se_db_query($sql);

            $sql = 'CREATE TABLE shop_atol_report (
              id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              id_order int(10) UNSIGNED NOT NULL,
              uuid varchar(255) NOT NULL,
              report text DEFAULT NULL,
              updated_at timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (id),
              CONSTRAINT FK_shop_atol_report_id_order FOREIGN KEY (id_order)
              REFERENCES shop_order (id) ON DELETE NO ACTION ON UPDATE RESTRICT
            )
            ENGINE = INNODB
            AUTO_INCREMENT = 25
            AVG_ROW_LENGTH = 1489
            CHARACTER SET utf8
            COLLATE utf8_general_ci;';

            file_put_contents(SE_ROOT . '/system/logs/shop_atol.upd', date('Y-m-d H:i:s'));
        }
    }

    public function addSettings()
    {
        if (!file_exists(SE_ROOT . '/system/logs/atol_settings.upd')) {
            $ssg = new seTable('shop_setting_groups');
            $ssg->select('id');
            $ssg->where('name="?"', 'Онлайн-касса "АТОЛ Онлайн"');
            if ($ssg->fetchOne()) {
                $id_group = $ssg->id;
            } else {
                $ssg->insert();
                $ssg->name = 'Онлайн-касса "АТОЛ Онлайн"';
                $ssg->description = 'Настройки для подключения сервиса онлайн-кассы "АТОЛ Онлайн"';
                $id_group = $ssg->save();
            }

            if ($id_group) {
                se_db_query("ALTER TABLE shop_settings CHANGE COLUMN type type VARCHAR(25) NOT NULL DEFAULT 'string' COMMENT 'string - текстовое поле, bool - чекбокс, select - выбор из списка из поля list_values';");

                $ss = new seTable('shop_settings');
                $ss->insert();
                $ss->code = 'atol_login';
                $ss->name = 'Логин пользователя API "АТОЛ Онлайн"';
                $ss->description = 'Логин для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя "АТОЛ Онлайн".';
                $ss->id_group = $id_group;
                $ss->sort = 1;
                $ss->save();

                $ss->insert();
                $ss->code = 'atol_password';
                $ss->type = 'password';
                $ss->name = 'Пароль пользователя API "АТОЛ Онлайн"';
                $ss->description = 'Пароль для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя "АТОЛ Онлайн".';
                $ss->id_group = $id_group;
                $ss->sort = 2;
                $ss->save();

                $ss->insert();
                $ss->code = 'atol_group';
                $ss->name = 'Идентификатор группы ККТ';
                $ss->description = 'Идентификатор группы для отправки данных можно получить из файла настроек для CMS в личном кабинете пользователя "АТОЛ Онлайн".';
                $ss->id_group = $id_group;
                $ss->sort = 3;
                $ss->save();

                $ss->insert();
                $ss->code = 'atol_inn';
                $ss->name = 'ИНН организации';
                $ss->description = 'Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим ИНН (сравнивается со значением в ФН). Допустимое количество символов 10 или 12. Если ИНН имеет длину меньше 12 цифр, то значение дополняется справа пробелами.';
                $ss->id_group = $id_group;
                $ss->sort = 4;
                $ss->save();

                $ss->insert();
                $ss->code = 'atol_payment_address';
                $ss->name = 'Адрес  места  расчетов';
                $ss->description = 'Используется для предотвращения ошибочных регистраций чеков на ККТ зарегистрированных с другим адресом места расчёта (сравнивается со значением в ФН). Максимальная длина строки – 256 символов.';
                $ss->id_group = $id_group;
                $ss->sort = 5;
                $ss->save();

                file_put_contents(SE_ROOT . '/system/logs/atol_settings.upd', date('Y-m-d H:i:s'));
            }
        }
    }

    private function getOrder($id_order = 0)
    {
        $order = array();

        $so = new seTable('shop_order');
        $so->select('id, id_author, delivery_payee, delivery_type');
        $so->find($id_order);

        if ($so->isFind()) {
            $order['id'] = $id_order;

            $p = new seTable('person');
            $p->select('id, email, phone');
            $p->find($so->id_author);

            $order['user'] = array(
                'id' => $so->id_author,
                'email' => (string)$p->email,
                'phone' => preg_replace('/[^0-9]/', '', $p->phone),
            );

            $sto = new seTable('shop_tovarorder');
            $sto->select('id, nameitem, price, discount, count');
            $sto->where('id_order=?', $id_order);
            $list = $sto->getList();

            $order['items'] = array();

            if ($list) {
                foreach ($list as $val) {
                    $order['items'][] = array(
                        'id' => $val['id'],
                        'name' => $val['nameitem'],
                        'price' => $val['price'] - $val['discount'],
                        'count' => (float)$val['count'],
                    );
                }
            }

            if ($so->delivery_type && $so->delivery_payee > 0) {
                $order['items'][] = array(
                    'id' => '0',
                    'name' => 'Доставка заказа',
                    'price' => $so->delivery_payee,
                    'count' => 1,
                );
            }
        }

        return $order;
    }


    public function sell($id_order = 0)
    {
        $order = $this->getOrder($id_order);

        if (!$order)
            return;

        $sum = 0;

        $items = array();

        if ($order['items']) {
            foreach ($order['items'] as $val) {
                $price = round($val['price'], 2);
                $amount = round($price * $val['count'], 2);
                $items[] = array(
                    'name' => mb_substr($val['name'], 0, 64),
                    'price' => $price,
                    'quantity' => $val['count'],
                    'sum' => $amount,
                    'tax' => 'vat18',
                );
                $sum += $amount;
            }
        }

        $inn = plugin_shopsettings::getInstance()->getValue('atol_inn');
        $payment_address = plugin_shopsettings::getInstance()->getValue('atol_payment_address');

        $data = array(
            'external_id' => $id_order . '_sale_test',
            'timestamp' => date('d.m.Y H:i:s'),
            'service' => array(
                'inn' => $inn,
                'payment_address' => $payment_address,
            ),
            'receipt' => array(
                'attributes' => array(
                    'email' => $order['user']['email'],
                    'phone' => $order['user']['phone'],
                ),
                'items' => $items,
                'payments' => array(
                    array(
                        'sum' => $sum,
                        'type' => 1,
                    )
                ),
                'total' => $sum,
            ),
        );

        //print_r($data);exit;

        $result = $this->atol->operation(1, $data);

        if (!empty($result)) {
            $json = json_decode($result, true);
            if (!$json['error'] && $json['status'] == 'wait' && $json['uuid']) {
                $sao = new setable('shop_atol_operation');
                $sao->insert();
                $sao->id_order = $id_order;
                $sao->uuid = $json['uuid'];
                $sao->operation = 'sell';
                $sao->log = $result;
                $sao->save();
            }
            $this->log(print_r($result, 1));
        }
    }

    public function report($id_order = 0, $operation = 'sell')
    {
        if (!$id_order)
            return;

        $sao = new seTable('shop_atol_operation');
        $sao->select('id, uuid');
        $sao->where('id_order=?', $id_order);
        $sao->andWhere('operation="?"', $operation);

        if ($sao->fetchOne()) {
            $uuid = $sao->uuid;

            $result = $this->atol->report($uuid);

            if (!empty($result)) {
                $json = json_decode($result, true);

                print_r($json);

                if (!$json['error'] && $json['status'] == 'done' && $json['payload']) {
                    $sar = new seTable('shop_atol_report');
                    $sar->insert();
                    $sar->id_order = $id_order;
                    $sar->uuid = $json['uuid'];
                    $sar->report = $result;
                    $sar->save();
                }
                $this->log(print_r($result, 1));
            }

            return $result;
        }
    }

    private function log($text)
    {
        $text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";

        $dir = SE_ROOT . 'system/logs/atol/';

        if (!is_dir($dir))
            mkdir($dir);

        $filename = $dir . date('Y-m-d') . '.log';

        $file = fopen($filename, 'ab');
        fwrite($file, $text);
        fclose($file);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
