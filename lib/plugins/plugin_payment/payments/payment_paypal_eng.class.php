<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
require_once dirname(__FILE__) . "/paypal/paypal.class.php";
/**
 * Плагин платежной системы PayPal
 */

class payment_paypal_eng extends basePayment
{

	public function setVars()
	{
		return array('pp_user_eng' => 'API UserName', 'pp_passw_eng' => 'API Password', 'pp_secret_eng' => 'API Signature');
	}

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);

		$currency = ($_SESSION['pricemoney']) ? $_SESSION['pricemoney'] : se_BaseCurrency();
		$user = $macros->execute('[SETCURRENCY:' . $currency . '][PAYMENT.PP_USER_ENG]');
		$currency = ($currency == 'RUR') ? 'RUB' : $currency;
		$pass = $macros->execute('[PAYMENT.PP_PASSW_ENG]');
		$signature = $macros->execute('[PAYMENT.PP_SECRET_ENG]');
		$paypal_server = ($this->test) ? 'sandbox' : 'live';
		$cancel_url = $macros->execute($this->getPathPayment('[MERCHANT_FAIL]', $pagename));
		$success_url = $macros->execute($this->getPathPayment('[MERCHANT_SUCCESS]', $pagename));
		$good_name = 'Order ' . $macros->execute('[ORDER.ID]');
		$description = 'Contract:' . $macros->execute('[USER.ID]') . '/ Order:' . $macros->execute('[ORDER.ID]');
		$amount = $macros->execute('[ORDER.SUMMA]');

		$paypal = new Paypal($user, $pass, $signature, $paypal_server);

		$request_params = array(
			'RETURNURL' => $success_url,
			'CANCELURL' => $cancel_url,
			'NOSHIPPING' => '1',
			'ALLOWNOTE' => '1',
			'set'
		);
		$order_params = array(
			'PAYMENTREQUEST_0_AMT' => $amount,
			'PAYMENTREQUEST_0_ITEMAMT' => $amount,
			'PAYMENTREQUEST_0_CURRENCYCODE' => $currency
		);
		$item = array(
			'L_PAYMENTREQUEST_0_NAME0' => $good_name,
			'L_PAYMENTREQUEST_0_DESC0' => $description,
			'L_PAYMENTREQUEST_0_AMT0' => $amount,
			'L_PAYMENTREQUEST_0_QTY0' => '1'
		);
		//initiate express checkout transaction
		$response = $paypal->request('SetExpressCheckout', $request_params + $order_params + $item);

		if (is_array($response) && $response['ACK'] == 'Success') {
			$token = $response['TOKEN'];
			//redirect to paypal where the buyer will make his payment
			if ($paypal_server == 'sandbox')
				header('Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . $token);
			elseif ($paypal_server == 'live')
				header('Location: https://www.paypal.com/webscr?cmd=_express-checkout&token=' . $token);
			exit;
		} else {
			header('Location: ' . $cancel_url);
			exit;
		}
	}

	public function result()
	{
		define("DEBUG", 1);
		define("USE_SANDBOX", 0);
		define("LOG_FILE", getcwd() . "/paypal.log");

		$raw_post_data = file_get_contents('php://input');
		$req = 'cmd=_notify-validate&' . $raw_post_data;
		if (DEBUG == true) {
			error_log(date('[Y-m-d H:i e] ') . "\r\nI get post request: <pre>" . $raw_post_data . "</pre>", 3, LOG_FILE);
		}

		if (USE_SANDBOX == true) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}

		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			return FALSE;
		}

		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

		if (DEBUG == true) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}

		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: Edgestile'));

		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) // cURL error
		{
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\nCan't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
			exit;
		} else {
			// Log the entire HTTP response if debug is switched on.
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\nHTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
				error_log(date('[Y-m-d H:i e] ') . "\r\nHTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
			}
			// Split response headers and payload
			list($headers, $res) = explode("\r\n\r\n", $res, 2);
			//}
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\n What I gett:" . $res, 3, LOG_FILE);
			}
			curl_close($ch);
		}

		if (strcmp($res, "VERIFIED") == 0) {
			$db_data = new seTable('shop_payment_merchant');
			$db_data->select('*');
			$db_data->where("`ident` LIKE '?'", 'ppl_' . $_POST['txn_id']);
			$datas = $db_data->fetchOne();

			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\n If I get errors:" . se_db_error(), 3, LOG_FILE);
				error_log(date('[Y-m-d H:i e] ') . "\r\n I get data from DB:" . print_r($datas, 1), 3, LOG_FILE);
			}

			if (!empty($datas)) {
				$payment_status = $_POST['payment_status'];
				$payment_currency = $_POST['mc_currency'];
				if ($payment_currency == 'RUB')
					$payment_currency = 'RUR';
				$payment_amount = $_POST['mc_gross'];

				if (DEBUG == true) {
					error_log(date('[Y-m-d H:i e] ') . "\r\n Check input data: Payment Status: " . $payment_status . " , Amount: " . $payment_amount . ' = ' . $datas['summ'] . ' , Currency: ' . $payment_currency . ' = ' . $datas['curr'], 3, LOG_FILE);
				}

				if (($payment_status == 'Completed') && ($payment_amount == $datas['summ']) && ($payment_currency == $datas['curr'])) {
					//  активация
					//$payment_currency = ($payment_currency == 'RUR') ? 'RUB' : $payment_currency;

					if (DEBUG == true) {
						error_log(date('[Y-m-d H:i e] ') . "\r\n I ready activate order, my Cuerrency: " . $payment_currency . ' , Order: ' . $datas['order_id'], 3, LOG_FILE);
					}

					$this->activate(intval($datas['order_id']));
					if (DEBUG == true) {
						error_log(date('[Y-m-d H:i e] ') . "\r\nVerified IPN: $req " . PHP_EOL, 3, LOG_FILE);
						error_log(date('[Y-m-d H:i e] ') . "POST" . print_r($_POST, 1) . PHP_EOL, 3, LOG_FILE);
						error_log(date('[Y-m-d H:i e] ') . "GET" . print_r($_GET, 1) . PHP_EOL, 3, LOG_FILE);
					}
					exit;
				}
			} else {
				if (DEBUG == true) {
					error_log(date('[Y-m-d H:i e] ') . "\r\n Data from DB is NOT find:", 3, LOG_FILE);
				}
			}
		} else if (strcmp($res, "INVALID") == 0) {
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\nInvalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
			exit;
		} else {
			if (DEBUG == true) {
				error_log(date('[Y-m-d H:i e] ') . "\r\nI cant compare strings $req AND VERFIED" . PHP_EOL, 3, LOG_FILE);
			}
			exit;
		}
	}

	public function success()
	{
		$__data = seData::getInstance();
		$pagename =  $__data->getpagename();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$cancel_url = $macros->execute($this->getPathPayment('[MERCHANT_FAIL]', $pagename));

		if ($_GET['token'] && $_GET['PayerID']) {
			$user = $macros->execute('[PAYMENT.PP_USER_ENG]');
			$pass = $macros->execute('[PAYMENT.PP_PASSW_ENG]');
			$signature = $macros->execute('[PAYMENT.PP_SECRET_ENG]');
			$paypal_server = ($this->test) ? 'sandbox' : 'live';

			$paypal = new Paypal($user, $pass, $signature, $paypal_server);
			$request_params_datail = array(
				'TOKEN' => $_GET['token']
			);

			$getDetail = $paypal->request('GetExpressCheckoutDetails', $request_params_datail);

			if (is_array($getDetail) && $getDetail['ACK'] == 'Success') {
				$request_params = array(
					'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale'
				);

				$response = $paypal->request('DoExpressCheckoutPayment', $getDetail + $request_params);

				if (is_array($response) && $response['ACK'] == 'Success') {
					$this->newPaymentLog('ppl_' . $response['PAYMENTINFO_0_TRANSACTIONID']);
					$this->success = '<h4 class="contentTitle">Payment Accepted</h4>
						<p>Your order № [ORDER.ID] accepted</p>
						<table class="tableTable" border="0">
							<tbody class="tableBody">
								<tr><td>Transaction number:</td><td>' . $response['PAYMENTINFO_0_TRANSACTIONID'] . '</td></tr>
								<tr><td>Date and time of payment:</td><td>' . date('r', strtotime($response['PAYMENTINFO_0_ORDERTIME'])) . '</td></tr>
							</tbody>
						</table>';
				} else {
					$this->success = '<h4 class="contentTitle">Error in conducting payment</h4>
						<table class="tableTable" border="0">
							<tbody class="tableBody">
								<tr><td>Reason:</td><td>' . $response['L_SHORTMESSAGE0'] . '</td></tr>
								<tr><td>Result:</td><td>' . $response['L_LONGMESSAGE0'] . '</td></tr>
							</tbody>
						</table>';
				}
				$this->success = $macros->execute($this->success);
				return $this->success;
			}
		} else {
			header('Location: ' . $cancel_url);
			exit;
		}
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->success = $macros->execute($this->success);
		return $this->success;
	}

	public function fail()
	{
		$this->fail = '<h4 class="contentTitle">Error in conducting payment</h4><p >Ups, something wrong</p>';
		return $this->fail;
	}
}
