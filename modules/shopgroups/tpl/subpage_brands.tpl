<?php if(!empty($brands)): ?>
    <h3 class="brandsTitle"><?php echo $section->language->lang004 ?></h3>
    <div class="brandsList">
        <?php foreach($section->brands as $brand): ?>
            <div class="brandItem<?php if($brand_selected==$brand->code): ?> selected<?php endif; ?>">
                <?php if(!empty($brand->image)): ?>
                    <div class="blockImage">
                        <a href="<?php echo $brand->link ?>" title="<?php echo $brand->title ?>">
                            <img class="brandImage" src="<?php echo $brand->image ?>">  
                        </a>
                    </div>
                <?php endif; ?>
                <div class="blockTitle">
                    <a class="brandTitle" href="<?php echo $brand->link ?>" title="<?php echo $brand->title ?>"><?php echo $brand->name ?></a>
                    <span class="count">(<?php echo $brand->cnt ?>)</span> 
                </div> 
            </div>
        
<?php endforeach; ?>
    </div>
    <?php if(!empty($brand_text)): ?>
        <div class="brandText">
            <?php echo $brand_text ?>        
        </div>
    <?php endif; ?>
<?php endif; ?>
