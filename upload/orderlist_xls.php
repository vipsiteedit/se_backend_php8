<?php

define("IS_OUT_MODE", false);

if (IS_OUT_MODE) {
    define("SE_INDEX_INCLUDED", 1);
    chdir($_SERVER['DOCUMENT_ROOT']);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/system/main/init.php';
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPExcel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPExcel/Writer/Excel2007.php';

function getModifications($item)
{
    $u = new seTable('shop_modifications_feature', 'smf');
    $u->select('sf.name, sfl.value');
    $u->innerjoin('shop_feature sf', 'sf.id=smf.id_feature');
    $u->innerjoin('shop_feature_value_list sfl', 'sfl.id=smf.id_value');
    $u->where('smf.id_modification in (?)', $item['modifications']);

    $result = $u->getList();
    if (!$result && $item['modifications']) {
        $name = substr($item['nameitem'], strpos($item['nameitem'], '(') + 1, strpos($item['nameitem'], ')') - strpos($item['nameitem'], '(') - 1);
        if ($name) {
            $items = explode(", ", $name);
            foreach ($items as $item) {
                $nameItem = substr($item, 0, strpos($item, ':'));
                if ($nameItem) {
                    $mod["name"] = $nameItem;
                    $mod["value"] = substr($item, strpos($item, ':') + 2);
                    $result[] = $mod;
                }
            }
        }
    }
    return $result;
}

function getOrderItems($idOrder)
{
    $u = new seTable('shop_tovarorder', 'sto');
    $u->select("sto.*, sp.code, sp.id_group, sp.curr, sp.lang, sp.img, si.picture, sp.measure, sp.name price_name");
    $u->leftjoin('shop_price sp', 'sp.id=sto.id_price');
    $u->leftjoin('shop_img si', 'si.id_price=sto.id_price AND si.`default`=1');
    $u->where("id_order=?", $idOrder);
    $u->groupby('sto.id');
    $result = $u->getList();
    unset($u);
    $items = array();
    if (!empty($result)) {
        foreach ($result as $item) {
            if ($item['picture']) $item['img'] = $item['picture'];
            $product['id'] = $item['id'];
            $product['idPrice'] = $item['id_price'];
            $product['code'] = $item['code'];
            $product['name'] = $item['nameitem'];
            $product['originalName'] = $item['price_name'];
            $product['modifications'] = getModifications($item);
            $product['article'] = $item['article'];
            $product['measurement'] = $item['measure'];
            $product['idGroup'] = $item['id_group'];
            $product['price'] = (float)$item['price'];
            $product['count'] = (float)$item['count'];
            $product['bonus'] = (float)$item['bonus'];
            $product['discount'] = (float)$item['discount'];
            $product['note'] = $item['commentary'];
            $items[] = $product;
        }
    }
    return $items;
}

$order["id"] = $idOrder;
$order["items"] = getOrderItems($idOrder);

$xls = new PHPExcel();
$xls->setActiveSheetIndex(0);
$sheet = $xls->getActiveSheet();
$sheet->setTitle('Заказ № ' . $order["id"]);

$sheet->setCellValue("A1", 'Заказ № ' . $order["id"] . " от " . date("d.m.Y"));
$sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
$sheet->getStyle('A1')->getFont()->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->mergeCells('A1:F1');
$sheet->getColumnDimension('A')->setWidth(16);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(14);
$sheet->getColumnDimension('D')->setWidth(9);
$sheet->getColumnDimension('E')->setWidth(9);
$sheet->getColumnDimension('F')->setWidth(9);

$table_customer = new seTable('person', 'p');
$table_customer->select("CONCAT_WS(' ', p.last_name, p.first_name, p.sec_name) customer, p.*,  uu.*,
        GROUP_CONCAT(CONCAT(ur.rekv_code, '::', ur.value) SEPARATOR '|') params,
        so.commentary orderNote");
$table_customer->leftjoin('user_urid uu', 'uu.id = p.id');
$table_customer->leftjoin('user_rekv ur', 'ur.id_author = p.id');
$table_customer->innerjoin('shop_order so', 'so.id_author = p.id');
$table_customer->where('so.id = ?', $idOrder);
$customer = $table_customer->fetchOne();

$order["note"] = $customer["orderNote"];
$sheet->setCellValue("A2", 'Заказчик:');
$sheet->setCellValue("B2", $customer["customer"]);
$sheet->mergeCells("B2:F2");
$sheet->setCellValue("A3", 'Телефон:');
$sheet->setCellValue("B3", $customer["phone"]);
$sheet->mergeCells("B3:F3");
$sheet->setCellValue("A4", 'Email:');
$sheet->setCellValue("B4", $customer["email"]);
$sheet->mergeCells("B4:F4");
$r = 5;
if (empty($customer["company"])) {
    $sheet->setCellValue("A5", 'Адрес заказчика:');
    $sheet->setCellValue("B5", $customer["addr"]);
    $sheet->mergeCells('B5:F5');
} else {
    $codes = explode("|", $customer["params"]);
    $params = array();
    foreach ($codes as $code) {
        $code = explode('::', $code);
        $params[$code[0]] = $code[1];
    }
    $r = 5;
    $sheet->setCellValue("A{$r}", 'Компания:');
    $sheet->setCellValue("B{$r}", htmlspecialchars_decode($customer["company"]));
    $sheet->mergeCells("B{$r}:F{$r}");
    $r++;
    $sheet->setCellValue("A{$r}", 'Юрид. адрес:');
    $sheet->setCellValue("B{$r}", htmlspecialchars_decode($customer["uradres"]));
    $sheet->mergeCells("B{$r}:F{$r}");
    $r++;
    $sheet->setCellValue("A{$r}", 'Почт. адрес:');
    $sheet->setCellValue("B{$r}", htmlspecialchars_decode($customer["fizadres"]));
    $sheet->mergeCells("B{$r}:F{$r}");
    if (!empty($params["inn"])) {
        $r++;
        $sheet->setCellValue("A{$r}", 'ИНН:');
        $sheet->getCell("B{$r}")->setValueExplicit($params["inn"], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->mergeCells("B{$r}:F{$r}");
    }
    if (!empty($params["kpp"])) {
        $r++;
        $sheet->setCellValue("A{$r}", 'КПП:');
        $sheet->getCell("B{$r}")->setValueExplicit($params["kpp"], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->mergeCells("B{$r}:F{$r}");
    }
    if (!empty($params["bank"])) {
        $r++;
        $sheet->setCellValue("A{$r}", 'БАНК:');
        $sheet->getCell("B{$r}")->setValueExplicit(htmlspecialchars_decode($params["bank"]), PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->mergeCells("B{$r}:F{$r}");
    }
    if (!empty($params["rs"])) {
        $r++;
        $sheet->setCellValue("A{$r}", 'Расчет. счет:');
        $sheet->getCell("B{$r}")->setValueExplicit($params["rs"], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->mergeCells("B{$r}:F{$r}");
    }
    if (!empty($params["ks"])) {
        $r++;
        $sheet->setCellValue("A{$r}", 'Кор. счет:');
        $sheet->getCell("B{$r}")->setValueExplicit(htmlspecialchars_decode($params["ks"]), PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->mergeCells("B{$r}:F{$r}");
    }
}

$startRowTable = $r + 3;
$sheet->setCellValue("A{$startRowTable}", 'Наименование товара');
$sheet->mergeCells("A{$startRowTable}:B{$startRowTable}");
$sheet->setCellValue("C{$startRowTable}", 'Артикул');
$startSym = "D";
$codeSym = ord($startSym);
if ($order["items"]) {
    $product = $order["items"][0];
    foreach ($product["modifications"] as $modification)
        $sheet->setCellValue(chr($codeSym++) . "{$startRowTable}", $modification["name"]);
}

$startSymCount = $codeSym;
$sheet->setCellValue(chr($codeSym++) . "{$startRowTable}", 'Кол-во');
$sheet->setCellValue(chr($codeSym++) . "{$startRowTable}", 'Цена');
$sheet->setCellValue(chr($codeSym) . "{$startRowTable}", 'Сумма');
$r = $startRowTable - 1;
$sheet->setCellValue("A{$r}", 'Товары и услуги заказа');
$endSym = chr($codeSym);
$sheet->mergeCells("A{$r}:" . $endSym . $r);
$sheet->getStyle("A{$r}:" . $endSym . $r)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle("A{$r}:" . $endSym . $r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$r++;
$sheet->getStyle("A{$r}:" . $endSym . $r)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle("A{$r}:" . $endSym . $r)->getFont()->setBold(true);
$i = $startRowTable + 1;
foreach ($order["items"] as $product) {
    $codeSym = ord($startSym);
    $sheet->getStyle("E$i:" . $endSym . $i)->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle("A$i:" . $endSym . $i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle("A$i:" . $endSym . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    $sheet->mergeCells("A$i:B$i");
    $sheet->getStyle("A$i")->getAlignment()->setWrapText(true);
    if (strlen($product["originalName"]) > 50)
        $sheet->getRowDimension("$i")->setRowHeight(30);
    $sheet->setCellValue("A$i", $product["originalName"]);
    $sheet->setCellValue("C$i", $product["article"]);
    foreach ($product["modifications"] as $modification) {
        $sheet->setCellValue(chr($codeSym++) . $i, (string)$modification["value"]);
        $sheet->getStyle(chr($codeSym) . $i . ':' . chr($codeSym) . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    }
    $codeSym = $startSymCount;
    $sheet->setCellValue(chr($codeSym++) . "$i", $product["count"]);
    $sheet->setCellValue(chr($codeSym++) . "$i", $product["price"] - $product["discount"]);
    $sheet->setCellValue(chr($codeSym++) . "$i", ($product["price"] - $product["discount"]) * $product["count"]);
    $i++;
}
foreach (range('A', $endSym) as $columnID)
    $sheet->getColumnDimension($columnID)->setAutoSize(true);

$r = $i + 1;
$sheet->setCellValue("A{$r}", 'Примечание:');
$r++;
$sheet->setCellValue("A{$r}", htmlspecialchars_decode($order["note"]));
$count = substr_count($order["note"], "\r");
$rb = $r + $count;
$sheet->getStyle("A{$r}:F{$r}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$sheet->getStyle("A{$r}")->getAlignment()->setWrapText(true);
$sheet->mergeCells("A{$r}:F{$rb}");

$uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/files";
$uploadFile = $uploadDir . "/order_list_{$idOrder}.xlsx";
if (!file_exists($uploadDir)) {
    $dirs = explode('/', $uploadDir);
    $path = $root;
    foreach ($dirs as $d) {
        $path .= $d;
        if (!file_exists($path))
            mkdir($path, 0700);
        $path .= '/';
    }
}

if (IS_OUT_MODE) {
    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=order.xlsx");
}

$objWriter = new PHPExcel_Writer_Excel2007($xls);
$objWriter->save($uploadFile);

if (IS_OUT_MODE)
    echo file_get_contents($uploadFile);
