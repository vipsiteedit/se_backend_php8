<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__MDL_ROOT."/tpl/subpage_13.tpl"; ?>
<!-- Subpage 12. Добавление товара -->
<div class="content e_shopvit_contEditGoods">
    <div class="errorText"><?php echo $errortext ?></div> 
    <form method="post" name="frm" action="" enctype="multipart/form-data" style="margin:0px;">
    <table class="tableTable">
    <tbody> 
    <!--Наименование товара -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param285 ?>*</label</td>
        <td class="fieldInp">
            <input id="titleNews" type="text" name="name" value="<?php echo $_name ?>" class="fieldEnt">
        </td>
    </tr>
    <!-- Код товара -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param300 ?></label</td>
        <td class="fieldInp">
            <input id="titleNews" type="text" name="code" value="<?php echo $_code ?>" class="fieldEnt">
        </td>
    </tr> 
    <!--Артикул -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param292 ?></label</td>
        <td class="fieldInp">
            <input id="titleNews" type="text" name="article" value="<?php echo $_article ?>" class="fieldEnt">
        </td>
    </tr> 
    <!--Производитель -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param288 ?></label</td>
        <td class="fieldInp">
            <input id="titleNews" type="text" name="manufacturer" value="<?php echo $_manufacturer ?>" class="fieldEnt">
        </td>
    </tr>
    <!--В наличии -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param289 ?></label</td>
        <td class="fieldInp">
            <input type="text" name="presence_count" id="presence_count" value="<?php echo $_presence_count ?>" class="fieldEnt">
            <input name="on_check" id="on_check" type="checkbox" value="checkbox" onClick="dis();" class="checkEnt">
            <span class="fieldMess"><?php echo $section->parametrs->param311 ?></span>
        </td>
    </tr>
<script>
<!--
function dis(){
  if(document.frm.on_check.checked){
   document.frm.presence_count.disabled=true;
  }else{
   document.frm.presence_count.disabled=false;
  }
}
-->   
</script>
    <!--Цена -->     
    <tr>
        <td class="titleEnt"><label for="titleNews"><?php echo $section->parametrs->param290 ?></label</td>
        <td class="fieldInp">
            <input id="titleNews" type="text" name="price" value="<?php echo $_price ?>" class="fieldEnt">
        </td>
    </tr> 
    <!--Валюта -->     
    <tr class="classCurr">
        <td class="titleEntCurr"><label for="titleNews"><?php echo $section->parametrs->param294 ?></label</td>
        <td class="fieldInpCurr">
            <select name="curr" value="<?php echo $_curr ?>" class="selEntCurr">
                <option value="rub" class="optEntCurr">
                    <?php echo $section->parametrs->param303 ?>
                </option>
                <option value="dol" class="optEntCurr">
                    <?php echo $section->parametrs->param304 ?>
                </option>
                <option value="eur" class="optEntCurr">
                    <?php echo $section->parametrs->param305 ?>
                </option>
            </select>    
        </td>
    </tr>   
    <!--Группа товара -->     
    <tr class="classGroup">
        <td class="titleEntGr"><label for="titleNews"><?php echo $section->parametrs->param332 ?>*</label</td>
        <td class="fieldInpGr">
            <select name="group" class="selEntGr">
                <option value="not" class="optEntGr">
                    <?php echo $section->parametrs->param316 ?>
                </option>
            <?php foreach($section->shop_group as $record): ?>
                <option class="optEntGr" value="<?php echo $record->id ?>" <?php if($group==$record->id): ?> selected <?php endif; ?>>
                    <?php echo $record->name ?>
                </option>
            
<?php endforeach; ?>    
            </select>
        </td>
    </tr>  
     <!--Краткое описание -->
    <tr class="classNote">
        <td class="titleEntNote" valign="top" colspan="2"><?php echo $section->parametrs->param291 ?></td> 
    </tr>
    <tr>
        <td colspan="2">
            <textarea style="width:100%;" rows="5" cols="40" id="edittar" name="note" class="fieldNote"><?php echo $_note ?>
            </textarea>
        </td>
    </tr>
    <!--Подробное описание -->
    <tr class="classText">
        <td class="titleEntTextNote" valign="top" colspan="2"><?php echo $section->parametrs->param287 ?></td> 
    </tr>
    <tr>
        <td colspan="2">
            <textarea style="width:100%;" rows="10" cols="40" id="edittar" name="text" class="fieldTextNote"><?php echo $_text ?>
            </textarea>
        </td>
    </tr> 
    <!--Добавить рисунок -->    
    <tr>
        <td class="titleEntFile"><label for="userfile"><?php echo $section->parametrs->param286 ?></label></td>
        <td><input id="userfile" type="file" name="userfile" class="userFileClass"></td>
    </tr>
    <!--Область кнопок -->
    <tr class="classBtn"> 
         <td colspan="2" class="BtnClass">
         <input class="buttonSend edSave"  type="submit" name="Save" value="<?php echo $section->parametrs->param283 ?>" >
         <input class="buttonSend edBack" type="button" value="<?php echo $section->parametrs->param284 ?>" onclick='javascript:history.back()'>          
         </td> 
    </tr> 
    </tbody> 
    </table>     
    </form> 
</div>
