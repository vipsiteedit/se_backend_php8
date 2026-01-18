<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    
        <?php if($add!=0): ?>
            <div id='message_warning'><?php echo $section->language->lang044 ?></div>
        <?php endif; ?>
        <?php if($del!=0): ?>
            <div id='message_warning'><?php echo $section->language->lang045 ?></div>
        <?php endif; ?>
        <?php if($edit!=0): ?>
            <div id='message_warning'><?php echo $section->language->lang046 ?></div>
        <?php endif; ?>     
        <?php if($save_status!=0): ?>
            <?php if($suser_name==''): ?>
                <div id='message_warning'><?php echo $section->language->lang047 ?></div>
            <?php else: ?>
                    <div id='message_warning'><?php echo $section->language->lang048 ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($del_status!=0): ?>
            <?php if($duser_name==''): ?>
                <div id='message_warning'><?php echo $section->language->lang047 ?></div>
            <?php else: ?>
                    <div id='message_warning'><?php echo $section->language->lang048 ?></div>
            <?php endif; ?>
        <?php endif; ?>   
                    
    <div id='butlayer'>
        <input class='buttonSend' id='mess_btnBack' type='button' value="<?php echo $section->language->lang014 ?>" onclick='javascript:history.back(-1)'>
    </div>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
