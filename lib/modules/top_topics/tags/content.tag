<div class="content top_topics" [part.style][contedit]>
    <noempty:part.title><h3 class="contentTitle" [part.style_title]>
        <span class="contentTitleTxt">[part.title]</span></h3>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <div class="workspace row">
        <repeat:topics name=topic>
            <div class="top">
                <div class="title">
                <if:[param2]!=''>
                    <a href="[param2].html<serv>post/[topic.url]/</serv>">[topic.title]</a>
                <else>
                    [topic.title]
                </if>
                </div>
                <div class="info">
                    <span class="rating">[topic.rating]</span>
                    <span class="txt">балла</span>
                    <span class="delimeter">/</span>
                    <span class="txt1">автор:</span>
                    <span class="author">
                        <if:(([topic.id_user]!=0)&&([param2]!=''))>
                            <a href="[param2].html<serv>user/[topic.id_user]/</serv>">[topic.fio]</a>
                        <else>
                            [topic.fio]
                        </if>
                    </span>
                </div>
            </div>
        </repeat:topics>
    </div>
</div>
