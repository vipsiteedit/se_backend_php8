<div class="content shcart_sub1">
    <div class="errortext sysedit">{$error}[se."Загружаемый файл превышает размер: 500Кб"]</div>
    <div class="formsend">
        <form style="margin:0px;" id="pay" name="pay" method="post" action="" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="0" class="tableTable tablePayment">
            <tbody class="tableBody">
                <tr class="tableRow" id="RowEven">
                    <td class="tableClientTitle objTitl">
                        <span class="objectTitleText">[param15]<font color="red">*</font> </span>
                    </td> 
                    <td class="tdClientInputInfo objArea">
                        <input type="text" class="Clientinputinfo inputText" name="client_name">
                    </td>
                </tr> 
                <tr class="tableRow" id="RowOdd">
                    <td class="tableClientInfoTitle objTitl">
                        <span class="objectTitleText">[param16]<font color="red">*</font></span>
                    </td> 
                    <td class="tdClientInputInfo objArea">
                        <input type="text" class="Clientinputinfo inputText" name="client_email">
                    </td> 
                </tr>
                <!-- tr class="tableRow" id="RowEvens">
                    <td class="tableClientPhone">[param22]</td> 
                    <td class="tdClientInputInfo">
                        <input type="text" class="Clientinputinfo" name="client_phone">
                    </td>
                </tr --> 
[subpage name=3]
                </tbody> 
            </table> 
            [subpage name=license]
            <div class="buttonSends">
                <input name="client_pay" type="hidden" value="{$summa_order}">
                <input class="buttonSend payer" 
                    <serv>type="submit"</serv><se>type="button" onclick="document.location.href='[link.subpage=2]'"</se>
                    name="GoToPay" value="[param14]" {$disabled_button}>
            </div>
        </form> 
    </div>
</div>
