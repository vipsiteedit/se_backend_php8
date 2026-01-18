<footer:js>[include_js()]</footer:js>
<header:js>
[lnk:rouble/rouble.css]
[lnk:fancybox2/jquery.fancybox.css]  
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
<if:[param327]=='Y' || [param336]=='Y'>
[js:fancybox2/jquery.fancybox.pack.js] 
</if>
<if:[param319]=='Y'>
[js:jquery/jquery.mousewheel.js] 
[js:jquery/jcarousellite.js]
</if>
<if:[param311]=='Y'> 
[js:jquery/zoomsl.min.js]
</if>
[include_js({
    id: [part.id],
    ajax_url: '?ajax[part.id]',
    param321: '[param321]',
    param307: '[param307]',
    param308: '[param308]',
    param309: '[param309]',
    param233: '[param233]'
})]
</footer:js>
[subpage name=goodlist]
<div class="content e_shopvit" data-type="[part.type]" data-id="[part.id]" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>                         
    <noempty:part.image>
        <img class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_title]" border="0" [part.style_image]>
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="goodsContent">
        <if:{$goodscount}!=0>      
            <div class="blockPanel container-fluid">
                <noempty:{$sort_list}>
                    <div class="vitrineSort pull-left">
                        <form method="post" action="<se>[thispage.link]</se>">
                            <label class="vitrineSortLabel hidden-xs" for="sortOrderby">[lang025]</label>
                            <span class="glyphicon glyphicon-filter hidden-lg hidden-md hidden-sm"></span>
                            <select class="vitrineSortSelect" id="sortOrderby" name="sortOrderby">
                                <repeat:sorts name=sort>
                                    <option value="[sort.value]"<noempty:[sort.selected]> selected</noempty>>[sort.name]</span></option>
                                </repeat:sorts>
                            </select>
                        </form>
                    </div>  
                </noempty>
            <if:'[param183]'=='Y'>
                <div class="changeView pull-right">
                    <form method="post" action="<se>[thispage.link]</se>">
                        <label class="viewLabel hidden-xs">[lang102]</label>
                        <div class="btn-group btn-group-sm">
                            <button class="buttonSend btn btn-default" name="typevitrine" title="[lang034]" <if:[param184]=='v'>disabled</if>><span class="glyphicon glyphicon-th-large"></span></button>
                            <button class="buttonSend btn btn-default" name="typetable" title="[lang035]" <if:[param184]=='t'>disabled</if>><span class="glyphicon glyphicon-th-list"></span></button>
                            <button class="buttonSend btn btn-default" name="typelist" title="[lang103]" <if:[param184]=='l'>disabled</if>><span class="glyphicon glyphicon-align-justify"></span></button>  
                        </div>                        
                    </form>
                </div>
            </if>
            <noempty:{$limit_list}>
                <div class="productsLimit pull-right hidden-sm hidden-xs">
                    <form method="post" action="<se>[thispage.link]</se>">
                        <label class="limitLabel">[lang099]</label>
                        <div class="btn-group  btn-group-sm">
                            <repeat:limits name=limit>
                                <button class="buttonSend btnChangeLimit btn btn-default" name="limit" value="[limit.value]"<noempty:[limit.selected]> disabled</noempty>><span>[limit.value]</span></button>
                            </repeat:limits>
                        </div>
                    </form>
                </div>
            </noempty>
            </div>
            <noempty:({$SE_NAVIGATOR})>
                <div class="goodsNavigator top">{$SE_NAVIGATOR}</div>
            </noempty>
            <div class="goodsGoods <if:'[param184]'=='v'> vitrina</if><if:'[param184]'=='t'> tables</if><if:'[param184]'=='l'> list</if> <if:'[param85]'=='Y'>notAjaxCart</if>">
                <if:'[param184]'=='v'>
                    [subpage name=vitrine]
                <else>
                    <if:'[param184]'=='t'>
                        [subpage name=table]
                    <else>
                        [subpage name=list]
                    </if>
                </if>
            </div>
            <noempty:({$SE_NAVIGATOR})>
                <div class="goodsNavigator bottom">{$SE_NAVIGATOR}</div>
            </noempty>    
        <else>
            <div class="noGoodsIntable">[lang023]</div>
        </if>
        <SE><div class="noGoodsIntable">[lang023]</div></SE>
        <if:([param323]=='Y' && {$footer_text}!='')>
            <noempty:{$noindex}><!--noindex--></noempty>
            <div class="sg_footer_text">
                {$footer_text}
            </div>
            <noempty:{$noindex}><!--/noindex--></noempty>
        </if>
    </div>
    <if:[param336]=='Y'>
        [subpage name=preorder]
    </if>
</div>
