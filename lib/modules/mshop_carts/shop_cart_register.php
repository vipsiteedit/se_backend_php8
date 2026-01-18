<?php

/**
 * @author Vladimir Sukhopyatkin
 * @copyright 2011
 */

require_once $__MDL_URL.'/shop_cart_view.php'; 

class shop_cart_Registration 
{
    public $section;
    public $data;
    public $view;
    
    public $username; // логин (в зависимости от настроек туда помещается `email`, `phone` или `username`)
    public $first_name;
    public $last_name;
    public $sec_name;
    public $email; 
    public $phone;
    public $passw;
    public $passw1;
    
    public function __construct($section, $view) 
    {
        $this->section = $section;
        $this->data = seData::getInstance(); 
        $this->view = $view;
        
        if (isRequest('first_name')) 
            $_SESSION['shopreg']['first_name'] = getRequest('first_name', VAR_STRING);
        if (!empty($_SESSION['shopreg']['first_name']))  
            $this->first_name = $_SESSION['shopreg']['first_name'];
            
        if (isRequest('last_name')) 
            $_SESSION['shopreg']['last_name'] = getRequest('last_name', VAR_STRING);
        if (!empty($_SESSION['shopreg']['last_name']))  
            $this->last_name = $_SESSION['shopreg']['last_name'];

        if (isRequest('sec_name')) 
            $_SESSION['shopreg']['sec_name'] = getRequest('sec_name', VAR_STRING);
        if (!empty($_SESSION['shopreg']['sec_name']))  
            $this->sec_name = $_SESSION['shopreg']['sec_name'];
            
        if (isRequest('email')) 
            $_SESSION['shopreg']['email'] = getRequest('email', VAR_STRING);
        if (!empty($_SESSION['shopreg']['email']))  
            $this->email = $_SESSION['shopreg']['email'];

        if (isRequest('phone')) 
            $_SESSION['shopreg']['phone'] = getRequest('phone', VAR_STRING);
        if (!empty($_SESSION['shopreg']['phone']))  
            $this->phone = $_SESSION['shopreg']['phone'];
        
        if (isRequest('username')) 
            $_SESSION['shopreg']['username'] = getRequest('username', VAR_STRING);
        if (!empty($_SESSION['shopreg']['username']))  
            $this->username = $_SESSION['shopreg']['username'];
    
        if ($this->section->parametrs->param74 == 'email') 
            $this->username = $this->email;
        elseif ($this->section->parametrs->param74 == 'phone')  
            $this->username = $this->phone;  
    //    elseif ($this->section->parametrs->param74 == 'username')  
    //        $this->username = $req['username'];    
    
        if (isRequest('passw')) 
            $_SESSION['shopreg']['passw'] = getRequest('passw', VAR_STRING);
        if (!empty($_SESSION['shopreg']['passw']))  
            $this->passw = $_SESSION['shopreg']['passw'];
        
        if (isRequest('passw1')) 
            $_SESSION['shopreg']['passw1'] = getRequest('passw1', VAR_STRING);
        if (!empty($_SESSION['shopreg']['passw1']))  
            $this->passw1 = $_SESSION['shopreg']['passw1'];
    }
    
    public function register()
    {
        if ($this->validate())
        {           
            $user = new seUser();
            $user->where("username = '?'", $this->username);
            if($user->fetchOne()) 
            {  
                $this->view->showExError($this->username." - ".$this->section->parametrs->param22);
            } 
            else 
            {   
                $id_up = intval($_SESSION['REFER']);
                
                $req['username'] = $this->username;
                $req['passw'] = $this->passw;
                $req['confirm'] = $this->passw1;
                $req['last_name'] = $this->last_name;
                $req['first_name'] = $this->first_name;
                $req['sec_name'] = $this->sec_name;
                $req['email'] = $this->email;
                $req['phone'] = $this->phone;
        
                if ($id_num = $user->registration($req, $id_up, 1, ''))
                {                
                    
                    // Открываем доступ
                    check_session(true); // Удаляем старую сессию
                    $auth['IDUSER'] = $id_num;
                    $auth['GROUPUSER'] = 1;
                    $auth['USER'] = $this->last_name." ".$this->first_name;
                    $auth['AUTH_USER'] = $this->username;
                    $auth['AUTH_PW'] = $password;
                    $auth['ADMINUSER'] = $id_up;
                    check_session(false, $auth); // Создаем новое вхождение авторизации

                    $nameuser = trim($this->last_name." ".$this->first_name);
                    $tbl = new seTable("main");
//                      $email_from = se_db_fields_item("main","lang='$lang'","esupport");

                    $tbl->select("esupport");
                    $tbl->where("lang = '?'", se_getLang());
                    $tbl->fetchOne();
                    $email_from = $tbl->esupport;
                    unset($tbl);          
                    $from = $this->section->parametrs->param58 . " " . $_SERVER['HTTP_HOST'];
                    $array_change = array(
                                        'USERNAME' => $nameuser,
                                        'THISNAMESITE' => $_SERVER['HTTP_HOST'],
                                        'SHOP_USERLOGIN' => $this->username,
                                        'SHOP_USERPASSW' => $this->passw
                                    );
            
                    $mails = new plugin_shopmail();
                    $mails->sendmail('reguser', $this->email, $array_change);
                    $mails->sendmail('regadm', '', $array_change);
                    //se_shop_mail("reguser", $req['email'], $array_change, $from, $email_from, $lang);
                    //se_shop_mail("regadm", $email_from, $array_change, $from, $email, $lang);
                    unset($_SESSION['shopcartunreg']);

                    header("Location: ?");
                    exit();
                } else 
                { 
                    $this->view->showExError($this->section->parametrs->param67);
                }
            }
        }
    }
    
    public function validate()
    {
        $res = false;
                                    
        if (empty($this->last_name))
            $this->view->showExError($this->section->parametrs->param61);
        elseif (empty($this->first_name)) 
            $this->view->showExError($this->section->parametrs->param18);
        elseif (($this->section->parametrs->param84 == 'Y') && empty($this->sec_name) && ($this->section->parametrs->param85 == 'Y'))
            $this->view->showExError($this->section->parametrs->param62);
        elseif (($this->section->parametrs->param74 == 'email') && empty($this->email)) 
            $this->view->showExError($this->section->parametrs->param86);
        elseif (($this->section->parametrs->param74 == 'email') && !se_CheckMail($this->email)) 
            $this->view->showExError($this->section->parametrs->param87);
        elseif (($this->section->parametrs->param74 == 'username') && empty($this->username)) 
            $this->view->showExError($this->section->parametrs->param19);     
        elseif (empty($this->passw)) 
            $this->view->showExError($this->section->parametrs->param20);
        elseif ($this->passw != $this->passw1)
            $this->view->showExError($this->section->parametrs->param21);
        else
            $res = true;
                       
        return $res;            
    }
    
}

?>