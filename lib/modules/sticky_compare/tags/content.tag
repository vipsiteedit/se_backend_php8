<footer:css>
[lnk:rouble/rouble.css]
</footer:css>
<footer:js>
[js:jquery/jquery.min.js]
<script src="[this_url_modul]iscroll-probe.min.js"></script>
[include_js({
    id: [part.id],
    hide_time: [param11],
    min_visible_area: [param12] 
})]
</footer:js>
<div class="content sticky_compare<if:[param13]=='n'> container<else> container-fluid</if>" data-content-type="[part.type]" data-content-id="[part.id]" [part.style][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody">
        [subpage name=2]
    </div>
</div>
