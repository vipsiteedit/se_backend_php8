<?php if(strval($section->parametrs->param2)!='d'): ?><div class="<?php if(strval($section->parametrs->param2)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<article class="content news-public">
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($editobject!='N'): ?>
        <a class="addLink" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>"><?php echo $section->language->lang002 ?></a>
    <?php endif; ?> 
    <div class="muchpages top"> 
        <?php echo $MANYPAGE ?>
    </div>
    <?php foreach($section->newss as $record): ?>
        <section class="object">
            <h4 class="objectTitle">
                <span class="dataType_date"><?php echo $record->news_date ?></span>
                <a class="textTitle" href="<?php echo $record->shownews ?>"><?php echo $record->title ?></a>
            </h4>
            <div class="newsContainer">
                <?php if(!empty($record->image_prev)): ?>
                    <a class="objectImageLink" href="<?php echo $record->shownews ?>">
                        <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>">
                    </a>                                               
                <?php endif; ?> 
                <div class="objectNote"><?php echo $record->note ?></div>
                <?php if(strval($section->parametrs->param32)=='Y'): ?>
                    <a class="newsLink" href="<?php echo $record->shownews ?>"><?php echo $section->parametrs->param37 ?></a>
                <?php endif; ?>
            </div> 
            <?php if($editobject!='N'): ?>
                <div class="objectPanel">
                    <a class="recordEdit" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>id/<?php echo $record->id ?>/"><?php echo $section->language->lang011 ?></a>
                    <a class="recordDelete" href="<?php echo seMultiDir()."/".$_page."/" ?>delete/<?php echo $record->id ?>/"><?php echo $section->language->lang018 ?></a>         
                </div>
            <?php endif; ?> 
        </section> 
    
<?php endforeach; ?>
    <div class="muchpages bottom">
        <?php echo $MANYPAGE ?>
    </div>
</article>
<?php if(strval($section->parametrs->param2)!='d'): ?></div><?php endif; ?>
