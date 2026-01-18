<div class="content-pagebrand">
    <noempty:{$brand}>
        <h1 class="brandTitle">{$brand['name']}</h1>  
        <noempty:{$brand['image']}>
            <div class="brandImage">
                <img class="brandImage" src="{$brand['image']}">
            </div>
        </noempty> 
        <noempty:{$brand['text']}>
            <div class="brandDescription">
                {$brand['text']}
            </div>
        </noempty> 
    </noempty>
    <empty:{$brand}>
        <noempty:{$brands}>
            <h1 class="brandsTitle">[lang004]</h1>
            <div class="brandsList">
                <repeat:brands name=brand>
                    <div class="brandItem">
                        <noempty:[brand.image]>
                            <div class="blockImage">
                                <a href="[brand.link]" title="[brand.title]">
                                    <img class="brandImage" src="[brand.image]">  
                                </a>
                            </div>
                        </noempty>
                        <div class="blockTitle">
                            <a class="brandTitle" href="[brand.link]" title="[brand.title]">[brand.name]</a>
                        </div> 
                    </div>
                </repeat:brands>
            </div>
        </noempty>    
    </empty>
</div>
