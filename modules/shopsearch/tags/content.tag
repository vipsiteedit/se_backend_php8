<if:[param19]=='Y'>
<footer:js>
[js:jquery/jquery.min.js]
[include_js({
    ajax_url: '?ajax[part.id]',
    min_length: [param14]
})]
</footer:js>
</if>
<div class="content contShopSearch" data-type="[part.type]" data-id="[part.id]" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody">    
        <form method="get" action="<noempty:{$page_vitrine}>{$page_vitrine}.html</noempty>">
            <div class="searchContent input-group">
                <input type="text" id="livesearch" class="form-control" name="q" value="{$query}" placeholder="[lang001]" autocomplete="off">               <span class="input-group-btn">
                    <button class="buttonSend btn btn-default" title="[lang002]">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <se><if:[param19]=='Y'>{$suggestion}{$preloader}</if></se>
            </div>
        </form> 
    </div> 
</div>
