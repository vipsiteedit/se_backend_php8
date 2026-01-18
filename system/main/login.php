<?php
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
session_start();
chdir($_SERVER['DOCUMENT_ROOT'] . '/');
error_reporting(0);
require 'system/main/init.php';
check_session(false);

$username = seUserName();
$userGroup = seUserGroup();
if (isset($_GET['target'])) {
    list($_SESSION['SE_BACK_URL']) = explode('#', $_SESSION['SE_BACK_URL']);
    $_SESSION['SE_BACK_URL'] .= '#' . $_GET['target'];
}

function getSocial($provider)
{
    if (stripos($provider, 'facebook')) {
        return 'facebook.png';
    } elseif (stripos($provider, 'twitter')) {
        return 'twitter.png';
    } elseif (stripos($provider, 'vkontakte')) {
        return 'vkontakte.png';
    } elseif (stripos($provider, 'google')) {
        return 'google.png';
    } elseif (stripos($provider, 'mail')) {
        return 'mailruapi.png';
    } elseif (stripos($provider, 'odnoklassniki')) {
        return 'odnoklassniki.png';
    }
}

;

function seGenRandomPassword($len = 6, $char_list = 'a-z,0-9')
{
    $chars = array();
    // предустановленные наборы символов
    $chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
    $chars['A-Z'] = strtoupper($chars['a-z']);
    $chars['0-9'] = '0123456789';
    $chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';
    $charset = $password = '';
    if (!empty($char_list)) {
        $char_types = explode(',', $char_list);
        foreach ($char_types as $type) {
            if (array_key_exists($type, $chars)) {
                $charset .= $chars[$type];
            } else {
                $charset .= $type;
            }
        }
    }
    for ($i = 0; $i < $len; $i++) {
        $password .= $charset[rand(0, strlen($charset) - 1)];
    }
    return $password;
}


$sql = " CREATE TABLE IF NOT EXISTS `se_loginza` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `uid` varchar(50) NOT NULL,
        `user_id` int(10) unsigned NOT NULL,
        `identity` varchar(255) NOT NULL,
        `provider` varchar(255) NOT NULL,
        `email` varchar(20) default NULL,
        `photo` varchar(255),
        `real_user_id` int(10) unsigned NULL,
        `updated_at` timestamp NOT NULL ,
        `created_at` timestamp NOT NULL ,
        PRIMARY KEY  (`id`),
        KEY user_id (user_id),
        KEY real_user_id (real_user_id)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
se_db_query($sql);

//error_reporting(E_ALL);
require_once SE_LIBS . 'loginza/loginzaapi.class.php';
require_once SE_LIBS . 'loginza/loginzauserprofile.class.php';
$LoginzaAPI = new LoginzaAPI();
$err = '';
$lang = se_getLang();
$uri = $_SERVER["REQUEST_URI"];
$host = urlencode(_HOST_ . $uri);
$url = 'http://loginza.ru/api/widget?token_url=' . $host . '&lang=' . $lang;
unset($list);
if (isset($_GET['sending'])) {
    $key = 1;
    $user_loginza = 1;
}
$flag = 0;
$list = array();
// проверка переданного токена
if (!empty($_POST['token']) && !(seUserGroup() || seUserId())) {
    if (isset($_SESSION['loginza'])) unset($_SESSION['loginza']);
    // получаем профиль авторизованного пользователя
    $UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);
    //print_r($UserProfile);
    $req['username'] = 'login' . $UserProfile->uid;
    $req['last_name'] = $UserProfile->name->last_name;
    $req['first_name'] = $UserProfile->name->first_name;
    if (($req['last_name'] == '') && ($req['first_name'] == '')) {
        list($req['first_name'], $req['last_name']) = explode(' ', $UserProfile->name->full_name);
    }
    $req['key'] = $UserProfile->uid;
    $req['identity'] = $UserProfile->identity;
    $req['provider'] = $UserProfile->provider;
    $req['email'] = $UserProfile->email;
    $req['avatar'] = $UserProfile->photo;
    $uid = $req['key'];
    $tlb = new seTable('se_loginza');
    $tlb->select('user_id, uid');
    $tlb->where('uid=?', $uid);
    $list = $tlb->fetchOne();
    unset($tlb);
    if (($req['email'] == '') && (!$list)) {
        $subpage = 1;
    } else {
        $passw = SeGenRandomPassword();
        $req['confirm'] = $req['passw'] = $passw;
        $flag = 1;
    }
} elseif (isset($key)) {
    $req['key'] = $_GET['keys'];
    $req['identity'] = $_GET['inden'];
    $req['provider'] = $_GET['prov'];
    $req['username'] = 'login' . $req['key'];
    $passw = seGenRandomPassword();   //пароль
    $req['confirm'] = $req['passw'] = $passw;
    $req['last_name'] = $_GET['lnames'];
    $req['first_name'] = $_GET['fnames'];
    $req['email'] = $_GET['emails'];
    $req['avatar'] = $_GET['ava'];
    unset($key);
    $flag = 1;
} elseif (isset($_GET['logout'])) {
    // выход пользователя
    unset($_SESSION['loginza']);
    check_session(true);
    header("Location: ?");
}

//  echo '['.seUserId().']';
// echo $subpage;
if ($flag == 1) {
    // запоминаем профиль пользователя в сессию или создаем локальную учетную запись пользователя в БД
    unset($list);
    $uid = $req['key'];
    $tlb = new seTable('se_loginza');
    $tlb->select('id, user_id, real_user_id,uid, photo');
    $tlb->where("uid='?'", $uid);
    $list = $tlb->fetchOne();
    $user = new seUser();
    if (!$list) {
        $new_user_id = $user->registration($req, 0, 1);
        $_SESSION['loginza']['user_id'] = $new_user_id;
        if ($new_user_id > 0) {
            $tlb->insert;
            $tlb->uid = $req['key'];
            $tlb->user_id = $new_user_id;
            $tlb->real_user_id = $new_user_id;
            $tlb->identity = $req['identity'];
            $tlb->provider = $req['provider'];
            $tlb->email = $req['email'];
            $tlb->photo = $req['avatar'];
            $tlb->save();
            //изменить username
            $user->update('username', "'login" . $new_user_id . "'");
            $user->where("id=?", $new_user_id);
            $user->save();

            $tlb->select('max(id) as id');
            $list = $tlb->fetchOne();
            $_SESSION['loginza']['is_photo'] = $list['id'];
            if (seUserGroup() > 0) {
                $men = seUserId();
                $tlb->update('user_id', $men);
                $tlb->where("uid='?'", $uid);
                $tlb->save();
                $_SESSION['loginza']['user_id'] = $men;
            } else {
                //взять username из БД
                $name = $user->select('username')->find($new_user_id);
                $who = $name['username'];
                //отправить письмо
                $text = "Регистрация на сайте ";
                $client_mail = 'Здравствуйте,  ' . $req['first_name'] . '!\r\n \r\nРегистрация прошла успешно. Ваши данные:\r\nЛогин: ' . $who . '\r\nПароль: ' . $req['passw'];
                $client_mail = str_replace('\r\n', '<br>', $client_mail);
                $from = "=?utf-8?b?" . base64_encode("$text") . "?= " . $_SERVER['HTTP_HOST'] . '<noreply@' . $_SERVER['HTTP_HOST'] . '>';
                $mailsend = new plugin_mail($text, $req['email'], $from, $client_mail, 'text/html');
                $mailsend->sendfile();
            }
        }

    } else {
        if (seUserGroup() > 0) {
            $men = seUserId();
            $tlb->update('user_id', $men);
            $tlb->where("uid='?'", $uid);
            $tlb->save();
            $_SESSION['loginza']['user_id'] = $men;
        } else {
            @$_SESSION['loginza']['user_id'] = $list['user_id'];
        }
        @$_SESSION['loginza']['is_photo'] = $list['id'];
    }
    //авторизация на сайте
    $new_user_id = $_SESSION['loginza']['user_id'];
    if (($new_user_id > 0) && (seUserGroup() == 0)) {
        $arr = $user->select('*')->find($new_user_id);
        $person = $user->getPerson();
        check_session(true);   //ЗАКРЫТЬ СЕССИЮ
        $auth['IDUSER'] = $new_user_id;
        $auth['GROUPUSER'] = 1;
        $login = $auth['USER'] = $person->first_name . " " . $person->last_name;
        $auth['AUTH_USER'] = $user->username;
        $auth['AUTH_PW'] = $user->password;
        $auth['ADMINUSER'] = '';
        check_session(false, $auth); //ОТКРЫТЬ СЕССИЮ
        $_SESSION['loginza']['is_auth'] = 1;
        header("Location: " . $_SESSION['SE_BACK_URL']);
    }
}
//echo seUserGroup();
if (seUserGroup() > 0) {
    $_SESSION['loginza']['is_auth'] = 1;
    $_SESSION['loginza']['user_id'] = seUserId();
} else {
    //if (isset($_SESSION['loginza'])) unset($_SESSION['loginza']);
}

if (!empty($_SESSION['loginza']['is_auth'])) {
    $id_avatar = $_SESSION['loginza']['is_photo'];
    $avatar = new seTable('se_loginza');
    $avatar->select('id, photo');
    $avatar->where("id=?", $id_avatar);
    $lists = $avatar->fetchOne();

    //поиск зарегеных аккаунтов
    $extra_avatar = '';
    $new_user_id = $_SESSION['loginza']['user_id'];
    $account = new seTable('se_loginza', 'sl');
    $account->select('id, user_id, real_user_id, provider, photo');
    $account->where("user_id='$new_user_id'");
    $acc = $account->getList();
    foreach ($acc as $item) {
        $fio = new seUser();
        $fio->select('*')->find($item['real_user_id']);
        $pers = $fio->getPerson();
        $fio = $pers->first_name . " " . $pers->last_name;
        $idd = $item['id'];
        if (!empty($item['provider'])) {
            $extra_avatar .= '<img class="icon_img" src="/lib/loginza/' . getSocial($item['provider']) . '" alt="' . $fio . '" title="' . $fio . '">';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="/lib/js/bootstrap/css/bootstrap.min.css" id="pageCSS" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/lib/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/lib/js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="/lib/js/bootstrap/bootstrap.init.js"></script>
    <script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
    <style>
        .seopenid .loginblock {
            width: 165px;
        }

        .seopenid .loginblock .title {
            margin-bottom: 10px;
            display: block;
        }

        .seopenid .loginblock .authorlogin {
            margin-bottom: 2px;
            vertical-align: middle;
        }

        .seopenid .loginblock .authorpassw {
            margin-bottom: 2px;
            vertical-align: middle;
        }

        .seopenid .loginblock .loginsend {
            margin-bottom: 10px;
            margin-right: 15px;
            float: left;
        }

        .seopenid .loginblock .authorSave {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .seopenid .loginblock .authorSave #authorSaveCheck {
            margin: 5px;
            margin-left: 0px;
            width: 13px;
            height: 13px;
            vertical-align: middle;
        }

        .seopenid .loginblock .authorSave .authorSaveWord {
            vertical-align: middle;
        }

        .seopenid .loginblock .links.regi {
            margin-top: 5px;
        }

        .seopenid .loginblock .links.remem {
            margin-top: 5px;
        }

        .seopenid .loginblock .openIdBlock {
            padding-top: 15px;
            border-top: 1px dotted #b0b0b0;
            margin-top: 15px;
        }

        .seopenid .loginblock .loginblocktxt {
            margin-bottom: 5px;
            display: block;
            vertical-align: middle;
        }

        .seopenid .logoutblock .title {
            margin-bottom: 5px;
            display: block;
        }

        .seopenid .logoutblock .invitation {
            margin-bottom: 10px;
        }

        .seopenid .logoutblock .invitation .username {
            font-weight: bold;
            display: block;
            clear: both;
        }

        .seopenid .logoutblock .soc_link {
            display: inline-block;
        }

        .seopenid .logoutblock .soc_link_a {
            margin-bottom: 10px;
            display: block;
        }

        .seopenid .logoutblock .soc_link_a a {
            display: block;
            clear: both;
        }

        .seopenid .logoutblock .soc_link .extra_images {
            display: inline-block;
            clear: both;
        }

        .seopenid .logoutblock .soc_link .extra_images .extra_title {
            margin-bottom: 3px;
            display: block;
        }

        .seopenid .logoutblock .soc_link .extra_images img {
            margin-top: 3px;
            margin-right: 3px;
            float: left;
        }

        .seopenid .logoutblock .links {
            margin-top: 10px;
            display: block;
            clear: both;
        }

        .glyphicon {
            top: 0;
        }
    </style>
</head>
<body>
<div class="seopenid" style="width: 305px;">
    <div class="logoutblock">
        <?php if (!empty($_SESSION['loginza']['is_auth']) && seUserGroup()): ?>
            <?php if ($lists["photo"]): ?>
                <span class="title"><img class="title_img" src="<?php echo $lists["photo"] ?>"></span>
            <?php else: ?>
                <span class="title"><img class="title_img" src="<?php echo "/system/main/img/nofoto.gif" ?>"></span>
            <?php endif ?>
            <div class="invitation">Приветствуем,<span class="username"><?php echo seUserName() ?></span></div>
            <?php if (seUserGroup() != 3): ?>
                <div class="soc_link"><span class="soc_link_a"><a href="<?php echo $url ?>" target=_parent>Связать аккаунт</a>
                        <?php if ($extra_avatar): ?>
                            <a href="?id_account">Отвязать аккаунт</a>
                        <?php endif ?>
        </span>
                    <?php if ($extra_avatar): ?>
                        <span class="extra_images"><span
                                class="extra_title">Доступ</span><?php echo $extra_avatar ?></span>
                    <?php endif ?>
                </div>
            <?php endif ?>
            <a class="links" href="?logout">Выход</a>
        <?php else: ?>
        <div class="openIdlogin">
            <form role="form" style="margin: 0;" action="<?php echo $_SESSION['SE_BACK_URL'] ?>" method="post"
                  target=_parent>
                <span class="title">Авторизация</span>
                <input type="hidden" value="true" name="authorize">
                <div class="form-group">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-user input-group-addon"></span>
                        <input class="form-control" name="authorlogin" value="Логин"
                               onfocus="if(this.value=='Логин') this.value='';"
                               onblur="if(this.value=='') this.value='Логин';">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="glyphicon glyphicon-lock input-group-addon"></span>
                        <input class="authorpassw form-control" type="password" name="authorpassword" value="Пароль"
                               onfocus="if(this.value=='Пароль') this.value='';"
                               onblur="if(this.value=='') this.value='Пароль';">
                    </div>
                </div>
                <div class="form-group">
                    <input class="buttonSend loginsend btn btn-primary btn-block" type="submit" value="Войти"
                           name="GoToAuthor">
                </div>
                <div class="authorSave checkbox">
                    <label>
                        <input id="authorSaveCheck" type="checkbox" value="1" name="authorSaveCheck"> Запомнить</label>
                </div>
                <div class="">
                    <label><a href="/remember/">Забыли пароль?</a></label>
                </div>
                </section>
            </form>
            <br>
            <!--a class="links regi" href="">Регистрация</a-->
            <!--a class="links remem" href="">Забыли пароль</a-->
        </div>
        <div class="openIdBlock form-group">
            <!-- $list = 'facebook,vkontakte,odnoklassniki,twitter,google,mailruapi'; -->
            <label class="loginblocktxt">Войти как пользователь</label>
            <div>
                <a href="<?php echo $url . '&provider=facebook' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                          src="/lib/loginza/facebook-lg.png"></a>
                <a href="<?php echo $url . '&provider=vkontakte' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                           src="/lib/loginza/vkontakte-lg.png"></a>
                <a href="<?php echo $url . '&provider=mailruapi' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                           src="/lib/loginza/mail-lg.png"></a>
                <a href="<?php echo $url . '&provider=twitter' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                         src="/lib/loginza/twitter-lg.png"></a>
                <a href="<?php echo $url . '&provider=odnoklassniki' ?>" class="loginzain" target=_parent><img
                        class="imgs" src="/lib/loginza/odnoklassniki-lg.png"></a>
                <a href="<?php echo $url . '&provider=google' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                        src="/lib/loginza/google-lg.png"></a>
                <a href="<?php echo $url . '&provider=yandex' ?>" class="loginzain" target=_parent><img class="imgs"
                                                                                                        src="/lib/loginza/yandex-lg.png"></a>
            </div>
        </div>
        <!--a href="<?php echo $url ?>" class="loginzain">Авторизация</a></div-->
    </div>
    <?php endif ?>
</div>
</div></body>
</html>