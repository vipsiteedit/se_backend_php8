<footer:js>
[lnk:flags/flags.css]
[js:jquery/jquery.min.js]
[include_js({
    ajax_url: '?ajax<?php echo $section->id ?>'       
})]
</footer:js>
<div class="content contRegionSelect" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" >
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>  
    <div class="contentBody">
        <div class="userRegion">
            <i class="glyphicon glyphicon-map-marker"></i>
            <span class=""><?php echo $section->language->lang001 ?></span>
            <a class="userRegionName" href="#" title="<?php echo $full_name ?>"><?php echo $name_city ?></a>
        </div>
        <?php if(strval($section->parametrs->param2)=='Y'): ?>
            <div class="confirmRegion">
                <div class="confirmTitle">
                    <?php echo $section->language->lang002 ?> <span class="nameCity"><?php echo $name_city ?></span>?
                </div>
                <div class="blockButton">
                    <button class="buttonSend btnConfirm"><?php echo $section->language->lang004 ?></button>
                    <a class="lnkCancel" href="#"><?php echo $section->language->lang005 ?></a>     
                </div>
                <span class="close">×</a>
            </div>
        <?php endif; ?>
        <div class="selectRegion">
            <input type="text" placeholder="<?php echo $section->language->lang003 ?>" autocomplete="off">
            <div class="suggestRegions" style="display:none;"></div> 
        </div>  
    </div>
</div>
