<repeat:regions name=region>
    <div class="regionItem" data-id=[region.id]>
        <div class="city">[region.city]</div>
        <div class="detail">
            <noempty:[region.code]><i class="flag flag-[region.code]"></i></noempty>
            [region.country],
            <noempty:[region.region]>[region.region],</noempty>
            [region.city]
        </div>
    </div>    
</repeat:regions>
