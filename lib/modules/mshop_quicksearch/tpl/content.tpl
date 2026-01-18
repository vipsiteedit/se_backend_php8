<div class="content contSrchcatQuick" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3><?php endif; ?>
    <?php if(!empty($section->image)): ?><img class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>"><?php endif; ?>
    <?php if(!empty($section->text)): ?><div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div><?php endif; ?>
    <form class="searchTable" method="post" action="<?php echo $srch_page ?>" id="quicksearch<?php echo $section->id ?>">
        <div class="srchcat_slsrchfor">     
            <select name="SHOP_SEARCH[text][type]" class="srchcat_srhfor">
                <?php foreach($section->types as $type): ?>
                    <option value="<?php echo $type->name ?>"<?php if($srch_type==$type->name): ?> selected<?php endif; ?>><?php echo $type->title ?></option>
                
<?php endforeach; ?>
            </select>
        </div>
        <div class="srchcat_main">
            <input class="inp_txt" type="text" name="SHOP_SEARCH[text][string]" value="<?php echo $srch_text ?>">
            <input class="buttonSend" type="submit" value="<?php echo $section->language->lang005 ?>" name="quicksearchGo">
        </div>
        <?php if(strval($section->parametrs->param1)!=''): ?><a class="srchcat_linkslc" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>"><?php echo $section->language->lang004 ?></a><?php endif; ?>
    </form>
</div>
