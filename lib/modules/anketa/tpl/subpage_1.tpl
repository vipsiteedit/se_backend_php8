<div class="content" id="cont_ank">
    <h3 class="contentTitle">
        <span class="contentTitleTxt"><?php echo $section->title ?></span>
    </h3>  
    <div id="autoreply"><?php echo $section->parametrs->param3 ?></div>
    <br clear="all">
    <div id="cntBack">
        <input class="buttonSend" type="button" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>'" value="<?php echo $section->parametrs->param12 ?>">
    </div>
</div>
