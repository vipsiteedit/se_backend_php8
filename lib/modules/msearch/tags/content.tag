<div class="content contSearch" [part.style][contedit]>
    <div class="contentTitle" [part.style_title]>
        <span class="contentTitleTxt">[part.title]</span>
        <b class="searchString">"[se."[lang001]"][$SEARCH_TITLE]"</b>
    </div>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="searchWarn">[se."[lang002]"][$SEARCH_WARN]</div>
    <div class="blockResult">
        <div class="countRec">
            <if:{$SEARCH_COUNTS}!=0>
                [lang012]<b class="countRecNum"><se>2</se>[$SEARCH_COUNTS]</b>
            </if>
        </div>
        <div class="blockObjResult">
            [$SEARCH_CONTENT]
            <se>
                <div class="recResult">
                    <b class="recNumber">1</b>
                    <a class="recTitle" href="#">[lang003]</a>
                    <div class="recSearchText">[lang004] [lang005] [lang006] [lang007]</div>
                    <a class="recLink" href="#">http://mysite.ru/home/</a>
                    <b class="recSize">(10 Кб)</b>
                    <b class="recDate">[$sysdate]</b>
                    <!--a class="recLightSearch" href="#">[lang008]</a-->
                </div>
                <div class="recResult">
                    <b class="recNumber">2</b>
                    <a class="recTitle" href="#">[lang003]</a>
                    <div class="recSearchText">[lang004] [lang005] [lang006] [lang007]</div>
                    <a class="recLink" href="#">http://mysite.ru/about/</a>
                    <b class="recSize">(8 Кб)</b>
                    <b class="recDate">[$sysdate]</b>
                    <!--a class="recLightSearch" href="#">[lang008]</a-->
                </div>
            </se>
        </div>
        <div class="steplist">
            [$steplist]
            <se>
                <span>|</span>
                <b class="currentpart"> 1 </b>
                <span>|</span>
                <a class="otherpart" href="#"> 2 </a>
                <span>|</span>
                <a class="otherpart" href="#"> 3 </a>
                <span>|</span>
            </se>
        </div>
    </div>
    <se>
        <br class="sysedit">
        <div style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; " class="sysedit">Д[lang009]<br>[lang010] [param1]</div>
    </se>
</div>
