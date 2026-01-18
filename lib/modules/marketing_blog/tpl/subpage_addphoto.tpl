<li class="imgUploadFiles delFile<?php echo $foto->num ?>">
    <div class="uploadFileDel" data-source="<?php echo $foto->num ?>" onmouseover="this.style='cursor:pointer;'"><?php echo $section->language->lang025 ?></div>
    <div class="uploadFilesArea">
        <img class="loadImg" src="<?php echo $file ?><?php echo $foto->text ?>" alt="" />
        <input type="hidden" name="uploadFiles[]" value="<?php echo $file ?><?php echo $foto->text ?>" >
    </div>
    <div class="forAlt">
        <span class="titleImgAlt"><?php echo $section->language->lang067 ?></span>
        <input type="text" class="textImgAlt" name="uploadFilesAlt[]" value="<?php echo $foto->alt ?>">
    </div>
</li>
