<div class="content cont_mail" <?php echo $section->style ?> >
<?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3> <?php endif; ?>
<?php if(!empty($section->image)): ?><img alt="<?php echo $section->image_alt ?>" border="0px" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>"><?php endif; ?>
<?php if(!empty($section->text)): ?><div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> <?php endif; ?>
<form action="" name="form" method="post">
    <div class="err">
        <?php echo $globalerr ?> 
    </div> 
    <table border="0" class="tableTable" width="600">
        <tbody> 
            <tr> 
                <td width="30%" class="tabletitle"><div class="divtitle"><?php echo $section->parametrs->param16 ?></div>
                </td> 
                <td class="tablefield"> 
                    <input name="name" type="text" maxlength="20" <?php echo $glob_err_stryle ?> value="<?php echo $name ?>" class="inpfield <?php echo $errstname ?>">
                    <div class="err"><?php echo $nameerr ?> </div> 
                </td> 
            </tr> 
            <tr> 
                <td class="tabletitle"><div class="divtitle"><?php echo $section->parametrs->param17 ?></div> 
                </td> 
                <td class="tablefield">
                    <input name="mail" type="text" maxlength="30" <?php echo $glob_err_stryle ?> value="<?php echo $email ?>" class="inpfield <?php echo $errstmail ?>">
                    <div class="err"><?php echo $emailerr ?></div> 
                </td> 
            </tr> 
            <tr> 
                <td colspan="2" class="tablearrea">
                    <div class="texttitle"><?php echo $section->parametrs->param18 ?></div> 
                    <div class="blockarea"> <textarea cols="38" rows="6" class="inp <?php echo $errstnote ?> textarea" name="note" <?php echo $glob_err_stryle ?>><?php echo $note ?></textarea> </div> 
                    <div class="err"><?php echo $noteerr ?> </div> 
                </td> 
            </tr> 
            
            <?php if(strval($section->parametrs->param3)=='Yes'): ?>
                <tr> 
                    <td colspan="2" class="tablrow">
                        <?php echo $anti_spam ?>
                    </td> 
                </tr> 
            <?php endif; ?>
            <tr><td colspan="2">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
            </td></tr>
            <?php if($errorlicense!=''): ?>
                <div class="error">
                    <span><?php echo $errorlicense ?></span>
                </div>
            <?php endif; ?>
            
            <tr> 
                <td colspan="2" class="tablerow">
                <input class="buttonSend" id="Send" name="Send" <?php echo $glob_err_stryle ?> value="<?php echo $section->parametrs->param2 ?>" type="submit" ></td> 
            </tr> 
        </tbody> 
    </table> 
</form> 

</div> 
