<div class='row'>
<?php foreach($item->items as $sitem): ?>
    <ul class="headerCatalogCol" style="width:calc(<?php echo $count_width ?>% - 30px) !important; border-right: 1px solid #f0f0f0;">
    <li>
        <a href="<?php echo $sitem->url ?>" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
        <?php if(!empty($sitem->image)): ?><img class="catalogPromoIcon img-responsive" src="<?php echo $sitem->image ?>" alt=""><?php endif; ?> <?php echo $sitem->title ?></a>
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
        </li>
    </ul>
    <?php if(fmod(intval($sitem->itemrow),intval(strval($section->parametrs->param15)))==0): ?> 
        </div><div class='row'>
    <?php endif; ?>

<?php endforeach; ?>
</div>
