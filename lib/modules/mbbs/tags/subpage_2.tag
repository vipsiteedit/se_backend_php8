<div class="content contEditDesk edit">
    <h3 class="contentTitle">[lang022]</h3>
    <div class="errortext sysedit">[se."Ошибка"][$errortext]</div>
    <form style="margin:0px;" action="[se."[thispage.link]"]" method="post" enctype="multipart/form-data"> 
        <div class="form">
            <div class="obj name">
                <label class="title" for="name"><span class="star">*</span><span class="text">[lang023]</span></label>
                <div class="field">
                    <input id="name" name="name" value="[$name]" maxlength="50">
                </div> 
            </div>
            <div class="obj town">
                <label class="title" for="town"><span class="star">*</span><span class="text">[lang024]</span></label>
                <div class="field">
                    <input id="town" name="town" value="[$town]" maxlength="30">
                </div> 
            </div>
            <div class="obj phone">
                <label class="title" for="phone"><span class="text">[lang037]</span></label>
                <div class="field">
                    <input id="phone" name="phone" value="[$phone]" maxlength="30">
                </div>
            </div>
            <div class="obj email">
                <label class="title" for="email"><span class="text">[lang025]</span></label> 
                <div class="field">
                    <input id="email" name="email" value="[$email]" maxlength="30">
                </div>
            </div>
            <div class="obj url">
                <label class="title" for="url"><span class="text">[lang026]</span></label>
                <div class="field">
                    <input id="url" name="url" value="[$url]" maxlength="50">
                </div>
            </div>
            <div class="obj short">
                <label class="title" for="short"><span class="star">*</span><span class="text">[lang027]</span></label>
                <div class="field">
                    <input id="short" name="short" value="[$short]" maxlength="50">
                </div>
            </div>
            <div class="obj textmany"> 
                <label class="title" for="text"><span class="star">*</span><span class="text">[lang028]</span></label>
                <div class="field">
                    <textarea id="text" name="text" rows="10" cols="40">[$text]</textarea>
                </div>
            </div> 
            <div class="obj userfile">
                <label class="title" for="userfile"><span class="text">[lang029]</span></label> 
                <div class="field">
                    <input id="userfile" type="file" name="userfile[]">
                </div>
            </div> 
            <div class="obj del">
                <label class="title" for="del"><span class="text">[lang030]</span></label>
                <div class="field">
                    <input type="checkbox" name="del" value="true" id="del">
                </div>
            </div>
        </div>
        <div class="groupButton">
            <input class="buttonSend goButton" name="GoTo" type="submit" value="[lang031]">
            <input class="buttonSend backButton" onclick="document.location = '[thispage.link]'" type="button" value="[lang015]">
        </div>
        <input type="hidden" name="id" value="[$id]">
        <input type="hidden" name="img" value="[$_img]">
    </form>
</div>
