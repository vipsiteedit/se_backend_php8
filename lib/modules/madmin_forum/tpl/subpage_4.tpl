<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <?php if($action==1): ?>
        <div id='message_warning'><?php echo $section->language->lang024 ?></div>
        
        <div id='butlayer'>
            <input class='buttonSend' id='mess_btnBack' type='button' value="<?php echo $section->language->lang014 ?>" onclick='javascript:history.back()'>
        </div>
    <?php endif; ?>
    <?php if($action==2): ?>
        <div id='message_warning'><?php echo $section->language->lang025 ?></div>
        <div id='butlayer'>
            <input class='buttonSend' id='mess_btnBack' type='button' value="<?php echo $section->language->lang014 ?>" onclick='javascript:history.back(-1)'>
        </div>
    <?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
