function init_editor(url, lang) {
    tinyMCE.init({
        language : lang,
        mode : "exact",
        elements : "anons",
        theme : "advanced",
        force_br_newlines : true,
        forced_root_block : '',
        extended_valid_elements : "noindex, iframe, object, style, param, embed",
        plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,media,mimage,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        theme_advanced_buttons1 : "bold,italic,underline,formatselect,cleanup,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,pasteword,pastetext,outdent,indent",
        theme_advanced_buttons2 : "hr,advlink,removeformat,|,sub,sup,charmap,|,table,visualaid,|,media,image,mimage,|,bullist,numlist,|,undo,redo,|,code,fullscreen",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        convert_urls : false,
        content_css : url+"tinymce.css"    // Игорю очень нужно чтобы по умолчанию текст был 12 пикселей поэтому создаем свой css
    });
    tinyMCE.init({
        language : lang,
        mode : "exact",
        elements : "full",
        theme : "advanced",
        force_br_newlines : true,
        forced_root_block : '',
        extended_valid_elements : "noindex, iframe, object, style, param, embed",
        plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,media,mimage,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlink",
        theme_advanced_buttons1 : "bold,italic,underline,formatselect,cleanup,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,pasteword,pastetext,outdent,indent",
        theme_advanced_buttons2 : "hr,advlink,removeformat,|,sub,sup,charmap,|,table,visualaid,|,media,image,mimage,|,bullist,numlist,|,undo,redo,|,code,fullscreen",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        convert_urls : false,
        content_css : url+"tinymce.css"    // Игорю очень нужно чтобы по умолчанию текст был 12 пикселей поэтому создаем свой css
    });
}
