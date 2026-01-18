<if:[param2]!='d'><div class="<if:[param2]=='n'>container<else>container-fluid</if>"></if>
<article class="content text-public"[contedit]>
    <noempty:part.title>
        <h3 class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </h3>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div>
    </noempty>
    <div class="muchpages top"> 
        {$MANYPAGE}
    </div>
    <repeat:newss name=record>
        <section class="object">
            <h4 class="objectTitle">
                <a class="textTitle" href="[record.link]">[record.title]</a>
            </h4>
            <div class="newsContainer">
                <noempty:record.image_prev>
                    <a class="objectImageLink" href="[record.link]">
                        <img border="0" class="objectImage" src="[record.image_prev]" alt="[record.image_alt]">
                    </a>                                               
                </noempty> 
                <div class="objectNote">[record.note]</div>
                <if:[param32]=='Y'>
                    <a class="newsLink" href="[record.link]">[param37]</a>
                </if>
            </div> 
        </section> 
    </repeat:newss>
    <div class="muchpages bottom">
        {$MANYPAGE}
    </div>
</article>
<if:[param2]!='d'></div></if>
