<?php if($section->parametrs->param15=='Y'): ?>
<header:js>
    [js:jquery/jquery.min.js]
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
    var geo = {
        Address: '<?php echo $section->parametrs->param9 ?>, <?php echo $section->parametrs->param10 ?>, <?php echo $section->parametrs->param11 ?>'
    }  
    var param = '<?php echo $section->parametrs->param18 ?>';
    </script>
    <script type="text/javascript" src="[module_url]engine.js"></script>
    <style type="text/css">
        #YMapsID {
            width: <?php echo $section->parametrs->param16 ?>;
            height: <?php echo $section->parametrs->param17 ?>;
            margin: 0;
            padding: 0;
        }
    </style>
</header:js> 
<?php endif; ?>
<div class="content contacts" <?php echo $section->style ?>><div class="vcard">
<?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
<?php if($section->parametrs->param15=='Y'): ?>
  <div id="YMapsID"></div>
<?php endif; ?>
  <div class="name">
    <span class="orgtitle"><?php echo $section->language->lang007 ?></span><span class="fn org"><?php echo $section->parametrs->param6 ?></span>
  </div>
  <div class="adr">
    <span class="orgtitle"><?php echo $section->language->lang009 ?></span>
     <span class="postal-code"><?php echo $section->parametrs->param8 ?></span><?php if($section->parametrs->param8): ?><?php echo $section->parametrs->param13 ?><?php endif; ?>
     <span class="region"><?php echo $section->parametrs->param9 ?></span><?php if($section->parametrs->param9): ?><?php echo $section->parametrs->param13 ?><?php endif; ?> 
     <span class="locality"><?php echo $section->parametrs->param10 ?></span><?php if($section->parametrs->param10): ?><?php echo $section->parametrs->param13 ?><?php endif; ?>
     <span class="street-address"><?php echo $section->parametrs->param11 ?></span>
     <br>
     <span class="url"><?php echo $section->language->lang010 ?></span><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>">http://<?php echo $_SERVER['HTTP_HOST'] ?></a><span></span>
  </div>
  <div class="phone">
    <span class="orgtitle"><?php echo $section->language->lang008 ?></span><span class="tel"><?php echo $section->parametrs->param7 ?></span>
  </div>
  <?php if($section->parametrs->param20!=''): ?>
    <div class="fax">
       <span><?php echo $section->language->lang012 ?></span><span><?php echo $section->parametrs->param20 ?></span>
    </div>
  <?php endif; ?>
  <?php if($section->parametrs->param21!=''): ?>
    <div class="mail">
       <span><?php echo $section->language->lang011 ?></span><a href="mailto:<?php echo $section->parametrs->param21 ?>"><?php echo $section->parametrs->param21 ?></a>
    </div>
  <?php endif; ?>
</div></div>
