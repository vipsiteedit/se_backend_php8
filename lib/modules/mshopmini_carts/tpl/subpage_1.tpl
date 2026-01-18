<div class="content shcart_sub1">
    <div class="errortext sysedit"><?php echo $error ?></div>
    <div class="formsend">
        <form style="margin:0px;" id="pay" name="pay" method="post" action="" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="0" class="tableTable tablePayment">
            <tbody class="tableBody">
                <tr class="tableRow" id="RowEven">
                    <td class="tableClientTitle objTitl">
                        <span class="objectTitleText"><?php echo $section->parametrs->param15 ?><font color="red">*</font> </span>
                    </td> 
                    <td class="tdClientInputInfo objArea">
                        <input type="text" class="Clientinputinfo inputText" name="client_name">
                    </td>
                </tr> 
                <tr class="tableRow" id="RowOdd">
                    <td class="tableClientInfoTitle objTitl">
                        <span class="objectTitleText"><?php echo $section->parametrs->param16 ?><font color="red">*</font></span>
                    </td> 
                    <td class="tdClientInputInfo objArea">
                        <input type="text" class="Clientinputinfo inputText" name="client_email">
                    </td> 
                </tr>
                <!-- tr class="tableRow" id="RowEvens">
                    <td class="tableClientPhone"><?php echo $section->parametrs->param22 ?></td> 
                    <td class="tdClientInputInfo">
                        <input type="text" class="Clientinputinfo" name="client_phone">
                    </td>
                </tr --> 
<?php if(file_exists($__MDL_ROOT."/php/subpage_3.php")) include $__MDL_ROOT."/php/subpage_3.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_3.tpl")) include $__data->include_tpl($section, "subpage_3"); ?>
                </tbody> 
            </table> 
            <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
            <div class="buttonSends">
                <input name="client_pay" type="hidden" value="<?php echo $summa_order ?>">
                <input class="buttonSend payer" 
                    type="submit"
                    name="GoToPay" value="<?php echo $section->parametrs->param14 ?>" <?php echo $disabled_button ?>>
            </div>
        </form> 
    </div>
</div>
