<noempty:{$pricelist}>
    [subpage name=goodlist]
    <div class="accompGoods">
        <h3 class="accompTitle"><span>[lang030]</span></h3>
        <if:[param317]=='T'>
            [subpage name=table]
        <else>
            <if:[param317]=='V'>
                [subpage name=vitrine]
            <else>
                [subpage name=special]
            </if>
        </if>
    </div>
</noempty>
