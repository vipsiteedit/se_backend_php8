<se><div class="content shopgroups" [part.style][contedit]></se>
<h1 class="groupTitle">{$thisgroup_name}</h1>
<noempty:{$thisgroup_image}>
    <div class="blockGroupImage">
        <img class="groupImage" src="{$thisgroup_image}" alt="{$thisgroup_image_alt}" >
    </div>
</noempty>
<noempty:{$thisgroup_commentary}>
    <div class="groupcomment">{$thisgroup_commentary}</div>
</noempty>
<noempty:{$sgrouplist}>
    <h3 class="subgroupsTitle">[lang003]</h3>
    <div class="groupsublinkblock">
        <repeat:subgroups name=sgroup> 
            <div class="cellGroup">
                <if:[param35]=='Y'>
                    <div class="blockImage">
                        <noempty:[sgroup.image]>
                            <a class="lnkGroupImage" href="[sgroup.link]" title="[sgroup.title]"><img class="subgroupImage" src="[sgroup.image]" alt="[sgroup.image_alt]"></a>
                        </noempty>
                    </div>
                </if>
                <div class="blockTitle">
                    <a class="lnkGroupName" href="[sgroup.link]" title="[sgroup.title]">[sgroup.name]</a>
                    <noempty:[sgroup.scount]>
                        <span class="count">([sgroup.scount])</span>
                    </noempty>
                </div>
            </div>
        </repeat:subgroups> 
    </div>
</noempty>
<noempty:{$related}>
    [subpage name=related]
</noempty>
<se></div></se>
