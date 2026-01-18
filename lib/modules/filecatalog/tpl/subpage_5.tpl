<div class="contModKomFCtlg">
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data"> 
        <div class="mod_kom_txt">
            <label class="title" for="text"><?php echo $section->parametrs->param57 ?></label> 
            <div class="field">
                <textarea class="inputs"  id="text" name="text" rows="10" cols="40"><?php echo $text ?></textarea>
            </div>
        </div>
        <div class="mod_kom_del">
            <div class="field">
                <input type="checkbox" name="delkom" value="true" id="delkom">
            </div>
            <label class="title"><?php echo $section->parametrs->param58 ?></label>
        </div>
        <div class="mod_kom_btn">
            <input class="buttonSend goButton savebutton" name="GoTo" type="submit" value="<?php echo $section->parametrs->param55 ?>">
            <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->parametrs->param56 ?>">
        </div>
    </form> 
</div>
