<div class="content populartopik" <?php echo $section->style ?>>
    
        <?php if(!empty($section->title)): ?>
            <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
        <?php endif; ?>
        <?php if(!empty($section->image)): ?>
            <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
        <?php endif; ?>
        <?php if(!empty($section->text)): ?>
            <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
        <?php endif; ?>
        
<header:js>[js:jquery/jquery.min.js]</header:js>
<header:js>[js:ui/jquery.ui.min.js]</header:js>
<header:js>
    <script type="text/javascript">
        $(function(){
            $( "#tabs" ).tabs();
        });
    </script>
</header:js>    
   
  <div id="tabs">
    <ul>
      <li><a href="#tabs-1"><?php echo $section->language->lang021 ?></a></li>
      <li><a href="#tabs-2"><?php echo $section->language->lang022 ?></a></li>
      
    </ul>
    <div id="tabs-1">
      <div class="poslcomm">
         <?php foreach($section->blogsposlcomm as $record): ?>
                <div  class="kazdcomm">   
                    <div class="link">
                        <a class="smlink" href="<?php echo seMultiDir()."/".$section->parametrs->param5."/" ?>post/<?php echo $record->url ?>/"><?php echo $record->title ?></a>
                    </div>
                    <div class="razdelitel">
                     <?php echo $section->parametrs->param6 ?>
                    </div>
                    <div class="kolvomsg">
                     <a class="msglink" href="<?php echo seMultiDir()."/".$section->parametrs->param5."/" ?>post/<?php echo $record->url ?>/#comments"><?php echo $record->kolvomsg ?></a>
                    </div>
                    <div class="razdelitel2">
                     <?php echo $section->language->lang024 ?>
                    </div>
                    <div class="data">
                     <?php echo $record->date_add_rus ?>
                    </div>
                </div>
         
<?php endforeach; ?>
       </div>
    </div>
    <div id="tabs-2">
      <div class="blogspopularposts">
         <?php foreach($section->blogspopularposts as $poslcom): ?>
                <div  class="kazdcomm">   
                    <div class="link">
                        <a class="smlink" href="<?php echo seMultiDir()."/".$section->parametrs->param5."/" ?>post/<?php echo $poslcom->url ?>/"><?php echo $poslcom->title ?></a>
                    </div>
                   <div class="razdelitel">
                     <?php echo $section->parametrs->param6 ?>
                    </div>
                    <div class="kolvomsg">
                     <a class="msglink" href="<?php echo seMultiDir()."/".$section->parametrs->param5."/" ?>post/<?php echo $poslcom->url ?>/#comments"><?php echo $poslcom->kolvomsg ?></a>
                    </div>
                   <div class="razdelitel3">
                     <?php echo $section->language->lang023 ?>
                    </div>
                    <div class="prosm">
                     <?php echo $poslcom->hits ?>
                    </div>
                </div>
         
<?php endforeach; ?>
       </div>
    </div>
    </div>
  </div>
