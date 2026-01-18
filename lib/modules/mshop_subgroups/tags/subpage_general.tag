<se><div class="content shopgroups" [part.style][contedit]></se>
<if:[param37] == 'Y'>
<div class="general-group-container">
<div>
<noempty:{$thisgroup_image}>
    <div class="blockGroupImage">
        <img class="groupImage" src="{$thisgroup_image}" alt="{$thisgroup_image_alt}" >
    </div>
</noempty>
<empty:{$thisgroup_image}>
    <div class="blockGroupImage">
        <img class="groupImage lol" src="[module_url]net_izobr.jpg" border="0" alt="[sgroup.image_alt]">
    </div>
</empty>
    <div class="general-group-text">
         <h1 class="groupTitle">{$thisgroup_name}</h1>
         <div class="description">{$thisgroup_commentary}</div>
    </div>
</div>
</div>
</if>
<noempty:{$thisgroup_commentary}>
    <div class="groupcomment">{$thisgroup_commentary}</div>
</noempty>
<noempty:{$sgrouplist}>
    <div class="groupsublinkblock">
        <repeat:subgroups name=sgroup>
        <div class="groupItem">
            <div class="mainGroup">
              <if:[param35]=='Y'>
              <div class="celltlbGroupImg">
                <if:[sgroup.image] != ''>
                    <a class="lnkGroupImg" href="[sgroup.link]" title="[sgroup.title]">
                        <img class="imgtlbGroupImg" src="[sgroup.image]" border="0" alt="[sgroup.image_alt]">
                    </a>
                    <else>
                    <a class="lnkGroupImg" href="[sgroup.link]" title="[sgroup.title]">
                        <img class="imgtlbGroupImg lol" src="[module_url]net_izobr.jpg" border="0" alt="[sgroup.image_alt]">
                    </a>
                </if>
              </div>
              </if>
              <div class="celltlbGroupName">
                <a class="lnkGroupTitle" href="[sgroup.link]" title="[sgroup.title]">[sgroup.name]</a>
              </div>
            </div>
        </div>
        </repeat:subgroups>
    </div>
</noempty>
<se></div></se>
