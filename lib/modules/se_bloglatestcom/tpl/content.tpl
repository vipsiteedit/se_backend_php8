<div class="content posledcomm" <?php echo $section->style ?>>
    
        <?php if($section->title!=''): ?>
            <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
        <?php endif; ?>
        <?php if($section->image!=''): ?>
            <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
        <?php endif; ?>
        <?php if($section->text!=''): ?>
            <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
        <?php endif; ?>
        <div class="poslcomm">
         <?php foreach($section->blogsposlcomm as $record): ?>
                <div  class="kazdcomm">   
                    <div class="author">
                        <?php echo $record->author ?>
                    </div>
                    <div class="razdelitel">
                     <?php echo $section->parametrs->param6 ?>
                    </div>
                    <div class="link">
                        <a class="smlink" href="<?php echo seMultiDir()."/".$section->parametrs->param5."/" ?>post/<?php echo $record->url ?>/"><?php echo $record->title ?></a>
                    
                    </div>
                </div>
         
<?php endforeach; ?>
        </div>
    
</div> 
