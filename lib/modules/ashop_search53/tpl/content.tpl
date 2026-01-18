<?php if(strval($section->parametrs->param19)=='Y'): ?>
<footer:js>
[js:jquery/jquery.min.js]
[include_js({
    ajax_url: '?ajax<?php echo $section->id ?>',
    min_length: <?php echo $section->parametrs->param14 ?>
})]
</footer:js>
<?php endif; ?>
<div class="content contShopSearch" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">    
        <form method="get" action="<?php if(!empty($page_vitrine)): ?><?php echo $page_vitrine ?>.html<?php endif; ?>">
            <div class="searchContent input-group">
                <input type="text" id="livesearch" class="form-control" name="q" value="<?php echo $query ?>" placeholder="<?php echo $section->language->lang001 ?>" autocomplete="off">               <span class="input-group-btn">
                    <button class="buttonSend btn btn-default" title="<?php echo $section->language->lang002 ?>">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                
            </div>
        </form> 
    </div> 
</div>
