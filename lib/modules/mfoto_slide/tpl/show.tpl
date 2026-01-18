
    <div class="content photoAlbumSplash <?php $section->id ?>" <?php echo $section->style ?>>
        <div class="photoDetailed" id="view" itemscope itemtype="http://schema.org/ImageObject">
            <?php if(!empty($record->title)): ?>
                <h4 class="objectTitle">
                    <span class="objectTitleTxt" itemprop="name"><?php echo $record->title ?></span>
                </h4>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <img class="objectImage" title="<?php echo $record->image_alt ?>" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0" itemprop="contentUrl">
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <div class="objectText" itemprop="description"><?php echo $record->text ?></div>
            <?php endif; ?> 
            <a class="buttonSend" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->parametrs->param2 ?></a>     
        </div>
    </div>
