<footer:js>
[js:jquery/jquery.min.js]
</footer:js>
<div class="content contShopPhone" data-type="[part.type]" data-id="[part.id]"[contedit]>
    <noempty:part.title>
        <h3 class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </h3>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>  
    <div class="contentBody">
        <div class="phone contact-phone-geo">
            <a class="lnk-phone" href="tel:{$phone_href}">{$phone}</a>        
        </div>
    </div>
</div>
