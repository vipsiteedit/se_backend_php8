<?php if($showpages!=0): ?>
    <?php if($startshow!=0): ?>
        <a class="pagen" id="Beg" href="?<?php echo $pgInfo['NAME'] ?>=1">1</a>
        <?php if($startpg!=0): ?>
            <a class="pagen lpoints points" id="Back" href="?<?php echo $pgInfo['NAME'] ?>=<?php echo $startpg ?>">...</a>
        <?php endif; ?>
    <?php endif; ?>
    <?php foreach($section->mypages as $pg): ?>
        <?php if($pg->cur!=0): ?>
            <input class="pagenactive Active" type='text' name="<?php echo $pgInfo['NAME'] ?>" value='<?php echo $pg->pg ?>'>
        <?php else: ?>
            <a class="pagen links" href="?<?php echo $pgInfo['NAME'] ?>=<?php echo $pg->pg ?>"><?php echo $pg->pg ?></a>
        <?php endif; ?>
    
<?php endforeach; ?>
    <?php if($stopshow!=0): ?>
        <?php if($stoppg!=0): ?>
            <a class="pagen rpoints points" id="Next" href="?<?php echo $pgInfo['NAME'] ?>=<?php echo $stoppg ?>">...</a>
        <?php endif; ?>
        <a class="pagen" id="End" href="?<?php echo $pgInfo['NAME'] ?>=<?php echo $allpages ?>"><?php echo $allpages ?></a>
    <?php endif; ?>
<?php endif; ?>
