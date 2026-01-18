<repeat:groups name=group>
    <div class="groupItem">
        <div class="mainGroup">
            <div class="celltlbGroupImg">
                <noempty:[group.image]>
                    <a class="lnkGroupImg" href="[group.link]" title="[group.title]">
                        <img class="imgtlbGroupImg" src="[group.image]" border="0" alt="[group.image_alt]">
                    </a>
                </noempty>
            </div>   
            <div class="celltlbGroupName">
                <a class="lnkGroupTitle" href="[group.link]" title="[group.title]">[group.name]</a>
            </div>
        </div>
        <noempty:[group.sub]>
            <div class="subgroupsList">
                <repeat:subgroups[group.id] name=sgroup>
                    <div class="subItem">
                        <if:[param34]=='Y'>
                            <noempty:[sgroup.image]>
                                <a class="lnkSubGrImage" href="[sgroup.link]" title="[sgroup.title]"><img src="[sgroup.image]" border="0" alt="[sgroup.image_alt]"></a> 
                            </noempty>
                        </if>
                        <a class="lnkSubGrTitle" href="[sgroup.link]" title="[sgroup.title]">[sgroup.name]</a><noempty:[sgroup.vline]><span class="vline">[param7]</span></noempty>
                    </div> 
                </repeat:subgroups[group.id]>
                <noempty:[group.end]>
                    <a class="moreGroups" href="[group.link]" title="[group.title]">[group.end]</a>
                </noempty>
            </div>
        </noempty> 
    </div> 
</repeat:groups>
