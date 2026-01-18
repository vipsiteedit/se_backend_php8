<?php foreach($section->regions as $region): ?>
    <div class="regionItem" data-id=<?php echo $region->id ?>>
        <div class="city"><?php echo $region->city ?></div>
        <div class="detail">
            <?php if(!empty($region->code)): ?><i class="flag flag-<?php echo $region->code ?>"></i><?php endif; ?>
            <?php echo $region->country ?>,
            <?php if(!empty($region->region)): ?><?php echo $region->region ?>,<?php endif; ?>
            <?php echo $region->city ?>
        </div>
    </div>    

<?php endforeach; ?>
