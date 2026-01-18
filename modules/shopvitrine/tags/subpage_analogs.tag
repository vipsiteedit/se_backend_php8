<noempty:{$pricelist}>
    [subpage name=goodlist]
    <div class="analogGoods <if:'[param317]'=='V'> vitrina</if><if:'[param317]'=='T'> tables</if><if:'[param317]'=='L'> list</if>">
        <h3 class="analogTitle"><span>[lang029]</span></h3>
        <if:[param317]=='T'>
            [subpage name=table]
        </if>
        <if:[param317]=='V'>
            [subpage name=vitrine]
        </if>
        <if:[param317]=='L'>
            [subpage name=list]
        </if>
        <if:[param317]=='Y'>
            [subpage name=special]
        </if>
    </div>  
</noempty>
