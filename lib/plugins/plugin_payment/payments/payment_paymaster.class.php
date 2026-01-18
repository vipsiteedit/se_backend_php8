<?php
require_once dirname( __FILE__ ) . '/basePayment.class.php';

/**
* Плагин платежной системы PayMaster
*/

class payment_paymaster extends basePayment {
    //private $order_id = 0;
    private $url = 'https://paymaster.ru/api/v2';

    public function setVars() {
        return array(
            'pm_merchant' => 'ID магазина',
            'pm_method'=>'Метод платежа (BankCard, Sbp)',
            'pm_api_token'=>'API токен'
        );
    }

    public function startform() {
        $macros = new plugin_macros( 0, $this->order_id, $this->payment_id );
        return $macros->execute( $this->startform );
    }

    public function blank( $pagename ) {
        $macros = new plugin_macros( 0, $this->order_id, $this->payment_id );
        $id_merchant = $macros->execute( '[PAYMENT.PM_MERCHANT]' );
        $privateSecurityKey = $macros->execute( '[PAYMENT.PM_PASSW]' );
        $method = $macros->execute( '[PAYMENT.PM_METHOD]' );
        $order_id = $macros->execute( '[ORDER.ID]' );
        $amount = se_FormatNumber( $macros->execute( '[ORDER.SUMMA]' ), '' );
        $order_user = $macros->execute( '[USER.ID]' );
        $result_url = $this->getPathPayment( '[MERCHANT_RESULT]', $pagename );
        $success_url = $this->getPathPayment( '[MERCHANT_SUCCESS]', $pagename );
        
        //$fail_url = $macros->execute( $this->getPathPayment( '[MERCHANT_FAIL]', $pagename ) );

        $description = 'Order:' . $order_id . '/User:' . $order_user;

        $data = array(
            'testMode'=>$this->test,
            'merchantId'=>$id_merchant,
            'invoice'=>array(
                'description'=>"заказ № $order_id",
                'orderNo'=>$order_id
            ),
            'amount'=>array(
                'value'=>$amount,
                'currency'=> 'RUB'
            ),
            'paymentMethod'=>$method?$method:'BankCard',
            'protocol'=> array(
                'callbackUrl'=>$result_url,
                'returnUrl'=>$success_url
            )
        );
        $headers = array(
            'Authorization: Bearer '.$macros->execute( '[PAYMENT.PM_API_TOKEN]' ),
            'Idempotency-Key: '.md5( time() ),
            'Content-Type: application/json',
            'Accept: application/json'
        );
        $result = json_decode(self::query( $this->url.'/invoices', $data, 'JSON_POST', true, $headers ), true);
        if ( $result && $result[ 'url' ]) {
            header( 'Location: '.$result[ 'url' ] );
            exit;
        }
        $this->newPaymentLog( $result[ 'paymentId' ] );

    }

    public function result() {

        //$res = $this->getPaymentLog();

        $data = json_decode( file_get_contents( 'php://input' ), true );
        $macros = new plugin_macros( 0, $this->order_id, $this->payment_id );
        $id_merchant = $macros->execute( '[PAYMENT.PM_MERCHANT]' );
        $this->order_id = $data[ 'invoice' ][ 'orderNo' ];
        if ( $data[ 'merchantId' ] == $id_merchant &&
        $data[ 'status' ] == 'Settled') {
            $this->activate($this->order_id);
        }
        exit;
    }

    public function success() {
        $macros = new plugin_macros( 0, $this->order_id, $this->payment_id );
        $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><p>Ваш заказ № ' . $this->order_id . ' оплачен</p>';
        return $macros->execute( $this->success );
    }

    public function fail() {
        $macros = new plugin_macros( 0, $this->order_id, $this->payment_id );
        $this->fail = '<h4 class="contentTitle">Ошибка в проведении платежа</h4>';
        return $macros->execute( $this->fail );
    }

    private function logs( $text ) {
        file_put_contents( getcwd() . '/paymaster_log.txt', $text . '\r\n <br>', FILE_APPEND );
    }
}
