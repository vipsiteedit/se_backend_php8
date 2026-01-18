<footer:js>
[js:jquery/jquery.min.js]     
<if:([param46]=='Y' || [param33]=='rotate')>
<if:[param46]=='Y'>
[js:ui/jquery.ui.min.js]
</if>
[include_js({id:[part.id], p32:'[param32]',p46:'[param46]'})]
<if:[param33]=='rotate'>
[js:jquery/jquery.carousel.js]
<script>
$('#partRotate[part.id] .rtContainer').Carousel({
    position: "[param34]",
    visible: [param35],
    rotateBy: [param36],
    speed: [param37],
    direction: [param42],
    btnNext: '#partRotate[part.id] #nextRotate',
    btnPrev: '#partRotate[part.id] #prevRotate',      
    auto: [param38],      
    delay: [param39],
    dirAutoSlide:[param40],
    margin: 0      
}); 
</script>
</if></if>   
</footer:js>
<div class="content contSpecialGoods" data-type="[part.type]" data-id="[part.id]" [part.style][contedit]>
    <noempty:part.title> 
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty> 
    <noempty:part.image> 
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty> 
    <noempty:part.text> 
        <div class="contentText" [part.style_text]>[part.text]</div> 
    </noempty> 
<if:{$pricelist}>
    <div class="contentBody bodySpecialGoods[part.id]">
    <if:[param33]=='rotate'>
        <div id="partRotate[part.id]" class="rotateGoods">
        <a href="javascript:void(0);" id="prevRotate">&nbsp;</a>  
        <div class="rtContainer">
            <ul class="rtRotate" style="margin:0;padding:0;list-style:none;">
            <repeat:objects>
                <li style="float:left;">
                    [subpage name=blockproduct]
                </li>
            </repeat:objects> 
            </ul>
        </div>
        <a href="javascript:void(0);" id="nextRotate">&nbsp;</a>
        </div>
    </if>
    <if:[param33]=='block'>
        <div id="partBlock[part.id]" class="blockGoods">
            <repeat:objects>
                [subpage name=blockproduct]
            </repeat:objects>    
        </div>
    </if> 
    </div>
</if>
</div>  
