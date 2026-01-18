<?php if(strval($section->parametrs->param2)!='d'): ?>
<div class="<?php if(strval($section->parametrs->param2)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content adap_imggallery part<?php echo $section->id ?>" data-seimglist="<?php echo $section->id ?>" >
<?php if(!empty($section->title)): ?>
<<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span>
</<?php echo $section->title_tag ?>>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
  <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
  </div>
  <div class="contentBody">
    <div class="links">
      <div id="links-photo<?php echo $section->id ?>">
        <?php echo $__data->linkAddRecord($section->id) ?>
        <div class="classNavigator">
            <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
        </div>
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

            <a href="<?php echo $record->image ?>" class="object col-xs-6 col-sm-4 col-md-2 col-lg-2" title="<?php echo $record->title ?>" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                <?php if(!empty($record->image)): ?>
                    <img border="0" class="objectImage img-responsive" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                <?php endif; ?>
            </a>
        
<?php endforeach; ?>
        <div class="classNavigator">
            <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
        </div>
      </div>
       
    </div>
  </div>
</section>
<?php if(strval($section->parametrs->param2)!='d'): ?>
</div><?php endif; ?>
<footer:js>
[js:jquery/jquery.min.js]
[module_js:blueimp-gallery.js]
[include_js({p20:'<?php echo $section->parametrs->param20 ?>', p21: '<?php echo $section->parametrs->param21 ?>', p22: '<?php echo $section->parametrs->param22 ?>', id: '<?php echo $section->id ?>'})]
</footer:js>
<header:css>
<link rel="stylesheet" href="[this_url_modul]blueimp-gallery.min.css">
</header:css>
