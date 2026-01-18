 
<div class="titleGroup">
    <h1 class="grouptitle"><?php echo $thisgroup_name ?></h1>
    <span class="grouptitleg"><?php echo $section->parametrs->param10 ?></span>
</div>
<?php if($thisgroup_image!=''): ?>
    <img class="groupImage" title="<?php echo $thisgroup_image_alt ?>" alt="<?php echo $thisgroup_image_alt ?>" src="<?php echo $thisgroup_image ?>">
<?php endif; ?>
<div class="groupcomment"><?php echo $thisgroup_commentary ?></div>
    <div class="groupsublinkblock">
        <?php if(strval($section->parametrs->param21)=='N'): ?> 
            <?php foreach($section->subgroups as $group): ?>
                <div class="subgrouplink">
                    <a class="link" href="<?php echo $group->link ?>"><?php echo $group->name ?></a>
                    <?php if($group->scount!='0'): ?>
                        <span>(<?php echo $group->scount ?>)</span>
                    <?php endif; ?>
                </div>
            
<?php endforeach; ?>
        <?php else: ?>   
            <?php if(file_exists($__MDL_ROOT."/php/subpage_sgroups.php")) include $__MDL_ROOT."/php/subpage_sgroups.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_sgroups.tpl")) include $__data->include_tpl($section, "subpage_sgroups"); ?>
        <?php endif; ?>
    </div>

