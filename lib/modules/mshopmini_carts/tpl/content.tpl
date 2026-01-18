<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<div class="content shcart" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <a href="<?php echo $section->image ?>" target="_blank">
            <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0" class="contentImage<?php echo $section->style_image ?>" src="<?php echo $section->image ?>">
        </a> 
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText<?php echo $section->style_text ?>"><?php echo $section->text ?></div> 
    <?php endif; ?>
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
    <table border="0" cellPadding="3" cellSpacing="0" class="tableTable" <?php echo $section->style_text ?> id="tableListGoods">
    <tbody class="tableBody">
            <tr class="tableRow" id="tableHeader" vAlign="top">
                <td class="cartitem_art"><?php echo $section->parametrs->param3 ?></td> 
                <td class="cartitem_name"><?php echo $section->parametrs->param4 ?></td> 
                <td class="cartitem_price"><?php echo $section->parametrs->param6 ?></td> 
                <td class="cartitem_cn"><?php echo $section->parametrs->param7 ?></td> 
                <td class="cartitem_summ"><?php echo $section->parametrs->param8 ?></td> 
                <td class="cartitem_del">&nbsp;</td> 
            </tr>
             
            <?php foreach($section->goods as $good): ?>
                <tr class="tableRow" id="<?php echo $good->style ?>">
                    <td class="cartitem_art" ><?php echo $good->article ?></td> 
                    <td class="cartitem_name" ><?php echo $good->name ?></td> 
                    <td class="cartitem_price" align="right"><?php echo $good->price ?></td> 
                    <td class="cartitem_cn" ><input class="cartitem_inputcn" type="text" name="countitem[<?php echo $good->code ?>]" value="<?php echo $good->count ?>" SIZE="3"></td> 
                    <td class="cartitem_summ" align="right"><?php echo $good->summ ?></td> 
                    <td class="cartitem_del" >
                        <input class="buttonSend delCard" id="deletes" name="dellcart[<?php echo $good->code ?>]" type="submit" value="<?php echo $section->parametrs->param9 ?>" title="<?php echo $section->parametrs->param9 ?>">
                    </td> 
                </tr> 
            
<?php endforeach; ?>
            
             
            <tr class="tableRow" id="tableTotal" vAlign="top">
                <td colspan="3">&nbsp;</td>
                <td class="TotalText" align="right"><?php echo $section->parametrs->param10 ?>:</td>
                <td class="cartitem_summ" align="right"><?php echo $summa_order ?>&nbsp;<?php echo $section->parametrs->param2 ?></td>
                <td >&nbsp;</td>
            </tr>
            <tr id="trpusto"><td colspan="6"></td></tr>
            <tr id="tableButtons">
                <td colspan="6" align="left">
                    <span class="ButtonBack">
                    <INPUT class="buttonSend" id="GoGoods" type="button" value="<?php echo $section->parametrs->param11 ?>" >
                    </span>
                    <span class="ButtonClear"><input class="buttonSend" id="clear" name="shcart_clear" type="submit" value="<?php echo $section->parametrs->param13 ?>" <?php echo $disabled_button ?>></span>
                    <span class="ButtonReload"><input class="buttonSend" id="reload" name="shcart_reload" type="submit" value="<?php echo $section->parametrs->param12 ?>" <?php echo $disabled_button ?>></span>
                </td> 
            </tr> 
        
    </tbody>
    </table>
    </form> 
    <?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_1.tpl")) include $__data->include_tpl($section, "subpage_1"); ?>
     
 
</div> 
<script >
$(document).ready(function(){
    $('#tableListGoods .cartitem_inputcn').keypress(function(e){
        if(e.keyCode==13){
            return false;
        }
    });
    
    $("#GoGoods").on('click', function(){
    <?php if(($comeback!='')): ?>
        window.location.replace('/<?php echo $comeback ?>/');
    <?php else: ?>
        window.history.back();
    <?php endif; ?>    
    });
});                   
</script>
