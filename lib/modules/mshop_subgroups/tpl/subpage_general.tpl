
<?php if(strval($section->parametrs->param37) == 'Y'): ?>
<div class="general-group-container">
<div>
<?php if(!empty($thisgroup_image)): ?>
    <div class="blockGroupImage">
        <img class="groupImage" src="<?php echo $thisgroup_image ?>" alt="<?php echo $thisgroup_image_alt ?>" >
    </div>
<?php endif; ?>
<?php if(empty($thisgroup_image)): ?>
    <div class="blockGroupImage">
        <img class="groupImage lol" src="[module_url]net_izobr.jpg" border="0" alt="<?php echo $sgroup->image_alt ?>">
    </div>
<?php endif; ?>
    <div class="general-group-text">
         <h1 class="groupTitle"><?php echo $thisgroup_name ?></h1>
         <div class="description"><?php echo $thisgroup_commentary ?></div>
    </div>
</div>
</div>
<?php endif; ?>
<?php if(!empty($thisgroup_commentary)): ?>
    <div class="groupcomment"><?php echo $thisgroup_commentary ?></div>
<?php endif; ?>
<?php if(!empty($sgrouplist)): ?>
    <div class="groupsublinkblock">
        <?php foreach($section->subgroups as $sgroup): ?>
        <div class="groupItem">
            <div class="mainGroup">
              <?php if(strval($section->parametrs->param35)=='Y'): ?>
              <div class="celltlbGroupImg">
                <?php if($sgroup->image != ''): ?>
                    <a class="lnkGroupImg" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>">
                        <img class="imgtlbGroupImg" src="<?php echo $sgroup->image ?>" border="0" alt="<?php echo $sgroup->image_alt ?>">
                    </a>
                    <?php else: ?>
                    <a class="lnkGroupImg" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>">
                        <img class="imgtlbGroupImg lol" src="[module_url]net_izobr.jpg" border="0" alt="<?php echo $sgroup->image_alt ?>">
                    </a>
                <?php endif; ?>
              </div>
              <?php endif; ?>
              <div class="celltlbGroupName">
                <a class="lnkGroupTitle" href="<?php echo $sgroup->link ?>" title="<?php echo $sgroup->title ?>"><?php echo $sgroup->name ?></a>
              </div>
            </div>
        </div>
        
<?php endforeach; ?>
    </div>
<?php endif; ?>

