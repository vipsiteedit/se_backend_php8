<footer:js>
[lnk:flags/flags.css]
[js:jquery/jquery.min.js]
[include_js({
    ajax_url: '?ajax[part.id]'       
})]
</footer:js>
<div class="content contRegionSelect" data-type="[part.type]" data-id="[part.id]" [part.style][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>  
    <div class="contentBody">
        <div class="userRegion">
            <i class="glyphicon glyphicon-map-marker"></i>
            <span class="">[lang001]</span>
            <a class="userRegionName" href="#" title="{$full_name}">{$name_city}</a>
        </div>
        <if:[param2]=='Y'>
            <div class="confirmRegion">
                <div class="confirmTitle">
                    [lang002] <span class="nameCity">{$name_city}</span>?
                </div>
                <div class="blockButton">
                    <button class="buttonSend btnConfirm">[lang004]</button>
                    <a class="lnkCancel" href="#">[lang005]</a>     
                </div>
                <span class="close">×</a>
            </div>
        </if>
        <div class="selectRegion">
            <input type="text" placeholder="[lang003]" autocomplete="off">
            <div class="suggestRegions" style="display:none;"><se>{$region_items}</se></div> 
        </div>  
    </div>
</div>
