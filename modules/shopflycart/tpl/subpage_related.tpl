<?php if(!empty($related)): ?>
<div class="cart-content-related">
    <h3><?php echo $section->language->lang035 ?></h3>
    <div class="related-list">
    <?php foreach($section->related as $related): ?>
        <div class="related-item productItem" style="display:inline-block;">
            <div class="related-item-name">
                <a class="" href="<?php echo $related->url ?>"><?php echo $related->name ?></a>
            </div>
            <div class="related-item-image">
                <a class="" href="<?php echo $related->url ?>">
                    <img class="" src="<?php echo $related->image ?>">
                </a>
            </div>
            <div class="related-item-price">
                <span class=""><?php echo $related->price ?></span>
            </div>
            <div class="buttonBox">
                <form class="form_addCart" method="post" action="">
                    <input type="hidden" name="addcart" value="11165">
                    <button class="buttonSend addcart btn btn-default"><?php echo $section->language->lang021 ?></button>
                    <a class="related-item-link" href="<?php echo $related->url ?>"><?php echo $section->language->lang036 ?></a>
                </form>
            </div>
        </div>
    
<?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
