<footer:js>
<serv>
[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
<script type="text/javascript" >
    $(function(){              
        $("#date").datepicker();                                                                                                                      
    });                                                                                                                             
</script>                                                                                                                             
</serv>
[lnk:ui/css/ui-lightness/jquery.ui.min.css]
</footer:js>
<div class="content cont_guest">
  <div id="subpage1">
    <form action="" enctype="multipart/form-data" method="post">
        
 <table class="tableTable" border="0">
    <tbody>
        <tr>
            <td class="tablerow"><label class="text datettl">[lang023]</label></td>
            <td class="tablerow"> 
                <input class="inp input" id="date" type="text" maxlength="10" name="date" value="{$postdate}">
                <div class="error<SE> sysedit</SE>">{$dateerror}<SE>[lang009]</SE></div> 
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow"><label class="text namettll">[lang027]</label></td> 
            <td class="tablerow">
                <input class="inp input" id="name" type="text" name="usrname" value="{$name}">
                <div class="error<SE> sysedit</SE>">{$nameerror}<SE>[lang009]</SE></div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow"><label class="text usrmailttl">[lang026]</label></td> 
            <td class="tablerow">
                <input class="inp input" id="usrmail" type="text" name="usrmail" value="{$mail}">
                <div class="error<SE> sysedit</SE>">{$mailerror}<SE>[lang009]</SE></div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2"> 
                <label class="text usernotettl">[lang011]</label><br> 
                <div><textarea class="inp textarea" id="note" name="note" rows="7" maxlength="[param35]" cols="30">{$note}</textarea></div>
                <div class="error<SE> sysedit</SE>">{$noteerror}<SE>[lang009]</SE></div> 
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2"> 
                <label class="text adminaswerttl">[lang010]</label><br> 
                <div><textarea class="inp textarea" id="admtxt" name="admtxt" rows="7" cols="30">{$adm_text}</textarea><div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox2" type="checkbox" name="active" {$adm_active}>
                <label class="text blockuserttl">Подтвердить</label>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox1" type="checkbox" name="del">
                <label class="text delmsgttl">[lang012]</label>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox2" type="checkbox" name="block">
                <label class="text blockuserttl">[lang022]</label>
            </td> 
        </tr> 
        <tr> 
            <td colspan="2" class="buttonBlock">
                <input class="buttonSend saveedit" type=<SE>"button"</SE><SERV>"submit"</SERV> name="saveEdit[part.id]" value="[lang021]"<SE> onclick="document.location='[thispage.link]';"</SE>> 
                <input class="buttonSend blockedit" type="button" onclick="document.location = '[link.subpage=2]'" name="saveEdit[part.id]" value="[lang020]"> 
            </td> 
        </tr> 
    </tbody> 
</table> 
    </form>
  </div>
</div>

