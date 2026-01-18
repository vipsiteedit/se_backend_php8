<if:[param8]!='d'><div class="cont-text-container <if:[param8]=='n'>container<else>container-fluid</if>"></if>
    <article class="content cont-text part[part.id]"[contedit]>
        <noempty:part.title>
            <header>
                <[part.title_tag] class="contentTitle content-title">
                    <span  class="contentTitleTxt" data-content="title">[part.title]</span> 
                </[part.title_tag]>
            </header> 
        </noempty>
        <noempty:part.image>
            <div class="content-image" data-content="image" >
                <img class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
            </div>
        </noempty>
        <noempty:part.text>
            <div class="contentText content-text" data-content="text">[part.text]</div> 
        </noempty>
        <div class="contentBody">   
            [*addobj]
            <nav class="class-navigator top">
                [SE_PARTSELECTOR]
            </nav>
            <div class="records-container">
                <repeat:records>
                    <section class="object record-item obj[record.id]" [objedit]>[*edobj]
                        <noempty:record.title>
                        <header>
                            <[record.title_tag] class="object-title objectTitle">
                                <span class="objectTitleTxt" data-record="title">[record.title]</span> 
                            </[record.title_tag]>
                        </header> 
                        </noempty>
                        <noempty:record.image>
                            <div class="objectImage object-image" data-record="image">
                                <img class="objectImg object-img" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
                            </div>
                        </noempty>
                        <noempty:record.note>
                            <div class="objectNote object-note" data-record="note">[record.note]</div> 
                        </noempty>
                        <noempty:record.text>
                            <a class="linkNext link-next" href="[record.link_detail]#show[part.id]_[record.id]">[param1]</a> 
                        </noempty>
                    </section> 
                </repeat:records>
            </div>   
            <nav class="class-navigator bottom">
                [SE_PARTSELECTOR]
            </nav>
        

{SHOW}
        <footer:js>
        <if:[param5]=="Y">
        [js:jquery/jquery.min.js]
        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
        [include_js({p6: '[param6]'})]
        </if>
        </footer:js>
        <if:[param8]!='d'><div class="cont-text-container <if:[param8]=='n'>container<else>container-fluid</if>"></if>
            <article class="content cont-text part[part.id] show">
                <if:[param4]=='Y'>
                    <a name="show[part.id]_[record.id]"></a>
                </if>                                                                        
                <div class="record-item" [objedit]>
                    <noempty:record.title>
                        <[part.title_tag] class="objectTitle object-title">
                            <span class="contentTitleTxt" data-record="title">[record.title]</span> 
                        </[part.title_tag]> 
                    </noempty>
                    <noempty:record.image>
                        <div class="objimage record-image">
                            <img class="objectImage" alt="[record.image_alt]" title="[record.image_title]" src="[record.image]" border="0">
                        </div> 
                    </noempty>
                    <if:[param3]=='Y'>
                        <noempty:record.note>
                            <div class="objectNote record-note">[record.note]</div> 
                        </noempty>
                    </if>
                    <noempty:record.text>
                        <div class="objectText record-text">[record.text]</div>
                    </noempty> 
                    <if:[param5]=="Y">
                        <div id="ya_share1" style="margin: 10px 0;">
                            <SE>
                                <img src="[this_url_modul]kont.png">
                            </SE>
                        </div>
                    </if>
                    <input class="buttonSend button-send btn btn-success" onclick="document.location.href='[thispage.link]';" type="button" value="[param2]">
                </div>
            </article>                           
        <if:[param8]!='d'></div></if>
        
{/SHOW}
        </div>
    </article>
<if:[param8]!='d'></div></if>
