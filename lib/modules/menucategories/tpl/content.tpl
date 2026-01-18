
<div class="content category" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>  
    <div class="contentBody">   

    <?php if($flag): ?>      
        <form name="OpenForm" method="post" action="" class="BtnClassFrm">
            <input type='hidden' name='part_id' value='<?php echo $section->id ?>'>
        <?php if($editor==false): ?>
            <input class="buttonSend edEdit" type='submit' name='EditCtg' id="editctg" value='<?php echo $section->language->lang017 ?>'>
        <?php else: ?> 
            <input class="buttonSend edAdd" name="AddTo" value="<?php echo $section->language->lang008 ?>" type="submit">
            <input class="buttonSend edEnd" name="End" type="submit" value="<?php echo $section->language->lang018 ?>" type="submit">
        <?php endif; ?> 
        </form>     
    <?php endif; ?> 

    
    </div>
        <div class="List">

            <?php echo $PRICEMENU ?>
        </div> 
</div>
