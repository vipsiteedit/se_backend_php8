<div class="search-head" style="top:0px; width: 1181px; height: 100%; display: none;">
<form method="get" action="<?php if(!empty($page_vitrine)): ?><?php echo $page_vitrine ?>.html<?php endif; ?>" style="margin: 0;">
        <input type="text" id="headersearch" class="livesearch" name="q" value="<?php echo $query ?>" placeholder="<?php echo $section->language->lang001 ?>" autocomplete="off">
        <!--div class="suggestions" style=""></div>
        <span class="preloader" style=""></span-->
        <button class="buttonSend btn btn-default" title="<?php echo $section->language->lang002 ?>" style="">
             <span class="glyphicon glyphicon-search"></span>
        </button>
</form> 
</div>
