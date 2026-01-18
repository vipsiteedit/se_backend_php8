<div class="content contSpecial">
    <div id="view">
        <h4 class=objectTitle><?php echo $objecttitle ?></h4>
        
        <?php echo $objectimage ?>
        <div class="baydiv">
            <div class="dprice">
                <span class="oldPrice" style="text-decoration:line-through;"><?php echo $objectprice ?></span>
                <span class="newPrice"><?php echo $object_new_price ?></span>
            </div>
            <form style="margin:0px;" method="post">
                <input type="hidden" name="addcart" value="<?php echo $objectid ?>">
                <input size="3" class="cartcount" type="text" name="addcartcount" value="1">
                <input class="buttonSend buy" type="submit" name="specialsubmit" value="<?php echo $section->language->lang007 ?>">
            </form>
        </div>
        <div class="objectNote"><?php echo $objectnote ?></div>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__data->include_tpl($section, "subpage_2"); ?>
        <div class="objectText"><?php echo $objecttext ?></div>
        <input class="buttonSend back" onclick="window.history.back(-1);" type="button" value="<?php echo $section->language->lang006 ?>">
    </div>
</div>
