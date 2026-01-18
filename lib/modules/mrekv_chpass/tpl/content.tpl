<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<header:js><script type="text/javascript" src='[this_url_modul]password_strength_plugin.js'></script>
<script>
    $(document).ready( function() {
    //ADVANCED
    $(".password_adv").passStrength({
        shortPass:      "top_shortPass",
        badPass:        "top_badPass",
        goodPass:       "top_goodPass",
        strongPass:     "top_strongPass",
        baseStyle:      "top_testresult",
        pshortPass:  '<?php echo $section->language->lang011 ?>',
        pbadPass: '<?php echo $section->language->lang012 ?>',
        pgoodPass: '<?php echo $section->language->lang013 ?>',
        pstrongPass: '<?php echo $section->language->lang014 ?>',
        psamePassword: '<?php echo $section->language->lang001 ?>',
        userid:     "#user_id_adv",
        messageloc:     1
        });
    });
</script></header:js>
<div class="content contChekPassJsChek" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="errorMessage sysedit"><?php echo $error_message ?></div>
    <form style="margin:0px;" name="chpass_form" action="" method="post">
        @if("<?php echo $section->parametrs->param9 ?>"!="N"){<div class="obj oldPass"><label><?php echo $section->language->lang003 ?></label><div class="area"><input type="password" name="oldpass" value=""></div></div>}
        <span id="user_id_adv" style="display:none !important">$login</span>
        <div class="obj newPass">
            <label><?php echo $section->language->lang004 ?></label>
            <div class="area">
        
                <input type="password" class="password_adv" name="newpass" value="">
                 
            </div>
        </div>
  
        <div class="obj confirmPass">
            <label><?php echo $section->language->lang005 ?></label>
            <div class="area">
                <input type="password"  name="confirmpass" value="">
            </div>
        </div>
        <div class="buttonArea">
            <input type="submit"  class="buttonSend" name="gochpass" value="<?php echo $section->language->lang006 ?>">
        </div>
    </form>
</div>
