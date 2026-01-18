<div class="content shopmini_info" [part.style][contedit]>
<noempty:part.title>
    <[part.title_tag] class="contentTitle" [part.style_title]>
        <span class="contentTitleTxt">[part.title]</span>
    </[part.title_tag]>
</noempty>
<noempty:part.image>
    <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
</noempty>
<noempty:part.text>
    <div class="contentText"[part.style_text]>[part.text]</div>
</noempty>
    <div class="content_info" onclick="document.location.href='[param2].html'">
        <span class="common_goods">[lang001] 
            <span class="ord_num">{$count_order}</span>
        </span>
        <span class="common_summ">[lang002] 
            <span class="ord_sum">{$summa_order}</span>
            <span class="ord_kurs">[param1]</span>
        </span>
    </div>
</div>      
