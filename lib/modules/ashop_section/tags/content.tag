<div class="content shopsection" data-type="[part.type]" data-id="[part.id]" [part.style][contedit]>
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
    <repeat:items name=item>
        <div class="section-item">
            <noempty:[item.title]>
                <div class="item-name"><h4>[item.title]</h4></div>
            </noempty>
            <noempty:[item.text]>
                <div class="item-note">[item.text]</div>
            </noempty>
            <noempty:[item.picture]>
                <div class="item-image"><img src="[item.picture]" alt="[item.picture_alt]" style=""></div>
            </noempty>
            <noempty:[item.url]>
                <div class="item-link"><a href="[item.url]">[lang001]</a></div>
            </noempty>
        </div>    
    </repeat:items>
  </div>
</div>
