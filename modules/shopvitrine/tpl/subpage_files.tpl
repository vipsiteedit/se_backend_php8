<?php if(!empty($product_files)): ?>
<h3 class="titleHead">
    <span><?php echo $section->language->lang107 ?></span>
</h3>
<div class="content">                    
    <div class="product-files">
        <?php foreach($section->files as $file): ?>
            <div class="file-item">
                <a class="file-item-link" href="<?php echo $file->link ?>"><?php echo $file->name ?></a>
                <button class="btn btn-default btn-sm" onClick="window.location.href='<?php echo $file->link ?>'" title="<?php echo $section->language->lang108 ?>">
                    <i class="glyphicon glyphicon-download-alt"></i>
                    <?php echo $section->language->lang108 ?> (<?php echo $file->size ?>)
                </button>
            </div>
        
<?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
