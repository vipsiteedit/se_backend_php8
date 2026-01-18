<header:css>
    [include_css]
</header:css>
<div class="<?php if(strval($section->parametrs->param3)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<section class="content row b_banners_group clearfix" >
  <div class="b_about_site">
    <div class="b_about_site-content">
        <<?php echo $section->title_tag ?> class="b_about_site-content_text"><?php echo $section->title ?></<?php echo $section->title_tag ?>>
        <p><?php echo $section->text ?></p>
    </div>
    <div class="b_about_site-link_block">
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>" class="buttonSend"><?php echo $section->language->lang002 ?></a>
    </div>
  </div>
    <div class="b_banners_group-containers_block">
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <div class="b_banners_group-items">
    <div class="b_banners_group-container" <?php if(!empty($record->image)): ?>style="background-image: url('<?php echo $record->image ?>');"<?php endif; ?> <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
             <a class="b_banners_group-container_link" href="<?php echo $record->field ?>">
                <div class="b_banners_group-container_content">
                     <?php if(!empty($record->title)): ?><h4 class="b_banners_group-container_title"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></h4><?php endif; ?>
                     <?php if(!empty($record->note)): ?><div class="b_banners_group-container_text"><?php echo $record->note ?></div><?php endif; ?>
                </div>
             </a>
    </div></div>
    
<?php endforeach; ?>
    </div>
</section>
</div>
