<div class="content contDesk" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($access_add==true): ?>
        <a class="newMsg" title="<?php echo $section->language->lang011 ?>" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>"><?php echo $section->language->lang011 ?></a> 
    <?php endif; ?>
    <div class="Cnri">
        
            <form style="margin:0px;" action="" method="post">
        
        <div class="title"><?php echo $section->language->lang013 ?></div> 
        <div class="CnriBox">
            <select class="CnriSel" name="townselected">
                <option value=""> ---- </option>
                <?php foreach($section->selecttown as $town): ?>
                    <option value="<?php echo $town->town ?>" <?php echo $town->selected ?>><?php echo $town->town ?></option>
                
<?php endforeach; ?>
            </select>
        </div> 
        <div class="buttonArea">
            <input class="buttonSend" type="submit" value="<?php echo $section->language->lang014 ?>">
        </div>
        
            </form>
        
    </div>
    <?php echo $MANYPAGE ?>
    <?php foreach($section->messags as $record): ?>
        <div class="object">
            <<?php echo $record->title_tag ?> class="objectTitle">
                <span class="objectTitleTxt"><?php echo $record->title ?></span>
            </<?php echo $record->title_tag ?>>
            <?php if(!empty($record->image)): ?>
                <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>id/<?php echo $record->id ?>/"><img border="0" class="objectImage" src="<?php echo $record->image ?>" alt="<?php echo $record->alt ?>" width="<?php echo $section->parametrs->param4 ?>"></a>
            <?php endif; ?>
            <div class="objectNote">
                <a class="shortText" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>id/<?php echo $record->id ?>/"><?php echo $record->note ?></a>
            </div>
            <div class="contact">
                <div class="obj date">
                    <div class="name"><?php echo $section->language->lang016 ?></div>
                    <b class="text"><?php echo $record->date ?></b>
                </div>
                <div class="obj authorName">
                    <div class="name"><?php echo $section->language->lang017 ?></div>
                    <b class="text"><?php echo $record->name ?></b>
                </div>
                <div class="obj town">
                    <div class="name"><?php echo $section->language->lang018 ?></div>
                    <b class="text"><?php echo $record->town ?></b>
                </div>
                <div class="obj phone">
                    <div class="name"><?php echo $section->language->lang039 ?></div>
                    <b class="text"><?php echo $record->phone ?></b>
                </div>
                <div class="obj email">
                    <div class="name"><?php echo $section->language->lang019 ?></div>
                    <a class="text" href="mailto:<?php echo $record->email ?>"><?php echo $record->email ?></a>
                </div>
                <div class="obj url">
                    <div class="name"><?php echo $section->language->lang020 ?></div>
                    <a class="text" href="http://<?php echo $record->url ?>"><?php echo $record->url ?></a>
                </div>
            </div>
            <?php if($record->access==true): ?>
                <div class="footEditBlock">
                    <a class="buttonSend editbbs" style="text-decoration:none;" title="<?php echo $section->language->lang012 ?>" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>id/<?php echo $record->id ?>/"><?php echo $section->language->lang012 ?></a>
                </div> 
            <?php endif; ?>  
        </div>
    
<?php endforeach; ?>
    <?php echo $MANYPAGE ?>
    
</div> 
