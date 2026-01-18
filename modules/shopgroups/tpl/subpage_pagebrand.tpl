<div class="content-pagebrand">
    <?php if(!empty($brand)): ?>
        <h1 class="brandTitle"><?php echo $brand['name'] ?></h1>  
        <?php if(!empty($brand['image'])): ?>
            <div class="brandImage">
                <img class="brandImage" src="<?php echo $brand['image'] ?>">
            </div>
        <?php endif; ?> 
        <?php if(!empty($brand['text'])): ?>
            <div class="brandDescription">
                <?php echo $brand['text'] ?>
            </div>
        <?php endif; ?> 
    <?php endif; ?>
    <?php if(empty($brand)): ?>
        <?php if(!empty($brands)): ?>
            <h1 class="brandsTitle"><?php echo $section->language->lang004 ?></h1>
            <div class="brandsList">
                <?php foreach($section->brands as $brand): ?>
                    <div class="brandItem">
                        <?php if(!empty($brand->image)): ?>
                            <div class="blockImage">
                                <a href="<?php echo $brand->link ?>" title="<?php echo $brand->title ?>">
                                    <img class="brandImage" src="<?php echo $brand->image ?>">  
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="blockTitle">
                            <a class="brandTitle" href="<?php echo $brand->link ?>" title="<?php echo $brand->title ?>"><?php echo $brand->name ?></a>
                        </div> 
                    </div>
                
<?php endforeach; ?>
            </div>
        <?php endif; ?>    
    <?php endif; ?>
</div>
