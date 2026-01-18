<form id="Go" action="" method="post" enctype="multipart/form-data">
        <div class="ml001_err">
            <span class="m001_ertxt text-danger"><?php echo $ml001_errtxt ?>

            </span> 
        </div>
        <div class="form_mail">
            <div class="field_name form-group">
                <label class="field_title"><?php echo $section->language->lang013 ?><span class="imp_point text-danger"> *</span></label> 
                <input class="field_inp form-control" name="name" title="&quot;<?php echo $section->language->lang013 ?>&quot;" value="<?php echo $ml001_name ?>" required>
            </div>
            <?php if(strval($section->parametrs->param19)=='Y'): ?>
            <div class="field_phone form-group">
                <label class="field_title"><?php echo $section->language->lang027 ?><?php if(strval($section->parametrs->param16)=='Y'): ?><span class="imp_point text-danger"> *</span><?php endif; ?></label> 
                <input class="field_inp form-control" name="phone" title="&quot;<?php echo $section->language->lang027 ?>&quot;" value="<?php echo $ml001_phone ?>"<?php if(strval($section->parametrs->param16)=='Y'): ?> required<?php endif; ?>>
            </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param18)=='Y'): ?> 
            <div class="field_email form-group">
                <label class="field_title"><?php echo $section->language->lang014 ?><?php if(strval($section->parametrs->param17)=='Y'): ?><span class="imp_point text-danger"> *</span><?php endif; ?></label> 
                <input class="field_inp form-control" type="email" name="email" title="&quot;<?php echo $section->language->lang014 ?>&quot;" value="<?php echo $ml001_email ?>"<?php if(strval($section->parametrs->param17)=='Y'): ?> required<?php endif; ?>>
            </div> 
            <?php endif; ?>
            <?php if(strval($section->parametrs->param29)=='Y'): ?>
            <div class="field_file form-group">
                <label class="field_title"><?php echo $section->language->lang034 ?></label>
                <input type="hidden" name="isfile" value="1">
                <input type="file" class="field_inp form-control" name="file">
            </div>     
            <?php endif; ?>
            <?php if(strval($section->parametrs->param20)=='Y'): ?>
            <div class="field_note form-group">
                <label class="field_title"><?php echo $section->language->lang015 ?></label> 
                <textarea class="field_area form-control" name="note" rows="5" wrap="virtual"><?php echo $ml001_note ?></textarea> 
            </div> 
            <?php endif; ?>
            <?php if(strval($section->parametrs->param13)!='No'): ?>
                <div class="field_pin">
                    <div class="field_pin">
                        <?php echo $anti_spam ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
            <input name="GoTo"  value="<?php echo $section->language->lang017 ?>" class="buttonSend" type="submit">
        </div>
</form>
