<!-- Subpage 15. Добавление/Редактирование группы товара -->
<div class="content e_shopvit_contEditorGroup">
    <div class="errorText"><?php echo $errortext ?></div> 
    <form method="post" name="frm" action="" enctype="multipart/form-data" style="margin:0px;">
    <div class="classBtnEdit">
         <input class="buttonSend edSaveEdGr"  type="submit" name="AddGroup" value="<?php echo $section->parametrs->param318 ?>">
         <input class="buttonSend edBackEdGr" type="submit" name="Back" value="<?php echo $section->parametrs->param284 ?>">          
    </div> 
    <?php if($section->parametrs->param320=="Y"): ?>
        <div class="MessEdit"><?php echo $section->parametrs->param319 ?></div>
    <?php endif; ?>
    <div class="linkGroup">
    <?php foreach($section->shop_group as $record): ?>
        <a class="linkGroups" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub14/" ?>id/<?php echo $record->id ?>/"><?php echo $record->name ?></a>
    
<?php endforeach; ?>
    
    </div>
    </form>
</div>
