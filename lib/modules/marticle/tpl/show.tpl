
    <div class="content contArt">
        <div id="view">
            <?php if(!empty($record->title)): ?>
                <<?php echo $section->title_tag ?> class="objectTitle record-title"><?php echo $record->title ?></<?php echo $section->title_tag ?>>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <div id="objimage record-image">
                    <img border="0" class="objectImage" src="<?php echo $record->image ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                </div>
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote record-note"><?php echo $record->note ?></div>
            <?php endif; ?>
            <div class="objectText record-text"><?php echo $record->text ?></div>
            <div class="buttonArea">
                <input class="buttonSend" onclick='location.href="<?php echo $__data->getLinkPageName() ?>"' type="button" value="<?php echo $section->language->lang027 ?>">
            </div>
           <?php if(file_exists($__MDL_ROOT."/php/subpage_sub2.php")) include $__MDL_ROOT."/php/subpage_sub2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_sub2.tpl")) include $__data->include_tpl($section, "subpage_sub2"); ?>
        </div>
    </div>
