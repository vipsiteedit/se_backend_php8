<noempty:{$brands}>
    <h3 class="brandsTitle">[lang004]</h3>
    <div class="brandsList">
        <repeat:brands name=brand>
            <div class="brandItem<if:({$brand_selected}==[brand.code])> selected</if>">
                <noempty:[brand.image]>
                    <div class="blockImage">
                        <a href="[brand.link]" title="[brand.title]">
                            <img class="brandImage" src="[brand.image]">  
                        </a>
                    </div>
                </noempty>
                <div class="blockTitle">
                    <a class="brandTitle" href="[brand.link]" title="[brand.title]">[brand.name]</a>
                    <span class="count">([brand.cnt])</span> 
                </div> 
            </div>
        </repeat:brands>
    </div>
    <noempty:{$brand_text}>
        <div class="brandText">
            {$brand_text}        
        </div>
    </noempty>
</noempty>
