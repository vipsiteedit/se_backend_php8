<noempty:{$pricelist}>
    [subpage name=goodlist]
    <div class="accompGoods <if:'[param316]'=='V'> vitrina</if><if:'[param316]'=='T'> tables</if><if:'[param316]'=='L'> list</if>">
        <h3 class="accompTitle"><span>[lang030]</span></h3>
        <if:[param316]=='T'>
            [subpage name=table]
        </if>
        <if:[param316]=='V'>
            [subpage name=vitrine]
        </if>
        <if:[param316]=='L'>
            [subpage name=list]
        </if>
        <if:[param316]=='Y'>
            [subpage name=special]
        </if>
    </div>
</noempty>
