<?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3><?php endif; ?>
<?php if(!empty($section->image)): ?><img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>"><?php endif; ?>
<?php if(!empty($section->text)): ?><div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div><?php endif; ?>
<div id='menu'>
    <a id='adm_lnkForums' href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->language->lang058 ?></a>
    <a id='adm_lnkUsers' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>"><?php echo $section->language->lang059 ?></a>
    <a id='sulink' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub8/" ?>"><?php echo $section->language->lang060 ?></a>
</div>

