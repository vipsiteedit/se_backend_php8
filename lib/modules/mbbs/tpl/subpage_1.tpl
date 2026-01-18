<div class="content contEditDesk add">
    <h3 class="contentTitle"><?php echo $section->language->lang021 ?></h3>
    <div class="errortext sysedit"><?php echo $errortext ?></div>
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
        <div class="form">
            <div class="obj name">
                <label class="title" for="name"><span class="star">*</span><span class="text"><?php echo $section->language->lang023 ?></span></label>
                <div class="field">
                    <input id="name" name="name" value="<?php echo $name ?>" maxlength="50">
                </div> 
            </div>
            <div class="obj town">
                <label class="title" for="town"><span class="star">*</span><span class="text"><?php echo $section->language->lang024 ?></span></label>
                <div class="field">
                    <input id="town" name="town" value="<?php echo $town ?>" maxlength="30">
                </div> 
            </div>
            <div class="obj phone">
                <label class="title" for="phone"><span class="text"><?php echo $section->language->lang037 ?></span></label>
                <div class="field">
                    <input id="phone" name="phone" value="<?php echo $phone ?>" maxlength="30">
                </div>
            </div>
            <div class="obj email">
                <label class="title" for="email"><span class="text"><?php echo $section->language->lang025 ?></span></label> 
                <div class="field">
                    <input id="email" name="email" value="<?php echo $email ?>" maxlength="30">
                </div>
            </div>
            <div class="obj url">
                <label class="title" for="url"><span class="text"><?php echo $section->language->lang026 ?></span></label>
                <div class="field">
                    <input id="url" name="url" value="<?php echo $url ?>" maxlength="50">
                </div>
            </div>
            <div class="obj short">
                <label class="title" for="short"><span class="star">*</span><span class="text"><?php echo $section->language->lang027 ?></span></label>
                <div class="field">
                    <input id="short" name="short" value="<?php echo $short ?>" maxlength="50">
                </div>
            </div>
            <div class="obj textmany"> 
                <label class="title" for="text"><span class="star">*</span><span class="text"><?php echo $section->language->lang028 ?></span></label>
                <div class="field">
                    <textarea class="field_text" id="text" name="text" rows="10" cols="40"><?php echo $text ?></textarea>
                </div>
            </div> 
            <div class="obj userfile">
                <label class="title" for="userfile"><span class="text"><?php echo $section->language->lang029 ?></span></label> 
                <div class="field">
                    <input id="userfile" type="file" name="userfile[]">
                </div> 
            </div> 
        </div>
        <div class="groupButton">
            <input class="buttonSend goButton" name="GoTo" type="submit" value="<?php echo $section->language->lang011 ?>">
            <input class="buttonSend backButton" onclick="document.location = '<?php echo $__data->getLinkPageName() ?>'" type="button" value="<?php echo $section->language->lang015 ?>">
        </div>
    </form>
</div>
