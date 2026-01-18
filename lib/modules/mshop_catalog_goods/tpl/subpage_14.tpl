<!-- Subpage 14. Добавление группы товара -->
<div class="content e_shopvit_contEditGroup">
    <div class="errorText"><?php echo $errortext ?></div> 
    <form method="post" name="frm" action="" enctype="multipart/form-data" style="margin:0px;">
    <table class="tableTableGroup">
    <tbody>
    <!-- Родительская группа -->     
    <tr>
        <td class="titleGroup"><label for="titleNews"><?php echo $section->parametrs->param293 ?></label</td>
        <td class="fieldGroupEnt">
            <select name="group">
                <option value="">
                    <?php echo $section->parametrs->param316 ?>
                </option>
            <?php foreach($section->shop_group as $record): ?>
                <option value="<?php echo $record->id ?>" <?php if($upid==$record->id): ?> selected <?php endif; ?>>
                    <?php echo $record->name ?>
                </option>
            
<?php endforeach; ?>    
            </select>
        </td>
    </tr>   
    <!--Наименование группы товара -->     
    <tr>
        <td class="titleGroup"><label for="titleNews"><?php echo $section->parametrs->param312 ?>*</label</td>
        <td class="fieldGroup">
            <input id="titleNews" type="text" name="name" value="<?php echo $_name ?>">
        </td>
    </tr>
    <!--Код группы товара -->     
    <tr>
        <td class="titleGroup"><label for="titleNews"><?php echo $section->parametrs->param313 ?></label</td>
        <td class="fieldGroup">
            <input id="titleNews" type="text" name="code_gr" value="<?php echo $_code_gr ?>">
        </td>
    </tr>
    <!--Добавить рисунок -->    
<!--    <tr>
        <td class="titleGroup"><label for="userfile"><?php echo $section->parametrs->param286 ?></label></td>
        <td class="fieldFile"><input id="userfile" type="file" name="userfile"></td>
    </tr>  -->
    <!--Область кнопок -->
    <tr> 
         <td colspan="2" class="classBtnGr">
         <input class="buttonSend edSave"  type="submit" name="Save" value="<?php echo $section->parametrs->param283 ?>" >
         <input class="buttonSend edBack" type="button" value="<?php echo $section->parametrs->param284 ?>" onclick='javascript:history.back()'> 
         <?php if($delbtn): ?>
         <input class="buttonSend edDel" type="submit" name="Del" value="<?php echo $section->parametrs->param321 ?>"> 
         <?php endif; ?>         
         </td> 
    </tr> 
    </tbody>
    </table>
    </form>
</div>
