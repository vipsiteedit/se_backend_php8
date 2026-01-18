<?php foreach($section->subgroups as $group): ?> 
    <div class="cellGroup">
        <?php if(!empty($group->image)): ?>
            <a class="lnkGroupImg" href="<?php echo $group->link ?>">
                <img src="<?php echo $group->image ?>" border="0" alt="<?php echo $group->image_alt ?>" title="<?php echo $group->image_alt ?>">
            </a>
         <?php endif; ?>
         <h5>
            <a href="<?php echo $group->link ?>"><?php echo $group->name ?></a>
            <?php if($group->scount!='0'): ?>
                <span>(<?php echo $group->scount ?>)</span>
            <?php endif; ?>
         </h5>
    </div>

<?php endforeach; ?>
