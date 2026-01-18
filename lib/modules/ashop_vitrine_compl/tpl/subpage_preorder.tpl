<div id="callback-modal-form" class="blockPreorder callback-form" style="display:none;">
    <form name="cback-form" method="post" id="" action="">
        <input type="hidden" name="product" value="">
        <input type="hidden" name="count" value="">
        <div class="title-cback"><?php echo $section->language->lang113 ?></div>
        <div class="msgError" style="display:none;"></div>
        <div class="block-field-cback">
            <div class="line-field-cback line-cback-name">
                <div class="block-label">
                    <label for="cb_name"><?php echo $section->language->lang114 ?></label><span class="required">*</span>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_name" name="cb_name">
                    <div class="cback_error" style="display:none;"><?php echo $section->language->lang115 ?></div>
                </div>
            </div>
            <div class="line-field-cback line-cback-email">
                <div class="block-label">
                    <label for="cb_email"><?php echo $section->language->lang116 ?></label><span class="required">*</span>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_email" name="cb_email">
                    <div class="cback_error" style="display:none;"><?php echo $section->language->lang117 ?></div>
                </div>
            </div>
            <div class="line-field-cback line-cback-phone">
                <div class="block-label">
                    <label for="cb_phone"><?php echo $section->language->lang118 ?></label>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_phone" name="cb_phone">
                    <div class="cback_error" style="display:none;"></div>
                </div>
            </div>
        </div>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
        <div class="block-button-cback">
            <button type="submit" class="buttonSend btnConfirm btn btn-default"><?php echo $section->language->lang078 ?></button>
        </div>
    </form>
    <div class="msgAccepted" style="display:none;">
        <p><?php echo $section->language->lang119 ?></p>
  <p><?php echo $section->language->lang120 ?></p>
    </div>
</div>
