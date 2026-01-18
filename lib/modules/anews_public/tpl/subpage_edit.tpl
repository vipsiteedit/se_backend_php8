<?php if(file_exists($__MDL_ROOT."/php/subpage_scripts.php")) include $__MDL_ROOT."/php/subpage_scripts.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_scripts.tpl")) include $__data->include_tpl($section, "subpage_scripts"); ?>
<?php if(strval($section->parametrs->param2)!='d'): ?><div class="<?php if(strval($section->parametrs->param2)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content news-public add">
    <b class="errorText"><?php echo $errortext ?></b> 
    <form class="form-horizontal" method="post" action="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>" enctype="multipart/form-data" style="margin:0px;">
        <div class="form-group">
             <label class="control-label col-xs-2" for="publics"><?php echo $section->language->lang020 ?>*</label>
             <div class="col-xs-2">
                <input class="form-control" type="date" name="date" value="<?php echo $date ?>">
             </div>
             <div class="col-xs-8">
                <div class="checkbox">
    <label>
        <input type="checkbox" name="publics" <?php echo $checked ?>> <?php echo $section->language->lang021 ?>
                </label>
    </div>
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="parttitle"><?php echo $section->language->lang014 ?>*</label>
             <div class="col-xs-10">
                <input class="form-control" type="text" name="title" value="<?php echo $_title ?>">
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="userfile"><?php echo $section->language->lang015 ?></label>
             <div class="col-xs-10">
                 <input class="form-control" type="file" name="userfile">
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="text"><?php echo $section->language->lang016 ?>*</label>
             <div class="col-xs-10">
                <textarea style="width:100%;" rows="10" cols="40" id="edittar" name="text" class="textarea"><?php echo $_text ?></textarea>
             </div>
        </div>
        <div class="form-group">
            <div class="col-xs-2"></div>
            <div class="col-xs-4">
            <input class="btn btn-success edSave" type="submit" name="Save" value="<?php echo $section->language->lang017 ?>">
            <input class="btn btn-default edBack" type="button" value="<?php echo $section->language->lang019 ?>" onclick="document.location='<?php echo seMultiDir()."/".$_page."/" ?>';">
            </div>
        </div>
    </form> 
</section>
<?php if(strval($section->parametrs->param2)!='d'): ?></div><?php endif; ?>
