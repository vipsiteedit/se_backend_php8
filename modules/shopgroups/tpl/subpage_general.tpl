
<h1 class="groupTitle"><?php echo $thisgroup_name ?></h1>
<?php if(!empty($thisgroup_image)): ?>
    <div class="blockGroupImage">
        <img class="groupImage" src="<?php echo $thisgroup_image ?>" alt="<?php echo $thisgroup_image_alt ?>" >
    </div>
<?php endif; ?>
<?php if(!empty($thisgroup_commentary)): ?>
    <div class="groupcomment"><?php echo $thisgroup_commentary ?></div>
<?php endif; ?>
<?php if(!empty($sgrouplist)): ?>
    <h3 class="subgroupsTitle"><?php echo $section->language->lang003 ?></h3>
    <div class="groupsublinkblock">
        <?php foreach($section->subgroups as $sgroup): ?> 
            <div class="cellGroup">
                <?php if(strval($section->parametrs->param35)=='Y'): ?>
                    <div class="blockImage">
                        <?php if(!empty($sgroup->image)): ?>
                            <a class="lnkGroupImage" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><img class="subgroupImage" src="<?php echo $sgroup->image ?>" alt="<?php echo $sgroup->image_alt ?>"></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="blockTitle">
                    <a class="lnkGroupName" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><?php echo $sgroup->name ?></a>
                    <?php if(!empty($sgroup->scount)): ?>
                        <span class="count">(<?php echo $sgroup->scount ?>)</span>
                    <?php endif; ?>
                </div>
            </div>
        
<?php endforeach; ?> 
    </div>
<?php endif; ?>
<?php if(!empty($related)): ?>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_related.php")) include $__MDL_ROOT."/php/subpage_related.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_related.tpl")) include $__data->include_tpl($section, "subpage_related"); ?>
<?php endif; ?>

