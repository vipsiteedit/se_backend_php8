<div class="groups-related">
    <h3>Возможно, вас также заинтересует:</h3>
    <?php foreach($section->realted_groups as $related): ?>
        <div class="cellGroup related-item">
            <?php if(strval($section->parametrs->param35)=='Y'): ?>
                <div class="blockImage">
                    <?php if(!empty($related->image)): ?>
                        <a class="lnkGroupImage" href="<?php echo $related->link ?>" title="<?php echo $related->title ?>"><img class="subgroupImage" src="<?php echo $related->image ?>" alt="<?php echo $related->image_alt ?>"></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="blockTitle">
                <a class="lnkGroupName" href="<?php echo $related->link ?>" title="<?php echo $related->title ?>"><?php echo $related->name ?></a>
                <?php if(!empty($related->count)): ?>
                    <span class="count">(<?php echo $related->count ?>)</span>
                <?php endif; ?>
            </div>
        </div>
    
<?php endforeach; ?>
</div>
