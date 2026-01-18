<li class="imgUploadFiles delFile<?php echo $foto->num ?>">
    <div class="uploadFileDel" data-source="<?php echo $foto->num ?>" onmouseover="this.style='cursor:pointer;'"><?php echo $section->language->lang025 ?></div>
    <div class="uploadFilesArea inpArea">
        <input type="text" name="uploadFiles[]" value="<?php echo $foto->text ?>" alt="" required />
    </div>
    <div class="forAlt">
        <span class="titleImgAlt"><?php echo $section->language->lang064 ?></span>
        <input type="hidden" name="uploadFilesAlt[]" value="<?php echo $foto->text ?>" alt="" />
    </div>
</li>
