<div class="groups-related">
    <h3>Возможно, вас также заинтересует:</h3>
    <repeat:realted_groups name=related>
        <div class="cellGroup related-item">
            <if:[param35]=='Y'>
                <div class="blockImage">
                    <noempty:[related.image]>
                        <a class="lnkGroupImage" href="[related.link]" title="[related.title]"><img class="subgroupImage" src="[related.image]" alt="[related.image_alt]"></a>
                    </noempty>
                </div>
            </if>
            <div class="blockTitle">
                <a class="lnkGroupName" href="[related.link]" title="[related.title]">[related.name]</a>
                <noempty:[related.count]>
                    <span class="count">([related.count])</span>
                </noempty>
            </div>
        </div>
    </repeat:realted_groups>
</div>
