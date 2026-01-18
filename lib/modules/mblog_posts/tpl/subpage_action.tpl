<div class="blogposts">
    <div class="contModKomFCtlg">   
        <form style="margin:0px;" action="" method="post" enctype="multipart/form-data"> 
            <div class="mod_kom_txt">
                <label class="title" for="text"><?php echo $section->language->lang026 ?></label> 
                <div class="field">
                    <textarea class="inputs"  id="text" name="text" rows="10" cols="40"><?php echo $text ?></textarea>
                </div>
            </div>
            <div class="mod_kom_btn">
                <input class="buttonSend goButton savebutton" name="GoTo" type="submit" value="<?php echo $section->language->lang028 ?>">
                <input class="buttonSend goButton deletbutton" name="delkom" type="submit" value="<?php echo $section->language->lang027 ?>">
                <input class="buttonSend backButton" onclick="window.history.back()" type="button" value="<?php echo $section->language->lang029 ?>">
            </div>
        </form> 
    </div>
</div>
