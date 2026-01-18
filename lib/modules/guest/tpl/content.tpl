<div class="content" id="cont_guest" <?php echo $section->style ?>>
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
    <?php echo $blocked ?> 
    <form method="post" action="" enctype="multipart/form-data">
        <div id="guest">
            <div id="comments">
                <?php echo $cont_comm ?>
                
                <?php echo $pageselector ?>      
            </div>
            <table class="tableTable" id="guesttab" width="400">
                <div class="error">
                    <?php echo $usrblock ?>
                </div> 
                <tbody>
                    <tr>
                        <td width="40%" class="tablrow tabltext">
                            <span class="titleTab nameTtl"><?php echo $section->parametrs->param16 ?></span>
                        </td>               
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp <?php echo $errstname ?> nameInp" value="<?php echo $usrname_inform ?>" name="usrname">
                            <div class="error"><?php echo $errorname ?></div>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow tabltext">
                            <span class="titleTab emailTtl"><?php echo $section->parametrs->param17 ?></span>
                        </td>                
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp <?php echo $errstmail ?> emailInp" value="<?php echo $usrmail_inform ?>" name="usrmail">
                            <div class="error"><?php echo $errormail ?></div>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <label class="titleTab commentTtl"><?php echo $section->parametrs->param18 ?></label>
                            <br>
                            <textarea id="textar" class="inp <?php echo $errstnote ?>" name="note" rows="7" maxlength="<?php echo $section->parametrs->param35 ?>" cols="36"><?php echo $usrnote_inform ?></textarea>
                            <div class="error"><?php echo $errornote ?></div> 
                        </td>                  
                    </tr>
                    
                    <?php echo $anti_spam ?>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <div class="error"><?php echo $errorpin ?></div> 
                        </td>
                    </tr>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <input type="submit" value="<?php echo $section->parametrs->param19 ?>" name="Save" id="but" class="buttonSend">
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </form> 
</div>
