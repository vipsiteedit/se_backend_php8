<?php if($features_exist): ?>
<h3 class="titleHead" id="features">
    <span><?php echo $section->language->lang016 ?></span>
</h3>
<div class="content">
<div class="goodsFeatures">
    <?php foreach($section->feature_groups as $fgroup): ?>
        <div class="featureGroup" data-id="<?php echo $fgroup->id ?>">
            <?php if(!empty($fgroup->id)): ?>
            <div class="blockGroupName">
                <?php if(!empty($fgroup->image)): ?>
                    <img class="groupImage" src="<?php echo $fgroup->image ?>">
                <?php endif; ?>
                <span class="groupName"><?php echo $fgroup->name ?></span>
                <?php if(!empty($fgroup->description)): ?>
                    <span class="markDescription">?</span>
                    <div class="contentDescription groupDescription" style="display:none;">
                        <?php echo $fgroup->description ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <ul class="featureList">        
                <?php $__list = 'features'.$fgroup->id; foreach($section->$__list as $feature): ?>
                    <li class="featureItem" data-id="<?php echo $feature->id ?>">
                        <?php if(!empty($feature->image)): ?>
                            <img class="featureImage" src="<?php echo $feature->image ?>">
                        <?php endif; ?>
                        <label class="blockFeatureName">
                            <span class="featureName"><?php echo $feature->name ?><?php if(!empty($feature->measure)): ?>, <span class="measure"><?php echo $feature->measure ?></span><?php endif; ?></span>
                            <?php if(!empty($feature->description)): ?>
                                <span class="markDescription">?</span>
                                <div class="contentDescription featureDescription" style="display:none;">
                                    <?php echo $feature->description ?>
                                </div>
                            <?php endif; ?>
                        </label>
                        <span class="blockFeatureValue">
                            <?php if(!empty($feature->icon_image)): ?>
                                <img class="valueIcon iconImage" src="<?php echo $feature->icon_image ?>""></img>
                            <?php endif; ?>
                            <?php if(!empty($feature->icon_color)): ?>
                                <span class="valueIcon iconColor" style="background:<?php echo $feature->icon_color ?>;"></span>
                            <?php endif; ?>
                            <span class="featureValue"><?php echo $feature->value ?></span>
                        </span>
                    </li>
                
<?php endforeach; ?>
            </ul> 
        </div>              
    <?php endforeach; ?>
</div>
</div>
<?php endif; ?>
