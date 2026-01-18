<?php if(!empty($related)): ?>
<div class="content-related">
    <h3><?php echo $section->language->lang092 ?></h3>
    <div class="related-list">
    <?php foreach($section->related as $related): ?>
        <div class="related-item">
            <span class="related-item-name"><?php echo $related->name ?></span>
            <img class="related-item-image" src="<?php echo $related->image ?>">
            <a class="related-item-link" href="<?php echo $__data->getPagePattern($section->parametrs->param1) ?><?php echo $related->url ?>"><?php echo $section->language->lang093 ?></a>
        </div>
    
<?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
