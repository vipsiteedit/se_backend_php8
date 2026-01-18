<if:{$filtercount}!=0>  
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
</if>
