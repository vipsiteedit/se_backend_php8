<footer:js>
[js:jquery/jquery.min.js]
</footer:js>
<div class="content contShopPhone" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>">
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>  
    <div class="contentBody">
        <div class="phone contact-phone-geo">
            <a class="lnk-phone" href="tel:<?php echo $phone_href ?>"><?php echo $phone ?></a>        
        </div>
    </div>
</div>
