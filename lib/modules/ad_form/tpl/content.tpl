 <header:css>
[include_css]
</header:css>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param20)=='Y'): ?>
[lnk:fancybox2/jquery.fancybox.css]
[js:fancybox2/jquery.fancybox.pack.js]
<script type="text/javascript">
$(document).ready(function() {
    $("#fancybox<?php echo $section->id ?>").fancybox({
        'openEffect'   : '<?php echo $section->parametrs->param18 ?>',
        'closeEffect'  : '<?php echo $section->parametrs->param18 ?>',
        'openSpeed'    : <?php echo $section->parametrs->param19 ?>, 
        'closeSpeed'   : <?php echo $section->parametrs->param19 ?>        
        });
 });
</script>
<?php endif; ?>
[js:jquery.maskedinput.min.js]
<script type="text/javascript">
$(function($){
    if (typeof $.fn.mask == 'function')
        $(".input_phone").mask("<?php echo $section->parametrs->param15 ?> (999) 999-99-99");
});
</script>
</footer:js>
<div class="content gs_form_mod" <?php echo $section->style ?>>
 <?php if(strval($section->parametrs->param20)=='Y'): ?><a id="fancybox<?php echo $section->id ?>" class="link_mod" href="#modal<?php echo $section->id ?>"><?php echo $section->parametrs->param17 ?></a>
 <div style="display:none"><?php endif; ?>
  <div id="modal<?php echo $section->id ?>" class="modal_block"> 
   <?php if(!empty($section->title)): ?><<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
     <span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
   <?php endif; ?>
   <?php if(!empty($section->image)): ?>
     <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
   <?php endif; ?>
   <?php if(!empty($section->text)): ?>
     <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
   <?php endif; ?>
   <div class="contentBody">  
    <form method="POST" class="fform" id="feedback-form<?php echo $section->id ?>">
    <div class="f_object" id="f_name">
    <?php if(strval($section->parametrs->param2)=='Y'): ?>
    <span class="inputTitle"><?php echo $section->language->lang005 ?></span>
    <?php endif; ?>
    <input class="inputTxt" <?php if(strval($section->parametrs->param2)=='N'): ?>style="display:none;"<?php endif; ?> type="text" name="nameFF" <?php if(strval($section->parametrs->param3)=='Y'): ?>required<?php endif; ?>  <?php if(strval($section->parametrs->param13)=='Y'): ?>placeholder="<?php echo $section->language->lang001 ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $section->language->lang001 ?>'"<?php endif; ?> x-autocompletetype="name">
    </div>
    <div class="f_object" id="f_phone">
    <?php if(strval($section->parametrs->param6)=='Y'): ?>
    <span class="inputTitle"><?php echo $section->language->lang006 ?></span>
    <?php endif; ?>
    <input class="inputTxt input_phone" <?php if(strval($section->parametrs->param6)=='N'): ?>style="display:none;"<?php endif; ?> type="text" name="phoneFF" <?php if(strval($section->parametrs->param7)=='Y'): ?>required<?php endif; ?> pattern="^((8|\+7|\+38)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$" <?php if(strval($section->parametrs->param13)=='Y'): ?>placeholder="<?php echo $section->language->lang002 ?>"<?php endif; ?> x-autocompletetype="name">
    </div>
    <div class="f_object" id="f_mail">
    <?php if(strval($section->parametrs->param4)=='Y'): ?>
    <span class="inputTitle"><?php echo $section->language->lang007 ?></span>
    <?php endif; ?>
    <input class="inputTxt" <?php if(strval($section->parametrs->param4)=='N'): ?>style="display:none;"<?php endif; ?> type="email" name="contactFF" <?php if(strval($section->parametrs->param5)=='Y'): ?>required<?php endif; ?> <?php if(strval($section->parametrs->param13)=='Y'): ?>placeholder="<?php echo $section->language->lang003 ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $section->language->lang003 ?>'"<?php endif; ?> x-autocompletetype="email">
    </div>
    <div class="f_object" id="f_mass">
    <?php if(strval($section->parametrs->param8)=='Y'): ?>
    <span class="inputTitle"><?php echo $section->language->lang008 ?></span>
    <?php endif; ?> 
    <textarea class="inputAr" <?php if(strval($section->parametrs->param8)=='N'): ?>style="display:none;"<?php endif; ?> name="messageFF" <?php if(strval($section->parametrs->param9)=='Y'): ?>required<?php endif; ?> rows="7" <?php if(strval($section->parametrs->param13)=='Y'): ?>placeholder="<?php echo $section->language->lang011 ?>" onfocus="placeholder='';" onblur="placeholder='<?php echo $section->language->lang011 ?>';"<?php endif; ?>></textarea>
    </div>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
    <div class="blockBtn"><input class="buttonForm buttonSend" type="submit" <?php if(strval($section->parametrs->param12)=='Y'): ?>onclick="yaCounter<?php echo $section->parametrs->param10 ?>.reachGoal('<?php echo $section->parametrs->param11 ?>');"<?php endif; ?> value="<?php echo $section->language->lang009 ?>"></div>
    </form>
    <script>
    document.getElementById('feedback-form<?php echo $section->id ?>').addEventListener('submit', function(evt){
      var http = new XMLHttpRequest(), f = this;
      evt.preventDefault();
      http.open("POST", "<?php echo $__data->getLinkPageName() ?>", true);
      http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      http.send("part=<?php echo $section->id ?>"+"&nameFF=" + this.nameFF.value + "&contactFF=" + this.contactFF.value + "&phoneFF=" + this.phoneFF.value + "&messageFF=" + this.messageFF.value);
      http.onreadystatechange = function() {
     if (http.readyState == 4 && http.status == 200) {
       <?php if(strval($section->parametrs->param20)=='Y'): ?>
       $("#modal<?php echo $section->id ?>").html('<div class="alert_mes">' + http.responseText +', <?php echo $section->language->lang010 ?>' + '</div>');
       <?php else: ?>
       alert(http.responseText +', <?php echo $section->language->lang010 ?>');
       <?php endif; ?>    
       f.messageFF.removeAttribute('value'); // очистить поле сообщения (две строки)
       f.messageFF.value='';
     }
      }
      http.onerror = function() {
     alert('Извините, данные не были переданы');
      }
    this.nameFF.value = "";
    this.phoneFF.value = "";
    this.contactFF.value = "";
    this.messageFF.value = "";
    }, false);
    </script>
   </div>
    
   <?php echo $__data->linkAddRecord($section->id) ?>
   <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
     <?php if(!empty($record->title)): ?>
      <div class="objectTitle">
       <span class="objectTitleTxt record-title"><?php echo $record->title ?></span> 
      </div> 
     <?php endif; ?>
     <?php if(!empty($record->note)): ?>
      <div class="objectNote record-note"><?php echo $record->note ?></div> 
     <?php endif; ?>
    </div> 
   
<?php endforeach; ?>
  </div>
 <?php if(strval($section->parametrs->param20)=='Y'): ?></div><?php endif; ?>
</div>
