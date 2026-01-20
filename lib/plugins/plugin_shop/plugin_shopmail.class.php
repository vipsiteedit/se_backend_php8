<?php
//require_once dirname(__FILE__).'/../plugin_macros/plugin_macros.class.php';
//require_once dirname(__FILE__).'/../plugin_mail/plugin_mail.class.php';
//require_once dirname(__FILE__).'/../plugin_jmail/plugin_jmail.class.php';
//require_once dirname(__FILE__).'/../plugin_sms/plugin_sms.class.php';
//require_once dirname(__FILE__).'/../plugin_qtsms/plugin_qtsms.class.php';

class plugin_shopmail {

    private $typpost;
    private $order_id;
    private $payment_id;
    private $user_id = 0;
    
    public function __construct($order_id = 0, $payment_id = 0, $typpost="html", $user_id = 0) {
        $this->order_id = $order_id;
        $this->payment_id = $payment_id;
    	$this->typpost = $typpost;
    	$this->user_id = $user_id;
    }

    public function sendmail($mailtype, $email_to = '', $array_change = array(), $filename = '') {        
        $emailadmin = '';
        $sender = '';
        $phone_admin = '';
        $phone_to = '';
        $result = false;
        $lang = se_getLang();
        if (function_exists('se_getAdmin')){
           $emailadmin = se_getAdmin('esales, esupport, domain, sms_phone, sms_sender');
           if (is_array($emailadmin)) {
               $sender = $emailadmin['sms_sender'] ?? '';
               $phone_admin = $emailadmin['sms_phone'] ?? '';
               if (empty($emailadmin['esales'])) {
                   $emailadmin = $emailadmin['esupport'] ?? '';
               } else {
                   $emailadmin = $emailadmin['esales'] ?? '';
               }
           } else {
               $emailadmin = '';
           }
        }
        if (empty($emailadmin) && empty($email_to))
            return false;

        if (empty($email_to)) {
            $email_to = str_replace(',',';', $emailadmin);
            $phone_to = str_replace(',',';', $phone_admin);
        } else {        	
            if (!$this->user_id) {
                $order = new seTable("shop_order");
                $order->where("id = ?", $this->order_id);
                $order->fetchOne();
                if ($order->isFind())
                    $this->user_id = $order->id_author;
            }
            $person = new seTable('person');
            $person->where("id = ?", $this->user_id);
            $person->fetchOne();
            if ($person->isFind())
                $phone_to = str_replace(',',';', $person->phone);        	
        }
		
		$macros = new plugin_macros($this->user_id, $this->order_id, $this->payment_id);
      
        $smail = new seTable('shop_mail');
        $smail->where("`lang`='?'", $lang);
        $smail->where("`mailtype`='?'", $mailtype);
        $smail->fetchOne();
        if ($smail->isFind()) {
            $smail->letter = (string)$smail->letter;
            $smail->subject = (string)$smail->subject;
            if ($this->typpost!='html') 
    	    $smail->letter = str_replace("<br>","\r\n",$smail->letter); 
            else { 
                $smail->letter = str_replace("\r", "", $smail->letter);
                if (strpos($smail->letter,'<')!==false && strpos($smail->letter,'>')!==false) {
                    $smail->letter = str_replace("\n", "", $smail->letter);
                } else {
                    $smail->letter = str_replace("\n","<br>",$smail->letter);
                }
            }
    	    $smail->letter = str_replace(array('&quot;','&amp;','&#039;', "&lt;", "&gt;"), array('"', '&', "'", "<", ">"), $smail->letter);
    	    $smail->subject = str_replace(array('&quot;','&amp;','&#039;', "&lt;", "&gt;"), array('"', '&', "'", "<", ">"), $smail->subject);
            foreach ($array_change as $k => $v) {
    	        $smail->letter = str_replace("[".$k."]", $v, $smail->letter);
    	        $smail->subject = str_replace("[".$k."]", $v, $smail->subject);
            } 
            
            $smail->letter = $macros->execute($smail->letter);
            $smail->subject = $macros->execute($smail->subject);
            
            if (strpos($smail->letter, "{attachment: order_list}") !== false) {
                $smail->letter = str_replace("{attachment: order_list}", "", $smail->letter);
                $filename = $this->getFileOrderList(); 
            }
            
            if (strpos($smail->letter, '{attachment:cart_upload_file.') !== false) {
				if (preg_match('/{attachment:cart_upload_file.([^}]+)}/', $smail->letter, $m)) {
					$name = $m[1];
                    if (isset($_SESSION['cart_upload_file'][$name])) {
					    $filename = SE_ROOT . $_SESSION['cart_upload_file'][$name];
                    }
				}
				$smail->letter = preg_replace('/{attachment:cart_upload_file.([^}]+)}/', '', $smail->letter);
			}
            
            if (strpos($smail->letter, '{attachment:registration_upload_file.') !== false) {
				if (preg_match('/{attachment:registration_upload_file.([^}]+)}/', $smail->letter, $m)) {
					$name = $m[1];
                    if (isset($_SESSION['registration_upload_file'][$name])) {
					    $filename = SE_ROOT . $_SESSION['registration_upload_file'][$name];
                    }
				}
				$smail->letter = preg_replace('/{attachment:registration_upload_file.([^}]+)}/', '', $smail->letter);
			}
            
            if (!empty($emailadmin)) {
                list($email_from,) = preg_split("/[\\s,;]+/", $emailadmin);
            } else {
                $email_from = '';
            }
            if (empty($email_from) || $email_from == $email_to || (strpos($email_from, "@mail.ru") !== false)) {
                $email_from = 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
            }
            $emaillist = explode(';',$email_to);
            $result = true;
            foreach ($emaillist as $email_to){
                $mailsend = new plugin_jmail($smail->subject, $email_to, $email_from);
                $mailsend->addtext($smail->letter, "text/{$this->typpost}");
                if (!empty($filename)) {
                    $filelist = explode(';', $filename);
                    foreach ($filelist as $file) {
                        $file = trim($file);
                        if ($file === '') {
                            continue;
                        }
                        $mailsend->attach($file, '', 'application/octet-stream');
                    }
                }
        	    if (!$mailsend->send()) {
                    $result = false;
        	    }
        	    unset($mailsend); 
            }    
        }
        
        // SMS send
        $provider = new seTable('sms_providers', 'sp');        
        $provider->where('is_active');
        $provider->fetchOne();
        if ($provider->isFind()) {
            $template = new seTable('sms_templates');          
            $template->where("is_active AND `code` = '?'", $mailtype);
            $template->fetchOne();
            if ($template->isFind()) {
            	$sender = empty($template->sender) ? $sender : $template->sender;
            	$phone_to = empty($template->phone) ? $phone_to : $template->phone;
                $text = strip_tags($macros->execute($template->text));
                $text = str_replace("&nbsp;", "", $text);
                $phones_list = explode(';', $phone_to);
                foreach ($phones_list as $phone_to) 
                    $this->smsSend($provider, $phone_to, $text, $sender);
            }            
        }         
        return $result;
    }

    private function smsSend($provider, $phoneTo, $text, $sender)
    {
        $phoneTo = preg_replace('/[^0-9]/', '', $phoneTo); 
        $log = new seTable('sms_log');
        $log->date = date("Y-m-d H:i:s");
        $log->text = $text;
        $log->phone = $phoneTo;
        $log->id_provider = $provider->id;
        $log->id_user = $this->user_id;
        if ($provider->name == "sms.ru") {         
            $settings = json_decode($provider->settings, true);
            $sms = new plugin_sms($settings["api_id"]["value"]);
            $response = $sms->sms_send($phoneTo, $text, $sender);
            $log->id_sms = $response["ids"][0];
            $log->code = $response["code"];
            $log->status = $sms->response_code["status"][$log->code];
            $costInfo = $sms->sms_cost($phoneTo, $text); 
            $log->cost = $costInfo["price"];
            $log->count = $costInfo["number"];
            $log->save();
        }
        if ($provider->name == "qtelecom.ru") {
            $settings = json_decode($provider->settings, true);
            $sms = new plugin_qtsms($settings["login"]["value"], $settings["password"]["value"]);
            $response = $sms->post_sms($text, $phoneTo, '', $sender);
            $response = $response["result"]["sms"]["@attributes"];
            $log->id_sms = $response["id"];
            $log->status = "Cообщение в очереди отправки";
            $log->cost = 0;
            $log->count = $response["sms_res_count"];
            $log->save();
        } 
		if ($provider->name == "inCore Dev") {
            $settings = json_decode($provider->settings, true);
            $sms = new plugin_sms_incore($settings['host']['value'], $settings['login']['value'], $settings['password']['value']);
			$response = $sms->send($text, $phoneTo, $sender);
			$log->id_sms = $response['id_sms'];
			$log->status = $response['value'];
			$state = $sms->getState($response['id_sms']);
			$log->cost = $state['price'];
			$log->count = $state['num_parts'];
            $log->save();
        } 
    }
    
    private function getFileOrderList() {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/upload/orderlist_xls.php"))
            return null;
        
        $idOrder = $this->order_id;        
        require_once $_SERVER["DOCUMENT_ROOT"] . "/upload/orderlist_xls.php";        
        if (!empty($uploadFile) && file_exists($uploadFile))
            return $uploadFile;        
        
        return null;
    }
}
