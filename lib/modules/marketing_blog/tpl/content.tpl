<?php if(seUserGroup()==3): ?>
    <header:css>
        [lnk:bootstrap3.1.1/css/bootstrap.min.css]
    </header:css>
<?php endif; ?>
<header:js>
    [js:jquery/jquery.min.js]
    [js:bootstrap/bootstrap.min.js]
</header:js>
<div class="content blogposts" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($section->parametrs->param1=='blog'): ?>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_blog.php")) include $__MDL_ROOT."/php/subpage_blog.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_blog.tpl")) include $__data->include_tpl($section, "subpage_blog"); ?> 
    <?php endif; ?>
    <?php if($section->parametrs->param1=='info'): ?>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_info.php")) include $__MDL_ROOT."/php/subpage_info.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_info.tpl")) include $__data->include_tpl($section, "subpage_info"); ?>
    <?php endif; ?>
    <?php if($section->parametrs->param1=='goods'): ?>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_goods.php")) include $__MDL_ROOT."/php/subpage_goods.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_goods.tpl")) include $__data->include_tpl($section, "subpage_goods"); ?>
    <?php endif; ?>   
    <div id="show_sets"></div>         
                 
    <div id="message" style="display:none;">
        <a href="#top" id="top-link"><?php echo $section->language->lang063 ?></a>
    </div>    
</div>
<script type="text/javascript">
    var partid = "<?php echo $section->id ?>";
    var thispage = "<?php echo seMultiDir()."/".$_page."/" ?>";            
</script>
<script type="text/javascript" src="[module_url]engine.js"></script>
