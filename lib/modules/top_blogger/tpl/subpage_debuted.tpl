<header:css>
    [lnk:bootstrap/css/bootstrap.min.css]
</header:css>
<header:js>
    [js:jquery/jquery.min.js]
    [js:bootstrap/bootstrap.min.js]
    <script type="text/javascript" src="[module_url]engine.js"></script>    
</header:js>
<div class="content topblogger debuted">
    <span class="subtitle"><?php echo $section->language->lang001 ?></span>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_lists.php")) include $__MDL_ROOT."/php/subpage_lists.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_lists.tpl")) include $__data->include_tpl($section, "subpage_lists"); ?>
    <?php echo $nav ?>
</div>
