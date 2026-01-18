<footer:js>
    [js:jquery/jquery.min.js]
    <script type="text/javascript" src='[this_url_modul]password_strength_plugin.js'></script>
    <script type="text/javascript">
        $(document).ready( function() {
        $(".password_adv").passStrength({
            shortPass:      "reg_shortPass",
            badPass:        "reg_badPass",
            goodPass:       "reg_goodPass",
            strongPass:     "reg_strongPass",
            baseStyle:      "reg_testresult",
            samePassword:   "reg_samePassword",
            pshortPass:  '<?php echo $section->language->lang028 ?>',
            pbadPass: '<?php echo $section->language->lang029 ?>',
            pgoodPass: '<?php echo $section->language->lang030 ?>',
            pstrongPass: '<?php echo $section->language->lang031 ?>',
            psamePassword: '<?php echo $section->language->lang032 ?>',
            userid:     "#login",
            messageloc:     1
            });
        });           
    </script>
</footer:js>
<div class="content contAuthCheskPass container" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contAuthBlock">
        <form id="Go" action="" method="post" style="margin:0px">
            <div class="errorText">
                
                <?php echo $error_message ?>
                
            </div>
            <div class="obj login">
                <label for="login"><?php echo $section->language->lang012 ?><span>*<span></label>
                <div class="area">
                    <input id="login" name="username" title="<?php echo $section->language->lang012 ?>" value="<?php echo $_username ?>"><em><?php echo $section->language->lang015 ?></em>
                </div>
            </div>
            <div class="obj passw">
                <label for="passw"><?php echo $section->language->lang011 ?><span>*<span></label>
                <div class="area">
                    <input id="passw" class="password_adv" type="password" size="20" name="passw" title="<?php echo $section->language->lang011 ?>">
                        
                    <em><?php echo $section->language->lang014 ?></em>
                </div>
            </div>
 
            <div class="obj confpassw">
                <label for="confpassw"><?php echo $section->language->lang010 ?><span>*<span></label>
                <div class="area">
                    <input type="password" id="confpassw" size="20" name="confpassw" title="<?php echo $section->language->lang010 ?>">
                </div>
            </div>
            <div class="obj email">
                <label for="email"><?php echo $section->language->lang009 ?><span>*<span></label>
                <div class="area">
                    <input id="email" name="email" title="<?php echo $section->language->lang009 ?>" value="<?php echo $_email ?>"><em><?php echo $section->language->lang013 ?></em>
                </div>
            </div>
            <div class="obj firstName">
                <label for="firstName"><?php echo $section->language->lang004 ?><span>*<span></label>
                <div class="area">
                    <input id="firstName" name="first_name" title="<?php echo $section->language->lang004 ?>" value="<?php echo $_first_name ?>">
                </div>
            </div>
            <div class="obj lastName">
                <label for="lastName"><?php echo $section->language->lang003 ?><span>*<span></label>
                <div class="area">
                    <input id="lastName" name="last_name" title="<?php echo $section->language->lang003 ?>" value="<?php echo $_last_name ?>">
                </div>
            </div>
            <div class="obj phone">
                <label for="phone"><?php echo $section->language->lang033 ?><span>*<span></label>
                <div class="area">
                    <input id="phone" name="phone" title="<?php echo $section->language->lang033 ?>" value="<?php echo $phone ?>">
                </div>
            </div>
            <?php if(strval($section->parametrs->param46)=='Y'): ?>
                <div class="license">
                    <input id="license" type="checkbox" name="license" checked>
                    <label for="license"><?php echo $section->language->lang024 ?>&nbsp;<a href="<?php echo $section->parametrs->param33 ?>" target="_blank"><?php echo $section->language->lang025 ?></a></label>
                </div>
            <?php endif; ?>
            <div class="antiSpam">
                <?php echo $anti_spam ?>
            </div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
            <div class="buttonArea">
                <input name="GoToAuthor" type="submit"  value="<?php echo $section->language->lang005 ?>" class="buttonSend">
            </div>
        </form>
    </div>
    
</div>
