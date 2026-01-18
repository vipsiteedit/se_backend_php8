<div class='row'>
<repeat:[item.items] name=sitem>
    <div class="headerCatalogCol" style="width:{$count_width}%">
        <a href="[sitem.url]" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
          <if:[param7]=='3'>
            <noempty:sitem.image><img class="catalogPromoIcon img-responsive" style="max-width:[param20]px; max-height:[param21]px" src="[sitem.image]" alt=""></noempty></if>
          [sitem.title]             
        </a>
        <if:([sitem.items])>
        <ul class="subsubitems">
        <repeat:[sitem.items] name=ssitem>
            <li>
            <a href="[ssitem.url]" class="headerCatalogSubItem headerCatalogSubNormal">[ssitem.title]</a>
            <ul class="subsubsubitems">
                <repeat:[ssitem.items] name=sssitem>
                <li>
                    <a href="[sssitem.url]" class="headerCatalogSubItem headerCatalogSubNormal">[sssitem.title]</a>
                </li>
                </repeat:[ssitem.items]>
            </ul>
            </li> 
        </repeat:[sitem.items]>
        </ul>
        </if>
    </div>
    <if:fmod(intval([sitem.itemrow]),intval([param15]))==0> 
        </div><div class='row'>
    </if>
</repeat:[item.items]>
</div>
