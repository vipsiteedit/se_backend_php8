<div class="<?php if(strval($section->parametrs->param36)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<div class="content shopgroups" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <?php if(strval($section->parametrs->param33)!='N'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_path.php")) include $__MDL_ROOT."/php/subpage_path.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_path.tpl")) include $__data->include_tpl($section, "subpage_path"); ?>
        <?php endif; ?>
        
        <?php if(empty($hidden)): ?>
            <div class="groupContent row">
                <?php if($shopcatgr==$basegroup): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_groups.php")) include $__MDL_ROOT."/php/subpage_groups.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_groups.tpl")) include $__data->include_tpl($section, "subpage_groups"); ?>
                <?php else: ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_general.php")) include $__MDL_ROOT."/php/subpage_general.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_general.tpl")) include $__data->include_tpl($section, "subpage_general"); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        
    </div>
</div>
</div>
