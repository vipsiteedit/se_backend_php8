<?php if(file_exists($__MDL_ROOT."/php/subpage_head.php")) include $__MDL_ROOT."/php/subpage_head.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_head.tpl")) include $__data->include_tpl($section, "subpage_head"); ?>

<div class="content vkladki" <?php echo $section->style ?>>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_vkladki.php")) include $__MDL_ROOT."/php/subpage_vkladki.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_vkladki.tpl")) include $__data->include_tpl($section, "subpage_vkladki"); ?>
    <div class="linkBox">
    <?php if(file_exists($__MDL_ROOT."/php/subpage_linkbox.php")) include $__MDL_ROOT."/php/subpage_linkbox.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_linkbox.tpl")) include $__data->include_tpl($section, "subpage_linkbox"); ?>
    </div>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <div class="textBox">
    <?php if(file_exists($__MDL_ROOT."/php/subpage_textbox.php")) include $__MDL_ROOT."/php/subpage_textbox.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_textbox.tpl")) include $__data->include_tpl($section, "subpage_textbox"); ?>
    </div>
</div>

