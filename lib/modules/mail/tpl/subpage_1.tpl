<div class="content" id="cont_mail">
        <h3 class="contentTitle"><span class="contentTitleTxt"><?php echo $section->title ?></span></h3>  
        <div id="autoreply"><?php echo $section->parametrs->param15 ?></div><br clear="all">
            <input class="buttonSend" id="getBack" value="<?php echo $section->parametrs->param19 ?>" type="submit"  onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>'">
</div>
