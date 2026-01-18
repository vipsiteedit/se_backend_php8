<?php foreach($section->groups as $group): ?>
    <table class="tableTable itemgroup" border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody> 
            <tr> 
                <td class="celltlbGroupImg" width="100">
                    <?php if(!empty($group->image)): ?>
                        <a class="lnkGroupImg" href="<?php echo $group->link ?>">
                           <img class="imgtlbGroupImg" align="left" src="<?php echo $group->image ?>" border="0" alt="<?php echo $group->image_alt ?>" title="<?php echo $group->image_alt ?>">
                        </a>
                    <?php endif; ?>
                </td>
                <td class="celltlbGroupName" valign="top">
                    <dl> 
                        <dt>
                            <a class="lnkGroupTitle" href="<?php echo $group->link ?>"><?php echo $group->name ?></a>&nbsp;
                        </dt> 
                        <?php if(!empty($group->sub)): ?>
                        <dd>
                            <?php $__list = 'subgroups'.$group->id; foreach($section->$__list as $sgroup): ?>
                                <a class="lnkSubGrTitle" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><?php echo $sgroup->name ?></a><?php echo $sgroup->vline ?><?php echo $sgroup->end ?>
                            
<?php endforeach; ?>
                            &nbsp;
                        </dd> 
                        <?php endif; ?> 
                    </dl> 
                </td> 
            </tr> 
        </tbody> 
    </table> 
<?php endforeach; ?>
