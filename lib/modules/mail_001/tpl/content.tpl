<div class="content" id="mail001" <?php echo $section->style ?> >
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0px" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <form id="Go" action="" method="post">
        <div class="ml001_err">
            <font class="m001_ertxt">
                <?php echo $ml001_errtxt ?>
                
            </font>             
        </div>
        <div class="mailform">
            <div class="ml001_name">
                <b class="ml001_title"><?php echo $section->parametrs->param4 ?></b> 
                <input class="ml001_inp" name="name" title="&quot;<?php echo $section->parametrs->param4 ?>&quot;" value="<?php echo $ml001_name ?>">
            </div> 
            <div class="ml001_email">
                <b class="ml001_title"><?php echo $section->parametrs->param5 ?></b> 
                <input class="ml001_inp" name="email" title="&quot;<?php echo $section->parametrs->param5 ?>&quot;" value="<?php echo $ml001_email ?>">
            </div> 
            <div class="ml001_note">
                <b class="ml001_title"><?php echo $section->parametrs->param6 ?></b> 
                <textarea class="ml001_area" cols="38" rows="6" name="note" wrap="virtual"><?php echo $ml001_note ?></textarea> 
            </div> 
            <?php if($section->parametrs->param11!='No'): ?>
                <div class="ml001_pin">
                    <b class="ml001_title"><?php echo $section->parametrs->param12 ?></b>
                    <div class="ml001_imgtxt">
                        
                        <div class="captcha_layer">
                            <?php echo $captcha ?>
                        </div>
                        <input maxlength="5" size="5" name="pin" type="text" class="ml001_txtpin" title="<?php echo $section->parametrs->param12 ?>" autocomplete="off">
                    </div>
                </div>
            <?php endif; ?>
            <input name="GoTo" type="submit" value="<?php echo $section->parametrs->param9 ?>" class="buttonSend">
        </div>
    </form> 
    
</div>
