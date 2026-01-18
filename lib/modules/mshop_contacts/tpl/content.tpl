<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param2)=='Y'): ?>
    <script src="http://api-maps.yandex.ru/2.1/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
<?php endif; ?>
</footer:js>
<div class="content contShopContacts" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
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
    <div class="contentBody">
        <?php foreach($section->contacts as $contact): ?>
             <div class="contact-item" data-id="<?php echo $contact->id ?>">
                <h4 class="contact-item-title">
                    <span class=""><?php echo $contact->name ?></span>
                </h4>
                <div class="contact-item-address">
                    <?php echo $section->language->lang001 ?> <span class=""><?php echo $contact->address ?></span>
                </div>
                <div class="contact-item-phone">
                    <?php echo $section->language->lang002 ?> <span class=""><?php echo $contact->phone ?></span>
                </div>
                <div class="contact-item-text">
                    <?php echo $contact->description ?>
                </div>   
                <?php if(strval($section->parametrs->param2)=='Y' && $contact->address != ''): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_ymap.php")) include $__MDL_ROOT."/php/subpage_ymap.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_ymap.tpl")) include $__data->include_tpl($section, "subpage_ymap"); ?>
                <?php endif; ?>
            </div>
        
<?php endforeach; ?> 
    </div>
</div>
