<header:css>
[include_css]
</header:css>
<div class="content slider-bigsh part<?php echo $section->id ?>" >
<?php if(!empty($section->title)): ?>
<<?php echo $section->title_tag ?> class="contentTitle">
  <span class="contentTitleTxt"><?php echo $section->title ?></span>
</<?php echo $section->title_tag ?>>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText"><?php echo $section->text ?></div>
<?php endif; ?>
  <div class="flicker contentBody" data-block-text="false">
<?php echo $__data->linkAddRecord($section->id) ?>
<ul>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <li class="object" data-background="<?php echo $record->image ?>">
    <a href="<?php echo $record->field ?>"><div class="flick-inner">
      <div class="flick-content">
      <?php if(!empty($record->title)): ?><<?php echo $record->title_tag ?> class="objectTitle"><?php echo $record->title ?></<?php echo $record->title_tag ?>><?php endif; ?>
      <?php if(!empty($record->note)): ?><div class="objectNote"><?php echo $record->note ?></div><?php endif; ?>
      <?php if(!empty($record->objecttext1)): ?><div class="objectPrice"><?php echo $record->objecttext1 ?></div><?php endif; ?>
      </div>
    </div></a>  
    </li>
    
<?php endforeach; ?>
</ul> 
  </div>
</div>
<footer:js>
    [js:jquery/jquery.min.js]
    [module_js:modernizr-custom-v2.7.1.min.js]
    [module_js:hammer-v2.0.3.min.js]
    [module_js:flickerplate.min.js]
    <link href="[this_url_modul]flickerplate.css" rel="stylesheet" type="text/css">
<script>
$(document).ready(function()
{
    $('.flicker').flickerplate(
    {
        arrows: <?php echo $section->parametrs->param1 ?>,
        arrows_constraint: <?php echo $section->parametrs->param2 ?>,
        auto_flick: <?php echo $section->parametrs->param3 ?>,
        auto_flick_delay: <?php echo $section->parametrs->param4 ?>,
        dot_alignment: '<?php echo $section->parametrs->param6 ?>',
        dot_navigation: <?php echo $section->parametrs->param5 ?>,
        flick_animation: '<?php echo $section->parametrs->param7 ?>',
        theme: '<?php echo $section->parametrs->param8 ?>'
    });
});
</script>
</footer:js>
