<?php

function seAuthorizeForm()
{
    $lang = se_getLang();
    $ymlfile = SE_CORE . 'i18n/' . $lang . '.yml';
    if (file_exists($ymlfile)) {
        $ymlres = seYAML::Load($ymlfile);
        foreach ($ymlres as $classname => $mess) ;
    }
    $res = '<div id="authirizeblock" style="width: 100%;">'
        . '<div class="base_auth" style="margin-right:auto; margin-left:auto; width:300px;">'
        . '<b style="font-size:14; color:#ff0000;">' . $mess['mes_noauthor'] . '</b></br></br></br>';
    if (!intval(seData::getInstance()->prj->vars->auth_check)) {
        $res .= '<table border="0" class="pswtable" width="250">'
            . '<tbody class="tableBody">'
            . '<tr class="tableRow" id="tableHeader" valign="top">'
            . '<td colspan="2" width="300px">' . $mess['mes_authorized'] . '</td>'
            . '<form action="" method="post">'
            . '<tr class="tableRow" id="tableRowEven" valign="top">'
            . '<td width="100px">' . $mess['mes_login'] . '</td><td>'
            . '<input style="width:150px;" type="text" name="authorlogin" value=""></td></tr>'
            . '<tr class="tableRow" id="tableRowEven" valign="top">'
            . '<td>' . $mess['mes_password'] . '</td>'
            . '<td><input style="width:150px;" type="password" name="authorpassword" value=""></td></tr>'
            . '<tr class="tableRow" id="tableRowEven" valign="top">'
            . '<td colspan="2">'
            . '<input type="submit" class="buttonSend" name="authorize" value="' . $mess['mes_login_next'] . '">'
            . '<input class="buttonSend" onclick="window.history.back();" type="button" value="' . $mess['mes_login_back'] . '"></td></tr>'
            . '</form>'
            . '</tbody>'
            . '</table>';
    }

    $res .= '</div></div>';
    return $res;
}

function seAuthorizeError()
{
    $lang = se_getLang();
    $ymlfile = SE_CORE . 'i18n/' . $lang . '.yml';
    if (file_exists($ymlfile)) {
        $ymlres = seYAML::Load($ymlfile);
        foreach ($ymlres as $classname => $mess) ;
        echo '<script language="JavaScript">alert("', $mess['mes_authorerror'], '")</script>';
    }
}

function seAuthorize($authorizeform)
{

    $form = replace_link(seData::getInstance()->prj->vars->authorizeform);
    if (!empty($form)) {
        @list($in, $out) = explode('{&&}', $form);
        if (!seUserGroup() && !seUserId())
            return $in;
        else
            return str_replace('[NAMEUSER]', seUserName(), $out);
    }
}
