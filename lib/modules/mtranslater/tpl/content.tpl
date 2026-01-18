<div class="translite" id="translite"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
<div class="translobj">
    <div id="google_translate_element">
        
    </div>
    
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
     
</div>
        
</div> 
      
