<div class="content valuteSelect" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span id="title"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
            <div class="txtValuteSelect"><?php echo $section->parametrs->param1 ?>&nbsp;</div> 
            <div class="divValuteSelect">
                <select class="ValuteSelect" name="pricemoney" onChange="submit();">
                    <?php foreach($section->currency as $cur): ?>
                        <option value="<?php echo $cur->name ?>" <?php echo $cur->sel ?>><?php echo $cur->title ?></option>
                    
<?php endforeach; ?>
                </select> 
            </div> 
        </form> 
    </div> 
</div> 
