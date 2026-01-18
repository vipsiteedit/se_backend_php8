<footer:js>

[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
<script type="text/javascript" >
    $(function(){              
        $("#date").datepicker();                                                                                                                      
    });                                                                                                                             
</script>                                                                                                                             

[lnk:ui/css/ui-lightness/jquery.ui.min.css]
</footer:js>
<div class="content cont_guest">
  <div id="subpage1">
    <form action="" enctype="multipart/form-data" method="post">
        
 <table class="tableTable" border="0">
    <tbody>
        <tr>
            <td class="tablerow"><label class="text datettl"><?php echo $section->language->lang023 ?></label></td>
            <td class="tablerow"> 
                <input class="inp input" id="date" type="text" maxlength="10" name="date" value="<?php echo $postdate ?>">
                <div class="error"><?php echo $dateerror ?></div> 
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow"><label class="text namettll"><?php echo $section->language->lang027 ?></label></td> 
            <td class="tablerow">
                <input class="inp input" id="name" type="text" name="usrname" value="<?php echo $name ?>">
                <div class="error"><?php echo $nameerror ?></div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow"><label class="text usrmailttl"><?php echo $section->language->lang026 ?></label></td> 
            <td class="tablerow">
                <input class="inp input" id="usrmail" type="text" name="usrmail" value="<?php echo $mail ?>">
                <div class="error"><?php echo $mailerror ?></div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2"> 
                <label class="text usernotettl"><?php echo $section->language->lang011 ?></label><br> 
                <div><textarea class="inp textarea" id="note" name="note" rows="7" maxlength="<?php echo $section->parametrs->param35 ?>" cols="30"><?php echo $note ?></textarea></div>
                <div class="error"><?php echo $noteerror ?></div> 
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2"> 
                <label class="text adminaswerttl"><?php echo $section->language->lang010 ?></label><br> 
                <div><textarea class="inp textarea" id="admtxt" name="admtxt" rows="7" cols="30"><?php echo $adm_text ?></textarea><div>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox2" type="checkbox" name="active" <?php echo $adm_active ?>>
                <label class="text blockuserttl">Подтвердить</label>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox1" type="checkbox" name="del">
                <label class="text delmsgttl"><?php echo $section->language->lang012 ?></label>
            </td> 
        </tr> 
        <tr> 
            <td class="tablerow" colspan="2">
                <input class="cbox inp" id="cbox2" type="checkbox" name="block">
                <label class="text blockuserttl"><?php echo $section->language->lang022 ?></label>
            </td> 
        </tr> 
        <tr> 
            <td colspan="2" class="buttonBlock">
                <input class="buttonSend saveedit" type="submit" name="saveEdit<?php echo $section->id ?>" value="<?php echo $section->language->lang021 ?>"> 
                <input class="buttonSend blockedit" type="button" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>'" name="saveEdit<?php echo $section->id ?>" value="<?php echo $section->language->lang020 ?>"> 
            </td> 
        </tr> 
    </tbody> 
</table> 
    </form>
  </div>
</div>

