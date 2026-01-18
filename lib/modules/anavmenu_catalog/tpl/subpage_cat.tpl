<div class="row">
<?php foreach($section->catalog as $sitem): ?>
    <ul class="headerCatalogCol" style="width:calc(<?php echo $count_width ?>% - 30px) !important; border-right: 1px solid #f0f0f0;">
        <li>
            <a href="<?php echo $path ?><?php echo $sitem->link ?>" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
                <?php if(!empty($sitem->image)): ?><img class="catalogPromoIcon img-responsive" src="<?php echo $image_dir ?><?php echo $sitem->image ?>" alt=""><?php endif; ?> 
                <span class="headerCatalogName"><?php echo $sitem->name ?></span>
            </a>
            <ul class="subsubitems">
                <?php $__list = 'scatalog'.$sitem->id; foreach($section->$__list as $ssitem): ?>
                    <li>
                        <a href="<?php echo $path ?><?php echo $ssitem->link ?>" class="headerCatalogSubItem headerCatalogSubNormal"><?php echo $ssitem->name ?></a>
                        <ul class="subsubitems">
                            <?php $__list = 'sscatalog'.$ssitem->id; foreach($section->$__list as $sssitem): ?>
                                <li>               
                                    <a href="<?php echo $path ?><?php echo $sssitem->link ?>" class="headerCatalogSubItem headerCatalogSubNormal">
                                        <?php if(!empty($sssitem->image)): ?><img class="catalogPromoIcon img-responsive" src="<?php echo $image_dir ?><?php echo $sssitem->image ?>" alt=""><?php endif; ?> 
                                        <span class="headerCatalogName"><?php echo $sssitem->name ?></span>
                                    </a>
                                </li> 
                            
<?php endforeach; ?>
                        </ul>
                    </li> 
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    <?php if(fmod(intval($sitem->itemrow),intval($counts))==0): ?> 
        </div><div class='row'>
    <?php endif; ?>
<?php endforeach; ?>
</div>
             
                                               
