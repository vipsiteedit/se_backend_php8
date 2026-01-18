<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<div class="content shcart" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <a href="[part.image]" target="_blank">
            <img alt="[part.image_alt]" title="[part.image_title]" border="0" class="contentImage[part.style_image]" src="[part.image]">
        </a> 
    </noempty>
    <noempty:part.text>
        <div class="contentText[part.style_text]">[part.text]</div> 
    </noempty>
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
    <table border="0" cellPadding="3" cellSpacing="0" class="tableTable" [contentstyle_txt] id="tableListGoods">
    <tbody class="tableBody">
            <tr class="tableRow" id="tableHeader" vAlign="top">
                <td class="cartitem_art">[param3]</td> 
                <td class="cartitem_name">[param4]</td> 
                <td class="cartitem_price">[param6]</td> 
                <td class="cartitem_cn">[param7]</td> 
                <td class="cartitem_summ">[param8]</td> 
                <td class="cartitem_del">&nbsp;</td> 
            </tr>
            <serv> 
            <repeat:goods name=good>
                <tr class="tableRow" id="[good.style]">
                    <td class="cartitem_art" >[good.article]</td> 
                    <td class="cartitem_name" >[good.name]</td> 
                    <td class="cartitem_price" align="right">[good.price]</td> 
                    <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[[good.code]]" value="[good.count]" SIZE="3"></td> 
                    <td class="cartitem_summ" align="right">[good.summ]</td> 
                    <td class="cartitem_del" >
                        <input class="buttonSend delCard" id="deletes" name="dellcart[[good.code]]" type="submit" value="[param9]" title="[param9]">
                    </td> 
                </tr> 
            </repeat:goods>
            </serv>
            <se> 
            <tr class="tableRow" id="tableRowEven">
                <td class="cartitem_art" >13456</td> 
                <td class="cartitem_name" >For Bookkeepers</td> 
                <td class="cartitem_price" align="right">20&nbsp;000.00</td> 
                <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[2]" value="2" SIZE="3"></td> 
                <td class="cartitem_summ" align="right">40&nbsp;000.00</td> 
                <td class="cartitem_del" ><input class="buttonSend delCard" id="deletes" name="dellcart[2]" type="submit" value="[param9]" title="[param9]"></td> 
            </tr> 
            <tr class="tableRow" id="tableRowOdd">
                <td class="cartitem_art" >7654</td> 
                <td class="cartitem_name" >The electronic manager</td> 
                <td class="cartitem_price" align="right">10&nbsp;000.00</td> 
                <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[5]" value="1" SIZE="3"></td> 
                <td class="cartitem_summ" align="right">10&nbsp;000.00</td> 
                <td class="cartitem_del" ><input class="buttonSend delCard" id="deletes" name="dellcart[5]" type="submit" value="[param9]" title="[param9]"></td> 
            </tr> 
            <tr class="tableRow" id="tableRowEven">
                <td class="cartitem_art" >777</td> 
                <td class="cartitem_name" >Кросовки "AID"</td> 
                <td class="cartitem_price" align="right">250.00</td> 
                <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[2]" value="2" SIZE="3"></td> 
                <td class="cartitem_summ" align="right">500.00</td> 
                <td class="cartitem_del" ><input class="buttonSend delCard" id="deletes" name="dellcart[2]" type="submit" value="[param9]" title="[param9]"></td> 
            </tr> 
            <tr class="tableRow" id="tableRowOdd">
                <td class="cartitem_art" >00210</td> 
                <td class="cartitem_name" >Куртка "Cool"</td> 
                <td class="cartitem_price" align="right">8&nbsp;999.99</td> 
                <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[5]" value="1" SIZE="3"></td> 
                <td class="cartitem_summ" align="right">8&nbsp;999.99</td> 
                <td class="cartitem_del" ><input class="buttonSend delCard" id="deletes" name="dellcart[5]" type="submit" value="[param9]" title="[param9]"></td> 
            </tr> 
            </se> 
            <tr class="tableRow" id="tableTotal" vAlign="top">
                <td colspan="3">&nbsp;</td>
                <td class="TotalText" align="right">[param10]:</td>
                <td class="cartitem_summ" align="right">[$summa_order]&nbsp;[se."40&nbsp678.00 руб"]<serv>[param2]</serv></td>
                <td >&nbsp;</td>
            </tr>
            <tr id="trpusto"><td colspan="6"></td></tr>
            <tr id="tableButtons">
                <td colspan="6" align="left">
                    <span class="ButtonBack">
                    <INPUT class="buttonSend" id="GoGoods" type="button" value="[param11]" >
                    </span>
                    <span class="ButtonClear"><input class="buttonSend" id="clear" name="shcart_clear" type="submit" value="[param13]" [$disabled_button]></span>
                    <span class="ButtonReload"><input class="buttonSend" id="reload" name="shcart_reload" type="submit" value="[param12]" [$disabled_button]></span>
                </td> 
            </tr> 
        
    </tbody>
    </table>
    </form> 
    [subpage name=1]
    <SE> 
    <br clear="all">
        <div class="sysedit" style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black;">
            <b>[lang002]<br>[lang003]&nbsp;[param1]</b> 
        </div> 
    </SE> 
 
</div> 
<script >
$(document).ready(function(){
    $('#tableListGoods .cartitem_inputcn').keypress(function(e){
        if(e.keyCode==13){
            return false;
        }
    });
    
    $("#GoGoods").on('click', function(){
    <if: ({$comeback}!='')>
        window.location.replace('/{$comeback}/');
    <else>
        window.history.back();
    </if>    
    });
});                   
</script>
