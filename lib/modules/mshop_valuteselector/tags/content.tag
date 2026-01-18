<div class="content valuteSelect" data-type="[part.type]" data-id="[part.id]" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span id="title">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody">
        <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
            <div class="txtValuteSelect">[param1]&nbsp;</div> 
            <div class="divValuteSelect">
                <select class="ValuteSelect" name="pricemoney" onChange="submit();">
                    <repeat:currency name=cur>
                        <option value="[cur.name]" [cur.sel]>[cur.title]</option>
                    </repeat:currency>
                </select> 
            </div> 
        </form> 
    </div> 
</div> 
