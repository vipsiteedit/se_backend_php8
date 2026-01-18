<?php
/**
 * �� ���� ������ �������� ����������� �� QIWI ��������.
 * SoapServer ������ �������� SOAP-������, ��������� �������� ����� login, password, txn, status,
 * �������� �� � ������ ������ Param � �������� ������� updateBill ������� ������ TestServer.
 *
 * ������ ��������� ��������� ����������� ������ ���� � updateBill.
 */

 $s = new SoapServer('IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'Param', 'tns:updateBillResponse' => 'Response')));
// $s = new SoapServer('IShopClientWS.wsdl');
 $s->setClass('TestServer');
 $s->handle();

 class Response {
  public $updateBillResult;
 }

 class Param {
  public $login;
  public $password;
  public $txn;      
  public $status;
 }

 class TestServer {
  function updateBill($param) {
  
	// ������� ��� �������� ��������� � �������� ������� � ��� �������
    $f = fopen('c:\\phpdump.txt', 'w');
	fwrite($f, $param->login);
	fwrite($f, ', ');
	fwrite($f, $param->password);
	fwrite($f, ', ');
	fwrite($f, $param->txn);
	fwrite($f, ', ');
	fwrite($f, $param->status);
	fclose($f);
	
	// ��������� password, login
	
	// � ����������� �� ������� ����� $param->status ������ ������ ������ � ��������
	if ($param->status == 60) {
		// ����� �������
		// ����� ����� �� ������ ����� ($param->txn), �������� ��� ����������
	} else if ($param->status > 100) {
		// ����� �� ������� (������� �������������, ������������ ������� �� ������� � �.�.)
		// ����� ����� �� ������ ����� ($param->txn), �������� ��� ������������
	} else if ($param->status >= 50 && $param->status < 60) {
		// ���� � �������� ����������
	} else {
		// ����������� ������ ������
	}

	// ��������� ����� �� �����������
	// ���� ��� �������� �� ���������� ������� ������ � �������� ������ �������, �������� ����� 0
	// $temp->updateBillResult = 0
	// ���� ��������� ��������� ������ (��������, ������������� ��), �������� ��������� �����
	// � ���� ������ QIWI ������ ����� ������������ �������� ��������� ����������� ���� �� ������� ��� 0
	// ��� �� ������� 24 ����
	$temp = new Response();
	$temp->updateBillResult = 0;
	return $temp;
  }
 }
