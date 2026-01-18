<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <?php if($yes!=0): ?>
        
        
            <form method='POST'>
                <div id='adm_Del'>
                    <div id='adm_DelMess'><?php echo $section->language->lang031 ?>&nbsp;<?php echo $res ?></div>
                    <input type='hidden' name="area" value="<?php echo $area_id ?>">
                    <input class='buttonSend' id='adm_bYes' type='submit' name="yes" value="<?php echo $section->language->lang029 ?>">
                    <input class='buttonSend' id='adm_bNo' type='button' value="<?php echo $section->language->lang030 ?>" onclick="javascript:history.back(-1)">
                </div>
            </form>
        
    <?php else: ?>
        <div id='message_warning'><?php echo $section->language->lang032 ?></div>
        <div id='butlayer'>
            <input class='buttonSend' id='mess_btnBack' type='button' value='<?php echo $section->language->lang014 ?>' onclick='javascript:history.go(-2)'>
        </div>
    <?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
