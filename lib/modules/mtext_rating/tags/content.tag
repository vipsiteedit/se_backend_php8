<div class="content cont_rattxt" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div> 
    </noempty>
    <SE>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="button" value="[lang004]" name="clear">
            </form> 
        </div> 
    </SE> 
    <if:{$accesslevel}==3>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="submit" value="[lang004]" name="clear">
            </form> 
        </div> 
    </if>
    [*addobj]                
    [SE_PARTSELECTOR]
    <wrapper>
        <repeat:records>       
            <div class="object record-item"[objedit]>
                <noempty:record.title>
                    <[record.title_tag] class="objectTitle record-title">
                        <span class="objectTitleTxt">[*edobj][record.title]</span> 
                    </[record.title_tag]> 
                </noempty>
                <noempty:record.image>
                    <img border="0" class="objectImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                </noempty>
                <noempty:record.note>
                    <div class="objectNote record-note">[record.note]</div> 
                </noempty>
                <noempty:record.text>
                    <div id="link">
                        <a href="[record.link_detail]">[param9]</a> 
                    </div> 
                </noempty>
                <div id="objFooter">
                    <SERV>
                        <form style="margin:0px;" action="#[record.link]" method="post">
                    </SERV>
                    <span id="ratingTitle">[lang002]:</span> 
                    <span id="obj_rating">[se."0"][record.rating]</span>
                    <input type="hidden" name="ratingraz" value="[part.id]"> 
                    <input type="hidden" name="ratingobj" value="[record.id]"> 
                    <input type="submit" id="VoteBut" class="buttonSend" name="goRating" value="[lang003]">
                    <SERV>
                        </form>
                    </SERV> 
                </div> 
            </div> 
        </repeat:records>
    </wrapper>

{SHOW}
    <div class="content cont_rattxt view record-item" id="view"[objedit]>
        <noempty:record.title>
            <[part.title_tag] class="objectTitle">
                <span class="objectTitleTxt record-title">[record.title]</span>
            </[part.title_tag]> 
        </noempty>
        <noempty:record.image>
            <div class="objimage record-image" id="objimage">
                <img class="objectImage" alt="[record.image_alt]" title="[record.image_title]" src="[record.image]" border="0">
            </div> 
        </noempty>
        <noempty:record.note>
            <div class="objectNote record-note">[record.note]</div> 
        </noempty>
        <div class="objectText record-text">[record.text]</div> 
            <input type="button" id="butfirst" class="buttonSend" onclick="document.location='[thispage.link]';" value="[lang001]">
        </div> 
{/SHOW}
</div> 
