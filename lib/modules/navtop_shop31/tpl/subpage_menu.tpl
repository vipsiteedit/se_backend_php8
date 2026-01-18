<div class='row'>
<?php foreach($item->items as $sitem): ?>
    <div class="headerCatalogCol" style="width:<?php echo $count_width ?>%">
        <a href="<?php echo $sitem->url ?>" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
          <?php if(strval($section->parametrs->param7)=='3'): ?>
            <?php if(!empty($sitem->image)): ?><img class="catalogPromoIcon img-responsive" style="max-width:<?php echo $section->parametrs->param20 ?>px; max-height:<?php echo $section->parametrs->param21 ?>px" src="<?php echo $sitem->image ?>" alt=""><?php endif; ?><?php endif; ?>
          <?php echo $sitem->title ?>             
        </a>
        <?php if($sitem->items): ?>
        <ul class="subsubitems">
        <?php foreach($sitem->items as $ssitem): ?>
            <li>
            <a href="<?php echo $ssitem->url ?>" class="headerCatalogSubItem headerCatalogSubNormal"><?php echo $ssitem->title ?></a>
            <ul class="subsubsubitems">
                <?php foreach($ssitem->items as $sssitem): ?>
                <li>
                    <a href="<?php echo $sssitem->url ?>" class="headerCatalogSubItem headerCatalogSubNormal"><?php echo $sssitem->title ?></a>
                </li>
                
<?php endforeach; ?>
            </ul>
            </li> 
        
<?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php if(fmod(intval($sitem->itemrow),intval(strval($section->parametrs->param15)))==0): ?> 
        </div><div class='row'>
    <?php endif; ?>

<?php endforeach; ?>
</div>
