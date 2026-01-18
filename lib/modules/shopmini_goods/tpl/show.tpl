
    <div class="content shopmini">
        <div id="show">
        <div class="record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <?php if(!empty($record->title)): ?>
                <h4 class="objectTitle record-title"><?php echo $record->title ?></h4> 
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <div id="objimage" class="record-image">
                    <img class="objectImage" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                </div>
            <?php endif; ?>
            <div class="objectCode">
                <span class="objectCodeTitle"><?php echo $section->parametrs->param7 ?></span>
                <span class="objectCodeVal" record-text1><?php echo $record->text1 ?></span>
            </div>
            <div class="specprice">
                <span class="specpriceVal record-field"><?php echo $record->field ?></span>
                <span class="specpriceTitle"><?php echo $section->parametrs->param6 ?></span>
            </div> 
            <?php if(!empty($record->note)): ?>
                <div class="objectNote record-note"><?php echo $record->note ?></div> 
            <?php endif; ?>
            <div class="objectText record-text"><?php echo $record->text ?></div> 
            <div class="objectSubm">
                <form style="margin:0px;" method="POST">
                    <input type="hidden" name="addcartspecial" value="<?php echo $record->id ?>">
                    <input type="hidden" name="partid" value="<?php echo $section->id ?>">  
                    <input class="buttonSend send" type="submit"  value="<?php echo $section->parametrs->param3 ?>">
                </form>  
                <input class="buttonSend back" onclick="document.location.href='<?php echo $_SESSION['SHOP_MINI_PAGE']['page'] ?>'" type="button" value="<?php echo $section->parametrs->param2 ?>"> 
            </div>
        </div> 
    </div>
    </div>
   
