<div class="content" id="cont_link"<?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <a href="<?php echo $section->image ?>" target="_blank">
            <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
        </a> 
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <table border="0" cellpadding="0" cellspacing="0" class="tableTable">
        <tbody class="tableBody">
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <tr id="tableHeader" vAlign="top">
                    <td class="tableRow" colspan="2">
                        <a class="linkobject" href="<?php echo $record->field ?>" target="_blank"><?php echo $record->title ?></a> &nbsp;
                    </td>
                </tr> 
                <tr>
                    <td width="95" vAlign="top" id="bfield">
                        <?php if(!empty($record->image)): ?>
                            <a href="<?php echo $record->field ?>" target="_blank">
                                <img src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" border="0">
                            </a> 
                        <?php endif; ?>
                        <?php echo $record->note ?>&nbsp;
                    </td> 
                    <td vAlign="top" id="tfield"><?php echo $record->text ?>&nbsp;</td> 
                </tr> 
            
<?php endforeach; ?>
        </tbody> 
    </table> 
</div> 
