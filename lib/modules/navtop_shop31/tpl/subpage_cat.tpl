<div class="row">
<?php foreach($section->catalog as $sitem): ?>
    <div class="headerCatalogCol" style="width:<?php echo $count_width ?>%">
            <a href="<?php echo $path ?><?php echo $sitem->link ?>" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
                <?php if(strval($section->parametrs->param7)=='3'): ?>
                    <?php if(!empty($sitem->image)): ?><img class="catalogPromoIcon img-responsive" style="max-width:<?php echo $section->parametrs->param20 ?>px; max-height:<?php echo $section->parametrs->param21 ?>px" src="<?php echo $image_dir ?><?php echo $sitem->image ?>" alt=""><?php endif; ?> 
                <?php endif; ?>
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
                                        <?php if(strval($section->parametrs->param7)=='3'): ?>
                                            <?php if(!empty($sssitem->image)): ?><img class="catalogPromoIcon img-responsive"  style="max-width:<?php echo $section->parametrs->param20 ?>px; max-height:<?php echo $section->parametrs->param21 ?>px" src="<?php echo $image_dir ?><?php echo $sitem->image ?>" src="<?php echo $image_dir ?><?php echo $sssitem->image ?>" alt=""><?php endif; ?>
                                        <?php endif; ?> 
                                        <span class="headerCatalogName"><?php echo $sssitem->name ?></span>
                                    </a>
                                </li> 
                            
<?php endforeach; ?>
                        </ul>
                    </li> 
                <?php endforeach; ?>
            </ul>
    </div>
    <?php if(fmod(intval($sitem->itemrow),intval($counts))==0): ?> 
        </div><div class='row'>
    <?php endif; ?>
<?php endforeach; ?>
</div>
             
                                               
