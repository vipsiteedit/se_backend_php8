
    <?php if($razdel==$_razdel): ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <?php if($record->id==$obj): ?>
         <?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_1.tpl")) include $__MDL_ROOT."/tpl/subpage_1.tpl"; ?>
        <?php endif; ?>
    
<?php endforeach; ?>
    <?php else: ?>
    
        <?php if($obj==0): ?>
        <?php foreach($section->objects as $record) { $__itemobj = -1; if ($record->id == $__itemobj || $__itemobj < 1) { include "show.tpl"; break; }} ?>
        <?php else: ?>
        <?php foreach($section->objects as $record) { $__itemobj = $obj; if ($record->id == $__itemobj || $__itemobj < 1) { include "show.tpl"; break; }} ?>
        <?php endif; ?>
    
    <?php endif; ?>
