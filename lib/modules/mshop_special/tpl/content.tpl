<div class="content contSpecial" <?php echo $section->style ?>>
    <?php if($count!='0'): ?>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>  
    <?php endif; ?>
    <?php foreach($section->objects as $record): ?>
        <div class="object">
            <h4 class="objectTitle"><?php echo $record->title ?></h4> 
            <span class="clarticle"><b><?php echo $section->language->lang008 ?></b><?php echo $record->article ?></span> 
            <?php if(!empty($record->image_prev)): ?>
                <div class="blockImage">
                    <?php if($section->parametrs->param9=='N'): ?>
                        <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>id/<?php echo $record->id ?>/">
                    <?php else: ?>
                        <?php if($section->parametrs->param15=='N'): ?>
                            <a href="<?php echo $siteAddr ?>/<?php echo $section->parametrs->param10 ?>/viewgoods/<?php echo $record->id ?>/">
                        <?php else: ?>
                            <a href="<?php echo $siteAddr ?>/<?php echo $section->parametrs->param10 ?>/show/<?php echo $record->code ?>/">
                        <?php endif; ?>
                    <?php endif; ?>
                            <img border="0" class="objectImage" title="<?php echo $record->title ?>" alt="<?php echo $record->title ?>" src="<?php echo $record->image_prev ?>">
                        </a> 
                </div>                     
            <?php endif; ?>            
            <div class="objectNote"><?php echo $record->note ?></div>
            <span class="specprice"><?php echo $record->oldprice ?><?php echo $record->newprice ?></span>
            <form style="margin:0px;" method="post">
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                <input size="3" class="cartcount" type="text" name="addcartcount" value="1">
                <div class="buttonArea">
                    <input name="specialsubmit" class="buttonSend buy" type="submit" value="<?php echo $section->language->lang007 ?>">
                    <?php if($section->parametrs->param9=='N'): ?>
                        <input class="buttonSend linkNext" type="button" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>id/<?php echo $record->id ?>/';" value="<?php echo $section->language->lang005 ?>">
                    <?php else: ?>
                        <?php if($section->parametrs->param15=='N'): ?>
                            <input class="buttonSend linkNext" type="button" onclick="document.location.href='<?php echo $siteAddr ?>/<?php echo $section->parametrs->param10 ?>/viewgoods/<?php echo $record->id ?>/';" value="<?php echo $section->language->lang005 ?>">
                        <?php else: ?>
                            <input class="buttonSend linkNext" type="button" onclick="document.location.href='<?php echo $siteAddr ?>/<?php echo $section->parametrs->param10 ?>/show/<?php echo $record->code ?>/';" value="<?php echo $section->language->lang005 ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </form> 
        </div> 
    
<?php endforeach; ?>
</div> 
