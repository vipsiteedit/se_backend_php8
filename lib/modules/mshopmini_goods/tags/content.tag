<footer:js>
[js:jquery/jquery.min.js]
[include_js({p10: '[param10]'})]
</footer:js>
<div class="content shopmini"[contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div> 
    </noempty>
    <wrapper>[*addobj]
    [SE_PARTSELECTOR]
    <repeat:records>
        <div class="object record-item" [objedit] >[*edobj]
            <noempty:record.title>
                <[record.title_tag] class="objectTitle record-title">[record.title]</[record.title_tag]> 
            </noempty>
            <noempty:record.image>
            <div class="objectImage">
                <img class="objectImg" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]" onclick="document.location.href='[record.link_detail]'">
            </div>
            </noempty>
            <div class="objectCode">
                <span class="objectCodeTitle">[param7]</span>
                <span class="objectCodeVal record-text1">[record.text1]</span>
            </div>
            <noempty:record.note>
                <div class="objectNote">[record.note]</div> 
            </noempty>
            <if:([param9]=="Y")>
                <noempty:record.text>
                    <a class="linkNext" href="[record.link_detail]">[param1]</a> 
                </noempty>
            </if>
            <div class="specprice">
                <span class="specpriceVal record-field" data-price="[record.field]">[record.field]</span>
                <span class="specpriceTitle">[param6]</span>            
            </div>                                                              
            <form style="margin:0px;" method="POST">
            <input type="hidden" name="addcartspecial" value="[record.id]">
                <input type="hidden" name="partid" value="[part.id]">
                <input class="buttonSend send" <serv>type="submit"</serv> <SE>type="button" onclick="document.location.href='[param4].html'"
</SE> value="[param3]">
            </form> 
        </div>                                
    </repeat:records>
    [SE_PARTSELECTOR]
    </wrapper>

{SHOW}
<footer:js>
[js:jquery/jquery.min.js]
[include_js({p10: '[param10]'})]
</footer:js>
    <div class="content shopmini">
        <div id="view">
        <div class="record-item" [objedit]>
            <noempty:record.title>
                <h1 class="objectTitle record-title">[record.title]</h1> 
            </noempty>
            <noempty:record.image>
                <div id="objimage" class="record-image">
                    <img class="objectImage" alt="[record.image_alt]" title="[record.image_title]" src="[record.image]" border="0">
                </div>
            </noempty>
            <div class="objectCode">
                <span class="objectCodeTitle">[param7]</span>
                <span class="objectCodeVal" record-text1>[record.text1]</span>
            </div>
            <div class="specprice">
                <span class="specpriceVal record-field" data-price="[record.field]">[record.field]</span>
                <span class="specpriceTitle">[param6]</span>
            </div> 
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div> 
            </noempty>
            <div class="objectText record-text">[record.text]</div> 
            <div class="objectSubm">
                <form style="margin:0px;" method="POST">
                    <input type="hidden" name="addcartspecial" value="[record.id]">
                    <input type="hidden" name="partid" value="[part.id]">  
                    <input class="buttonSend send" <serv>type="submit"</serv> <SE>type="button" onclick="document.location.href='[param4].html'"
</SE> value="[param3]">
                </form>  
                <input class="buttonSend back" onclick="document.location.href='<serv>{$_SESSION['SHOP_MINI_PAGE']['page']}</serv><se>[thispage.link]</se>'" type="button" value="[param2]"> 
            </div>
        </div> 
    </div>
    </div>
   
{/SHOW}
</div>      
