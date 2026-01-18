<?php if(strval($section->parametrs->param15)==Y): ?>
<footer:js>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    [include_js({'id':'<?php echo $section->id ?>','city':'<?php echo $section->parametrs->param10 ?>','addr':'<?php echo $section->parametrs->param11 ?>', 'company':'<?php echo $section->parametrs->param6 ?>'})]
</footer:js>
<?php endif; ?>
<div class="content contacts" <?php echo $section->style ?>><div class="vcard">
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
<?php if(strval($section->parametrs->param15)=="Y"): ?>
  <div id="YMapsID<?php echo $section->id ?>" style="width:100%; height:400px" class="maps"></div>
<?php endif; ?>
<div class="addr-block">
  <div class="name">
    <span class="orgtitle"><?php echo $section->language->lang007 ?></span><span class="fn org"><?php echo $section->parametrs->param6 ?></span>
  </div>
  <div class="phone">
    <span class="orgtitle"><?php echo $section->language->lang008 ?></span><span class="tel"><?php echo $section->parametrs->param7 ?></span>
  </div>
  <div class="adr">
    <span class="orgtitle"><?php echo $section->language->lang009 ?></span>
     <span class="postal-code"><?php echo $section->parametrs->param8 ?></span><?php echo $section->parametrs->param13 ?><span class="region"><?php echo $section->parametrs->param9 ?></span><?php echo $section->parametrs->param13 ?> 
     <span class="locality"><?php echo $section->parametrs->param10 ?></span><?php echo $section->parametrs->param13 ?><span class="street-address"><?php echo $section->parametrs->param11 ?></span>
     <br>
     <span class="url"><?php echo $section->language->lang010 ?> http://<?php echo $host ?></span>
  </div>
  </div>
</div></div>
