<div class="content shopsection" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
<?php if(!empty($section->title)): ?>
<<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span>
</<?php echo $section->title_tag ?>>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>  
  <div class="contentBody">
    <?php foreach($section->items as $item): ?>
        <div class="section-item">
            <?php if(!empty($item->title)): ?>
                <div class="item-name"><h4><?php echo $item->title ?></h4></div>
            <?php endif; ?>
            <?php if(!empty($item->text)): ?>
                <div class="item-note"><?php echo $item->text ?></div>
            <?php endif; ?>
            <?php if(!empty($item->picture)): ?>
                <div class="item-image"><img src="<?php echo $item->picture ?>" alt="<?php echo $item->picture_alt ?>" style=""></div>
            <?php endif; ?>
            <?php if(!empty($item->url)): ?>
                <div class="item-link"><a href="<?php echo $item->url ?>"><?php echo $section->language->lang001 ?></a></div>
            <?php endif; ?>
        </div>    
    
<?php endforeach; ?>
  </div>
</div>
