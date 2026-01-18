<div class="row">
<repeat:catalog name=sitem>
    <div class="headerCatalogCol" style="width:{$count_width}%">
            <a href="{$path}[sitem.link]" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
                <if:[param7]=='3'>
                    <noempty:sitem.image><img class="catalogPromoIcon img-responsive" style="max-width:[param20]px; max-height:[param21]px" src="{$image_dir}[sitem.image]" alt=""></noempty> 
                </if>
                <span class="headerCatalogName">[sitem.name]</span>
            </a>
            <ul class="subsubitems">
                <repeat:scatalog[sitem.id] name=ssitem>
                    <li>
                        <a href="{$path}[ssitem.link]" class="headerCatalogSubItem headerCatalogSubNormal">[ssitem.name]</a>
                        <ul class="subsubitems">
                            <repeat:sscatalog[ssitem.id] name=sssitem>
                                <li>               
                                    <a href="{$path}[sssitem.link]" class="headerCatalogSubItem headerCatalogSubNormal">
                                        <if:[param7]=='3'>
                                            <noempty:sssitem.image><img class="catalogPromoIcon img-responsive"  style="max-width:[param20]px; max-height:[param21]px" src="{$image_dir}[sitem.image]" src="{$image_dir}[sssitem.image]" alt=""></noempty>
                                        </if> 
                                        <span class="headerCatalogName">[sssitem.name]</span>
                                    </a>
                                </li> 
                            </repeat:sscatalog[ssitem.id]>
                        </ul>
                    </li> 
                </repeat:scatalog[sitem.id]>
            </ul>
    </div>
    <if:fmod(intval([sitem.itemrow]),intval({$counts}))==0> 
        </div><div class='row'>
    </if>
</repeat:catalog>
</div>
             
                                               
