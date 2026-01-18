<header:js>
[lnk:rouble/rouble.css]
</header:js>
<div class="content contCartInformer" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="informer">
        <div class="infoShopCard">
            <noindex>
                <a class="infoLink" href="<?php echo seMultiDir()."/".$section->parametrs->param2."/" ?>" rel="nofollow"><?php echo $section->language->lang006 ?></a>
            </noindex>
            <span class="infoCount"><?php echo $show_items_count ?></span>
        </div> 
        <div class="infoShopPrice">
            <span class="infoSummTxt"><?php echo $section->language->lang005 ?></span>
            <span class="infoSumm"><?php echo $show_order_sum ?></span>
        </div> 
    </div>
</div>
