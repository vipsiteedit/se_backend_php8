<?php if(strval($section->parametrs->param8)!='d'): ?><div class="cont-text-container <?php if(strval($section->parametrs->param8)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
    <article class="content cont-text part<?php echo $section->id ?>">
        <?php if(!empty($section->title)): ?>
            <header>
                <<?php echo $section->title_tag ?> class="contentTitle content-title">
                    <span  class="contentTitleTxt" data-content="title"><?php echo $section->title ?></span> 
                </<?php echo $section->title_tag ?>>
            </header> 
        <?php endif; ?>
        <?php if(!empty($section->image)): ?>
            <div class="content-image" data-content="image" >
                <img class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
            </div>
        <?php endif; ?>
        <?php if(!empty($section->text)): ?>
            <div class="contentText content-text" data-content="text"><?php echo $section->text ?></div> 
        <?php endif; ?>
        <div class="contentBody">   
            <?php echo $__data->linkAddRecord($section->id) ?>
            <nav class="class-navigator top">
                <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
            </nav>
            <div class="records-container">
                <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                    <section class="object record-item obj<?php echo $record->id ?>" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                        <?php if(!empty($record->title)): ?>
                        <header>
                            <<?php echo $record->title_tag ?> class="object-title objectTitle">
                                <span class="objectTitleTxt" data-record="title"><?php echo $record->title ?></span> 
                            </<?php echo $record->title_tag ?>>
                        </header> 
                        <?php endif; ?>
                        <?php if(!empty($record->image)): ?>
                            <div class="objectImage object-image" data-record="image">
                                <img class="objectImg object-img" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($record->note)): ?>
                            <div class="objectNote object-note" data-record="note"><?php echo $record->note ?></div> 
                        <?php endif; ?>
                        <?php if(!empty($record->text)): ?>
                            <a class="linkNext link-next" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $section->parametrs->param1 ?></a> 
                        <?php endif; ?>
                    </section> 
                
<?php endforeach; ?>
            </div>   
            <nav class="class-navigator bottom">
                <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
            </nav>
        


        </div>
    </article>
<?php if(strval($section->parametrs->param8)!='d'): ?></div><?php endif; ?>
