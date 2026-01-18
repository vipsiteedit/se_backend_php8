<?php if(strval($section->parametrs->param38)!='d' && strval($section->parametrs->param38)!=''): ?>
<div class="<?php if(strval($section->parametrs->param38)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content contOnNews" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($editobject!='N'): ?>
        <a class="addLink" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>"><?php echo $section->language->lang002 ?></a>
    <?php endif; ?> 
    <div class="muchpages top"> 
        <?php echo $MANYPAGE ?>
    </div>
    <?php foreach($section->newss as $record): ?>
        <div class="object">
            <h4 class="objectTitle">
                <span class="dataType_date"><?php echo $record->date ?></span>
                <a class="textTitle" href="<?php echo $record->shownews ?>"><?php echo $record->title ?></a>
            </h4>
            <div class="newsContainer">
                <?php if(!empty($record->image_prev)): ?>
                    <a class="objectImageLink" href="<?php echo $record->shownews ?>">
                        <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>">
                    </a>                                               
                <?php endif; ?> 
                <div class="objectNote"><?php echo $record->text ?></div>
                <?php if(strval($section->parametrs->param32)=='Y'): ?>
                    <a class="newsLink" href="<?php echo $record->shownews ?>"><?php echo $section->parametrs->param37 ?></a>
                <?php endif; ?>
            </div> 
            <?php if($editobject!='N'): ?>
                <div class="objectPanel">
                    <a class="recordEdit" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>id/<?php echo $record->id ?>/"><?php echo $section->language->lang011 ?></a>
                    <a class="recordDelete" href="<?php echo $__data->getLinkPageName() ?>delete/<?php echo $record->id ?>/"><?php echo $section->language->lang018 ?></a>         
                </div>
            <?php endif; ?> 
        </div> 
    
<?php endforeach; ?>
    <div class="muchpages bottom">
        <?php echo $MANYPAGE ?>
    </div>
</section>
<?php if(strval($section->parametrs->param38)!='d' && strval($section->parametrs->param38)!=''): ?>
</div><?php endif; ?>
