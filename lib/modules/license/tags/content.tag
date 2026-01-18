<footer:js>
[js:jquery/jquery.min.js]
[lnk:fancybox2/jquery.fancybox.css] 
[js:fancybox2/jquery.fancybox.pack.js]
[include_js()]
</footer:js>
<div class="content contLicense textLicense" data-type="[part.type]" data-id="[part.id]" [part.style][contedit] style="display:none;">
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"><span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
</div>
