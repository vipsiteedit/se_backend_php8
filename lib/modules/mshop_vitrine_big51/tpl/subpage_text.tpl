<?php if(!empty($product_text)): ?>
<?php if($use_tabs): ?>
    <?php foreach($section->tabs as $tab): ?>
        <h3 class="titleHead"><span><?php echo $tab->title ?></span></h3>
        <div class="content"><?php echo $tab->content ?></div>
    
<?php endforeach; ?>
<?php else: ?>
    <h3 class="titleHead"><span><?php echo $section->language->lang017 ?></span></h3>
    <div class="content"><?php echo $product_text ?></div>
<?php endif; ?>
<?php endif; ?>
