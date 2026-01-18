<header:js>
[js:jquery/jquery.min.js]
[js:tiny_mce/tiny_mce.js]
</header:js>
<script type='text/javascript' charset=utf-8">
<!--  
    tinyMCE.init({
        language : "<?php echo $mlang ?>",
        mode : "exact",
        elements : "anons",
        theme : "advanced",
        force_br_newlines : true,
        forced_root_block : '',
        plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,mimage,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlink",
        theme_advanced_buttons1 : "bold,italic,underline,formatselect,link,justifyleft,justifycenter,justifyright,pasteword,pastetext,table,mimage,|,bullist,numlist,|,undo,redo,|,code,fullscreen",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        content_css : "[module_url]tinymce.css"    // Игорю очень нужно чтобы по умолчанию текст был 12 пикселей поэтому создаем свой css
    });
    tinyMCE.init({
        language : "<?php echo $mlang ?>",
        mode : "exact",
        elements : "full",
        theme : "advanced",
        force_br_newlines : true,
        forced_root_block : '',
        plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,mimage,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlink",
        theme_advanced_buttons1 : "bold,italic,underline,formatselect,link,justifyleft,justifycenter,justifyright,pasteword,pastetext,table,mimage,|,bullist,numlist,|,undo,redo,|,code,fullscreen",  
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        content_css : "[module_url]tinymce.css"    // Игорю очень нужно чтобы по умолчанию текст был 12 пикселей поэтому создаем свой css
    });
    -->
</script>
