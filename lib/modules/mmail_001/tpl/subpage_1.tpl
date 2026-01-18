<div class="mail001">
    <h3 class="contentTitle">
        <span class="contentTitleTxt"><?php echo $section->title ?></span>
    </h3>
    <div id="autoreply"><?php echo $section->parametrs->param3 ?></div>
    <div id="ml001_divbtn">
    <input class="buttonSend" type="button" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>'" value="<?php echo $section->language->lang009 ?>">
    </div>
</div>
