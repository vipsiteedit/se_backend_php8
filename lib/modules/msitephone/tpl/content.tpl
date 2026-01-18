<div class="content cont_sitephone"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?>
    <object name="callme" width=' <?php if(strval($section->parametrs->param2)=="1"): ?>200<?php endif; ?><?php if(strval($section->parametrs->param2)=="2"): ?>240<?php endif; ?><?php if(strval($section->parametrs->param2)=="3"): ?>300<?php endif; ?><?php if(strval($section->parametrs->param2)=="4"): ?>468<?php endif; ?>' height=' <?php if(strval($section->parametrs->param2)=="1"): ?>100<?php endif; ?><?php if(strval($section->parametrs->param2)=="2"): ?>400<?php endif; ?><?php if(strval($section->parametrs->param2)=="3"): ?>250<?php endif; ?><?php if(strval($section->parametrs->param2)=="4"): ?>60<?php endif; ?>' align="middle">
        <embed class="flphone" src='<?php if(strval($section->parametrs->param1)!="none"): ?><?php if(strval($section->parametrs->param2)=="1"): ?>http://universe.uiscom.ru/media/flash/callme200x100<?php endif; ?><?php if(strval($section->parametrs->param2)=="2"): ?>http://universe.uiscom.ru/media/flash/callme240x400<?php endif; ?><?php if(strval($section->parametrs->param2)=="3"): ?>http://universe.uiscom.ru/media/flash/callme300x250<?php endif; ?><?php if(strval($section->parametrs->param2)=="4"): ?>http://universe.uiscom.ru/media/flash/callme468x60<?php endif; ?><?php endif; ?><?php if(strval($section->parametrs->param1)=="none"): ?>[this_url_modul]sitephone_200x100_04<?php endif; ?>.swf?h=<?php echo $section->parametrs->param5 ?>&color=<?php echo $section->parametrs->param1 ?>&person=<?php echo $section->parametrs->param3 ?>&text=<?php echo $section->parametrs->param4 ?>' width=' <?php if(strval($section->parametrs->param2)=="1"): ?>200<?php endif; ?><?php if(strval($section->parametrs->param2)=="2"): ?>240<?php endif; ?><?php if(strval($section->parametrs->param2)=="3"): ?>300<?php endif; ?><?php if(strval($section->parametrs->param2)=="4"): ?>468<?php endif; ?>' height=' <?php if(strval($section->parametrs->param2)=="1"): ?>100<?php endif; ?><?php if(strval($section->parametrs->param2)=="2"): ?>400<?php endif; ?><?php if(strval($section->parametrs->param2)=="3"): ?>250<?php endif; ?><?php if(strval($section->parametrs->param2)=="4"): ?>60<?php endif; ?>' WMode="transparent" quality="high" bgcolor="#ffffff" name="callme" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
    </object>
    
</div>
