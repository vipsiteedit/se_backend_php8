<footer:js>
[js:jquery/jquery.min.js]
<if:[param1]=='Y'>
[lnk:ionrangeslider/ionrangeslider.min.css]  
[js:ionrangeslider/ionrangeslider.min.js]
</if>
[include_js({
    ajax_url: '?ajax[part.id]',
    param4:'[param4]',
    partNum: '[part.id]',
    param2:'[param2]',
    param3:'[param3]',
    param1:'[param1]'
     
})]
</footer:js> 
<if:{$filtercount}!=0> 
<div class="content shopFilter" data-type="[part.type]" data-id="[part.id]" [contentstyle][contedit]> 
<noempty:part.title> 
  <[part.title_tag] class="contentTitle" [part.style_title]><span class="contentTitleTxt">[part.title]</span></[part.title_tag]> 
</noempty> 
<noempty:part.image> 
  <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
</noempty> 
<noempty:part.text> 
  <div class="contentText" [part.style_text]>[part.text]</div> 
</noempty>
<div class="contentBody">
    <div class="filterNotify" style="display:none;">
        <div class="productsFound">
            [lang001] <span class="countFound">{$count_price_found}</span>
        </div>
        <div class="showProducts">
            <a href="javascript:void(0);" title="[lang002]">[lang006]</a>
        </div>
        <div class="notifyOverlay"></div>
    </div>
    <form style="margin:0px" method="get" id="filterForm" action="<noempty:{$page_vitrine}>{$page_vitrine}</noempty>">
        <div class="filtersList" id="filterList[part.id]">
            [subpage name=filters]
        </div>
            <div class="blockButton">     
                <button class="buttonSend btnSearch" title="[lang002]">
                    <span>[lang006]</span>
                </button>
                <button class="buttonSend btnClear" title="[lang008]">
                    <span>[lang007]</span>
                </button>
            </div>
    </form>
</div>
</div></if>
