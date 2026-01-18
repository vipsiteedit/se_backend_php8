<?php

require_once SE_LIBS . 'plugins/curlAbstract.class.php';

class plugin_capcha extends CurlAbstract 
{
    private $keySite;
    private $key;
    private $sid;
    private $hash;
    private $errstpin;
    public function __construct($keySite = false, $key = false)
    {
        $__data = seData::getInstance();

        
        $this->keySite = (string)$__data->prj->vars->sitecapchakey;
        $this->key = (string)$__data->prj->vars->sitecapchasecret;
        
        if ($this->keySite && $this->key) {
            $__data->header[] = '<script src="https://www.google.com/recaptcha/api.js"></script>';
        }
        
        $this->sid = session_id();
        $this->hash = md5($this->sid . getRequest('page'));
        $this->errstpin = '';
    }
    
    public function getCapcha($title = '', $error = '')
    {
        if (!$title) 
            $title = 'Введите цифры с картинки';
        
        if (!$error) 
            $error = 'Не верно введено число с картинки';
        
        if (empty($this->keySite)) {
            return '<img id="pin_img" src="/lib/cardimage.php?session=' . $this->sid . '&hash=' . $this->hash . '&' . time() . '">' .
                   '<div class="titlepin">'.$title.'</div>' .
                   '<input class="inp inppin ' . $this->errstpin . '" name="pin" maxlength="5" value="" autocomplete="off" required>' .
                   '<div class="err">' . ($this->errstpin ? $error : '') . '</div>';
        } else {
            return '<div class="g-recaptcha" data-sitekey="' . $this->keySite  . '"></div>';
        }
    }

    public function getError()
    {
        return $this->errstpin;
    }

    public function check()
    {
        if (empty($this->key)) {
            require_once SE_LIBS . "card.php";
            $this->errstpin = 'errorinp';
            if (empty($_POST['pin'])) {
                return -10;
            } elseif (checkcard($_POST['pin'],$this->hash)) {
                $this->errstpin = '';
                return true;
            }
            return false;
        } else {
            if (!getRequest('g-recaptcha-response', 3)){
                return -10;
            } else 
                $data = array(
                    'secret' => $this->key,
                    'response' => getRequest('g-recaptcha-response', 3),
                    'remoteip' => detect_ip()
                );
            $result = $this->queryJSON('https://www.google.com/recaptcha/api/siteverify', $data, 'POST');
            return $result["success"];
        }
    }
}
