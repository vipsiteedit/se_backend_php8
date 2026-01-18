<?php foreach($section->groups as $group): ?>
    <div class="groupItem">
        <div class="mainGroup">
            <div class="celltlbGroupImg">
                <?php if(!empty($group->image)): ?>
                    <a class="lnkGroupImg" href="<?php echo $group->link ?>" title="<?php echo $group->title ?>">
                        <img class="imgtlbGroupImg" src="<?php echo $group->image ?>" border="0" alt="<?php echo $group->image_alt ?>">
                    </a>
                <?php endif; ?>
            </div>   
            <div class="celltlbGroupName">
                <a class="lnkGroupTitle" href="<?php echo $group->link ?>" title="<?php echo $group->title ?>"><?php echo $group->name ?></a>
            </div>
        </div>
        <?php if(!empty($group->sub)): ?>
            <div class="subgroupsList">
                <?php $__list = 'subgroups'.$group->id; foreach($section->$__list as $sgroup): ?>
                    <div class="subItem">
                        <?php if(strval($section->parametrs->param34)=='Y'): ?>
                            <?php if(!empty($sgroup->image)): ?>
                                <a class="lnkSubGrImage" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><img src="<?php echo $sgroup->image ?>" border="0" alt="<?php echo $sgroup->image_alt ?>"></a> 
                            <?php endif; ?>
                        <?php endif; ?>
                        <a class="lnkSubGrTitle" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><?php echo $sgroup->name ?></a><?php if(!empty($sgroup->vline)): ?><span class="vline"><?php echo $section->parametrs->param7 ?></span><?php endif; ?>
                    </div> 
                
<?php endforeach; ?>
                <?php if(!empty($group->end)): ?>
                    <a class="moreGroups" href="<?php echo $group->link ?>" title="<?php echo $group->title ?>"><?php echo $group->end ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?> 
    </div> 
<?php endforeach; ?>
