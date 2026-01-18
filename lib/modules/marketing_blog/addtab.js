$(function () {

    //####### Tabs
        $('#tabs2').tabs().find(".ui-tabs-nav").sortable();
  


    // Dynamic tabs
    var tabTitle = $( "#tab_title" ),
        tabContent = $( "#tab_content" ),
        tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-edit'>Редактировать название вкладки</span><span class='ui-icon ui-icon-close'>Удалить вкладку</span><input type='hidden' value='#{label}' name='tabName[#{number}]'></li>",
        tabCounter = 100;

        
    // modal dialog init: custom buttons and a "close" callback reseting the form inside
    var dialog = $( "#dialog2" ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            'Добавить': function() {
                addTab();
                $( this ).dialog( "close" );
            },
            'Отмена': function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            form[ 0 ].reset();
        }
    });

    // addTab form: calls addTab function on submit and closes the dialog
    var form = dialog.find( "form" ).submit(function( event, ui ) {
        addTab();
        dialog.dialog( "close" );
        event.preventDefault();
    });

    // actual addTab function: adds new tab using the input from the form above
    function addTab() {
        var tabs = $( "#tabs2" ).tabs();
        var label = tabTitle.val() || "Tab " + tabCounter,
            id = "tabs-" + tabCounter,
            li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ).replace( /#\{number\}/g, tabCounter ) );
           // tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";

        tabs.find( ".ui-tabs-nav" ).append( li );
        tabs.append( "<div id='" + id + "' class='tcontents'><textarea id='tabText" + tabCounter + "'  name='tabText["+tabCounter+"]'></textarea></div>" );
        tabs.tabs( "refresh" );
		
        var check = $( "#tabs2" ).find('.checkType').val();
        if (check==1) {
            tinymce.init({
                selector: "textarea#tabText"+tabCounter,
                document_base_url: "/",
                //content_css: "/system/main/editor/tiny.css",
                safari_warning : false,
                remove_script_host : false,
                convert_urls : false,
                theme : "modern",
                forced_root_block : false,
                menubar : false,
                browser_spellcheck : true,
                language: "ru",
                convert_fonts_to_spans : true,
                toolbar: "undo redo pastetext | bold italic underline | alignleft aligncenter alignright alignjustify | "+
                 " bullist numlist outdent indent | image link unlink media | table blockquote | removeformat fullscreen code",
                plugins: "link image paste code fullscreen media table",
                link_list: "[thispage.link]?getpagelist",
                image_advtab: true,
                external_plugins: { "filemanager" : "/admin/filemanager/plugin.js" },
                external_filemanager_path: "[thispage.link]"
            });        
        }

        var num = $( "#tabs2" ).find('ul li').length;
        tabs.tabs("option", "active", num-1);
        tabCounter++;
    }

    // addTab button: just opens the dialog
    $( "#add_tab" )
        .button()
        .click(function() {
            dialog.dialog( "open" );
        });

    // close icon: removing the tab on click
    $( "#tabs2" ).on( "click",'span.ui-icon-close', function() {

        var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
    });

//  edit
    // edit tab title
    $( "#tabs2" ).on( "click",'span.ui-icon-edit', function() {
        //dialog1.dialog( "open" );
        var title = prompt('Новое название вкладки');
        if (title != '') {
            var target = $(this).parent();
            target.find('a').text(title);
            target.find('input:hidden').val(title);
        }
    });

})
