<div class="content shopgroups" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>">
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0" <?php echo $section->style_image ?>>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <?php if(strval($section->parametrs->param33)!='N'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_path.php")) include $__MDL_ROOT."/php/subpage_path.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_path.tpl")) include $__data->include_tpl($section, "subpage_path"); ?>
        <?php endif; ?>
        <?php if(empty($hidden)): ?>
            <div class="groupContent">
                <?php if($shopcatgr==$basegroup): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_groups.php")) include $__MDL_ROOT."/php/subpage_groups.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_groups.tpl")) include $__data->include_tpl($section, "subpage_groups"); ?>
                <?php else: ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_general.php")) include $__MDL_ROOT."/php/subpage_general.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_general.tpl")) include $__data->include_tpl($section, "subpage_general"); ?>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param31)!='N'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_brands.php")) include $__MDL_ROOT."/php/subpage_brands.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_brands.tpl")) include $__data->include_tpl($section, "subpage_brands"); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
