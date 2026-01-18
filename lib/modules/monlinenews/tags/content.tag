<if:([param38]!='d' && [param38]!='')>
<div class="<if:[param38]=='n'>container<else>container-fluid</if>"></if>
<section class="content contOnNews" [contedit]>
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
    <if:{$editobject}!='N'>
        <a class="addLink" href="[link.subpage=edit]">[lang002]</a>
    </if> 
    <div class="muchpages top"> 
        {$MANYPAGE}
    </div>
    <repeat:newss name=record>
        <div class="object">
            <h4 class="objectTitle">
                <span class="dataType_date">[record.date]</span>
                <a class="textTitle" href="<SE>[link.subpage=show]</SE><SERV>[record.shownews]</SERV>">[record.title]</a>
            </h4>
            <div class="newsContainer">
                <noempty:record.image_prev>
                    <a class="objectImageLink" href="<SE>[link.subpage=show]</SE><SERV>[record.shownews]</SERV>">
                        <img border="0" class="objectImage" src="[record.image_prev]" alt="[record.image_alt]">
                    </a>                                               
                </noempty> 
                <div class="objectNote">[record.text]</div>
                <if:[param32]=='Y'>
                    <a class="newsLink" href="<SE>[link.subpage=show]</SE><SERV>[record.shownews]</SERV>">[param37]</a>
                </if>
            </div> 
            <if:{$editobject}!='N'>
                <div class="objectPanel">
                    <a class="recordEdit" href="[link.subpage=edit]<SERV>id/[record.id]/</SERV>">[lang011]</a>
                    <a class="recordDelete" href="[thispage.link]<SERV>delete/[record.id]/</SERV>">[lang018]</a>         
                </div>
            </if> 
        </div> 
    </repeat:newss>
    <div class="muchpages bottom">
        {$MANYPAGE}
    </div>
</section>
<if:([param38]!='d' && [param38]!='')>
</div></if>
