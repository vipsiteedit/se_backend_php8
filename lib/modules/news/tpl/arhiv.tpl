
    <div class="content" id="arh_news">
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3> 
        <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
        <ul> 
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <li class="arhivTitle">
                    <a id="links" href="<?php echo $record->link_detail ?>"><?php echo $record->field ?> <?php echo $record->title ?></a>
                </li>
            
<?php endforeach; ?>
        </ul> 
        <input class="buttonSend" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->parametrs->param4 ?>">
    </div> 
