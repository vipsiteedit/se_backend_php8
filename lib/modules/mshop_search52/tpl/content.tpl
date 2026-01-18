<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param19)=='Y'): ?>
[include_js({'id':<?php echo $section->id ?>,'mlength':'<?php echo $section->parametrs->param14 ?>'})]
<?php endif; ?>
</footer:js>
<div class="content contShopSearch" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">    
        <form style="margin:0px;" method="get" action="<?php if(!empty($page_vitrine)): ?><?php echo $page_vitrine ?>.html<?php endif; ?>">
            <div class="searchContent">
                <input type="text" name="q" id="livesearch" placeholder="<?php echo $section->language->lang001 ?>" autocomplete="off" value="<?php echo $query ?>">
                <button class="buttonSend btnSearch" title="<?php echo $section->language->lang002 ?>"><?php echo $section->language->lang002 ?></button>
                
            </div>
        </form> 
    </div> 
</div>
