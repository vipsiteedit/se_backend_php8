<?php

//$smilesURL="http://e-stile.ru/public/smiles/";
$iconsURL="http://e-stile.ru/public/icons";
$frmsmiles="";
$i=1;
while(file_exists("skin/forum/smile".sprintf("%03d", $i).".gif")) {
  $frmsmiles.="<div id=erm_SmilesBlock><a href=\"javascript:addSmile('[smile".sprintf("%03d", $i)."]');\">
  <img border=\"0\" src=\"".$smilesURL."/smile".sprintf("%03d", $i).".gif\"></a></div>
  ";
  $i++;
}

if (!isset($text)) $text="";
$forum_echo.="
  <table class=tableERM><tbody class=tableBody><tr><td id=erm_Buttons>
  <div id=erm_ClrManag>
  <input class=inputForum type='text' id='clr' readonly maxlength=7>
  <input class=buttonForum id=erm_PopUp type='button' onclick='showtable()' value='Cписок цветов'>
  <div id='clr_div' style='background-color: ffffff;'></div><script> artclr();</script></div>
  <DIV id=erm_allButtons>
  <button id=erm_ButtonsBlock onclick=\"button('b');return false;\"><img src='$iconsURL/b.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('em');return false;\"><img src='$iconsURL/i.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('u');return false;\"><img src='$iconsURL/u.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"www('url');return false;\"><img src='$iconsURL/url.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"www('mail');return false;\"><img src='$iconsURL/mail.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"www('img');return false;\"><img src='$iconsURL/img.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('ul');return false;\"><img src='$iconsURL/ul.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('ol');return false;\"><img src='$iconsURL/ol.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('center');return false;\"><img src='$iconsURL/center.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('sup');return false;\"><img src='$iconsURL/sup.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('sub');return false;\"><img src='$iconsURL/sub.gif'></button>
  <button id=erm_ButtonsBlock onclick=\"button('code');return false;\"><img src='$iconsURL/code.gif'></button>
  </div></td>
  <td rowspan=2 id=erm_Smiles><DIV id=erm_allSmiles>$frmsmiles</div></td></tr>
  <tr><td id=erm_TextArea>
  <textarea name='text' maxlength=25  id=erm_AreaForText onselect='doSaveSel();' onChange='countchar();' onkeypress='countchar();' onkeydown='countchar();' onkeyup='countchar();' onFocus='countchar();' onBlur='countchar();' onMouseOut='countchar();'>".@stripslashes($text)."</textarea><br>
  </td></tr>
  <TR><TD colspan=2>&nbsp;</TD></TR>
  <TR><TD colspan=2><div id=ERM_maxlen>Максимальная длина сообщения: $msgMaxLength символов.
  					Еще можно ввести: <input type='text' id='txtCount' value='$msgMaxLength' readonly>
                    </div></TD></TR>

";  //</tbody></table>

//Количество символов;
$forum_echo.="
<script>
function countchar() {
  col=$msgMaxLength-form.erm_AreaForText.value.length;
  if (col<0) col=0;
  form.txtCount.value=col;
}

function asubmit() {
  if (form.erm_AreaForText.value.length>$msgMaxLength){ alert('Длина сообщения ('+form.erm_AreaForText.value.length+') превышает максимально допустимую ('+$msgMaxLength+')!'); return false;}
  if (form.erm_AreaForText.value.length==0){ alert('Поле сообщения пустое!'); return false;}
  if (form.erm_AreaForText.value.length<=$msgMaxLength){ form.submit();}
}

</script>
";
?>