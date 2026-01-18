var ed_button = parent.ed_button;
var form_mess = parent.form_mess;
var lang = parent.editor_language;

//$import('/system/main/editor/js/i18n/'+lang+'.js', true);
$import('/system/main/editor/js/page_editor_menu.js', true); // Редактор меню
$import('/system/main/editor/js/page_editor_content.js', true); // Редактор страницы
$import('/system/main/editor/js/page_editor_contacts.js', true); // Редактор контактов
$import('/system/main/editor/js/page_text.js', true); // Редактор переменных
$import('/system/main/editor/js/part_editor.js', true); // Редактор переменных
$import('/system/main/editor/js/record_editor.js', true); // Редактор переменных
$import('/system/main/editor/js/jquery.imgareaselect.pack.js', true); // Редактор контактов$import('/system/main/editor/js/html5_images.js', true); // Редактор контактов


function startEvents(){
	parent.startEvents();
}

// динамическая загрузка javascript библиотек
// src - адрес для загрузки
// cache - 
function $import(src, cache){
	var ms = new Date().getTime().toString(),
    scriptElem = document.createElement('script');
    if (cache != true) src = src + '?'+ms;
	scriptElem.setAttribute('src',src);
    scriptElem.setAttribute('type','text/javascript');
    document.getElementsByTagName('head')[0].appendChild(scriptElem);
}

// класс фрейма, который используется в редакторе
var frame_name = "#seeditor";

// показ и скрытие окна с редактором
// s - значения "show" (показывать) и "hide" (скрывать) окно с редактором
function frameWindow (s){
	if (s == "hide"){
		$(frame_name, parent.document).css("display","none");
		//$("body,html",parent.document).css("overflow","auto");
		$("body").empty();
		parent.console.log("windows close");
	};
	
	if (s == "show"){
		$(frame_name, parent.document).css("display","block");
		//$("body,html",parent.document).css("overflow","hidden");
		$("body").html("<div id=\"se_editor_box\"></div>");
		parent.console.log("windows open");
	}
}

function editor_tinymce(dialog_height, elements){

	if (elements == undefined){
	tinyMCE.init({
        language : lang,
		height: dialog_height,
		mode : "specific_textareas",
		document_base_url: "/",
		content_css: "/system/main/editor/tiny.css",
        safari_warning : false,
		remove_script_host : false,
		convert_urls : false,
            theme : "advanced",
            forced_root_block : false,
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,mimage,"
					+"iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,"
					+"contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,"
					+"xhtmlxtras,template,advlink",
		theme_advanced_buttons1 : "bold,italic,underline,formatselect,link,justifyleft,justifycenter,justifyright,pasteword,pastetext,table,mimage,|,bullist,numlist,|,undo,redo,|,code,fullscreen",		
		theme_advanced_buttons2 : "",
		fullscreen_settings : {
			theme_advanced_path_location : "top"
		}
    });
	} else {
	tinyMCE.init({
        language : lang,
		height: dialog_height,
		mode : "exact",
		elements : ""+elements+"",
		document_base_url: "/",
		content_css: "/system/main/editor/tiny.css",
        safari_warning : false,
		remove_script_host : false,
		convert_urls : false,
            theme : "advanced",
            forced_root_block : false,
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,mimage,"
					+"iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,"
					+"contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,"
					+"xhtmlxtras,template,advlink",
		theme_advanced_buttons1 : "bold,italic,underline,formatselect,link,justifyleft,justifycenter,justifyright,pasteword,pastetext,table,images,tcut,|,bullist,numlist,|,undo,redo,|,code,fullscreen",		
		theme_advanced_buttons2 : "",
		fullscreen_settings : {
			theme_advanced_path_location : "top"
		}
    });
	}
}

function messageYesNo(id, title, message, arr){
	$('#'+id).html('<div id="'+id+'" title="'+title+'">'
	+'<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>'
	+'</div>');
		$( "#"+id ).dialog( "destroy" );
		$( "#"+id ).dialog(arr);
}

function executeWindow(name, value)
{
  $.ajax({
	url: parent.document.location.pathname+"?on_ajax_execute",
        type: 'POST',
        data: {name: ""+name+"", value: ""+value+""},
        success: function(data){
			if (name == 'EditorDeletePage'){
				parent.document.location.href='/';
			}
            $('#se_editor_box').html(data);
			var expr = new RegExp('editpage_([\w]+)');

			if (name == 'editrecord' || name == 'addrecord'){
				runRecordEditir();
			}
			if (name == 'editsection' || name=='addsection'){
				if (runPartEditor(name) == false) return;
			}
			if (name == 'editpage_repos'){
				set_menu_editor();
			}
			if (name == 'siteedit_vars'){
				runPageText();
				//set_page_text(value);
			}
			var res = name.match(/(editpage_)([\w]+)/);
            if (res != null && (res[2] == 'cancel' || res[2] == 'delete')) parent.document.location.href='/';
			if (res != null &&  res[1] == 'editpage_' && res[2] !='save' && res[2] != 'delete' && res[2] != 'cancel'){
				if (res[2] == 'addcontent' || res[2] == 'content'){
					runEditorContent();
				} else
				//res[2] = 'content';
				if(res[2] == 'menu') {
				    if (value == '') value = 0;
					runMenuEditor(value);
				} else
				if(res[2] == 'contacts') {
					runEditorContacts()
				}	
				else
					$import('/system/main/editor/js/page_editor_'+res[2]+'.js', false);
			}
	}
  });
  
  //WScript.sleep(3000);
}



function set_menu_editor(){
	$import('/lib/js/jquery/jquery-ui.custom.js', false);
	$import('/lib/js/jquery/jquery.cookie.js', false);
	$import('/lib/js/jquery/jquery.dynatree.min.js', false);
	//$import('/lib/js/jquery/jquery.dynatree.js', false);

 $(function(){
		$('ul.sortPages,ul.menuWrapper').sortable({
			connectWith: "ul.menuWrapper,ul.sortPages",
			stop :function(event, ui)
				{
				},
			start: function(event, ui){
			}
		}).disableSelection();
 
	$('#editorform').submit(function(){ 
		var elems = $(".menuWrapper");
		var data = '';
		for (var i = 0; i < elems.length; i++){
			var id = elems[i].id;
			var d = $('#'+id).sortable('serialize');
			if(d != ''){
				if (data != '') data = data + '&';
				data = data + d.split('group[]').join(id+'[]');
			}
		}
		if (data != ''){
			var res  = $.post('?on_ajax_execute&sortablepages', { data:data },function(res){
			});
		}
		return true;
	});   
 });
}

function set_page_text(id_box){
	$import('/system/main/editor/js/page_text.js', false);
}

function DialogMessage(button, name, value){
		$( "#dialog:ui-dialog").dialog("destroy");
		$( "#dialog-confirm").dialog({
			resizable: false,
			height:200,
			modal: true,
			buttons: [{
				text: ed_button[button],
				click: function() {
					executeWindow(name, value);
					if (name == 'editpage_save') {
						$( this ).dialog( "close" );
					}
				}}, {
				text: ed_button['cancel'],
				click:  function() {
					$( this ).dialog( "close" );
				}
			}],
			close: function () {
				frameWindow("hide");
			}
		});
}

// Показывает окна редактора
function getEditWindow(name, value)
{
	frameWindow("show");
	
	// Диалог удаления страницы
	if (name == 'editpage_delete'){
		$('#se_editor_box').html('<div id="dialog-confirm" title="'+form_mess['delete_page']+'">'
		+'<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+form_mess['delete_page_text']+' "'+value+'"?</p>'
		+'</div>');
		DialogMessage('delete', 'EditorDeletePage', value);	
  } else 
  
  if (name == 'editpage_save'){
	$('#se_editor_box').html('<div id="dialog-confirm" title="'+form_mess['save_change']+'">'
	+'<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+form_mess['site_publication']+'</p>'
	+'</div>');
		DialogMessage('yes', name, value);	
  } else 
  
  if (name == 'editpage_cancel'){
	$('#se_editor_box').html('<div id="dialog-confirm" title="'+form_mess['back_change']+'">'
	+'<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+form_mess['back_change_text']+'</p>'
	+'</div>');
	DialogMessage('next', name, value);
  }
  else {
	executeWindow(name, value);
  }
  
}

function translite(s){
r = String(s);
r = r.toLowerCase();
r = r.split(' ').join('-');
//r = r.split('-').join('_');
r = r.split('_').join('-');
r = r.split('--').join('-');
r = r.split('а').join('a');
r = r.split('б').join('b');
r = r.split('в').join('v');
r = r.split('г').join('g');
r = r.split('д').join('d');
r = r.split('е').join('e');
r = r.split('ё').join('yo');
r = r.split('ж').join('zh');
r = r.split('з').join('z');
r = r.split('и').join('i');
r = r.split('й').join('j');
r = r.split('к').join('k');
r = r.split('л').join('l');
r = r.split('м').join('m');
r = r.split('н').join('n');
r = r.split('о').join('o');
r = r.split('п').join('p');
r = r.split('р').join('r');
r = r.split('с').join('s');
r = r.split('т').join('t');
r = r.split('у').join('u');
r = r.split('ф').join('f');
r = r.split('х').join('h');
r = r.split('ц').join('c');
r = r.split('ч').join('ch');
r = r.split('ш').join('sh');
r = r.split('щ').join('sch');
r = r.split('ъ').join('');
r = r.split('ы').join('y');
r = r.split('ь').join('');
r = r.split('э').join('e');
r = r.split('ю').join('yu');
r = r.split('я').join('ya');

var reg = new RegExp('[^a-z\-0-9]' ,'gim')
r = r.replace(reg,'');

return r;
}

function getImageLib(elem, path, showimage, smallimage)
{	
	thiselem = elem;
	thispath = path;
	fullimage = showimage;
	trumbimage = smallimage;
	image_list = new Array();
	var imageWindow = window.open('/lib/imanager/imagesmanager.php','','width=700,height=500,location=0,menubar=0,toolbar=0');
	return imageWindow;
	//while (!imageWindow.closed);
}

function addImage(text){
	image_list[image_list.length] = text.substr(1);
}

function setImage(){
	var elem = $(thiselem);
		//dat = elem.val();
	if (image_list.length > 0){
		elem.val(image_list[0]);
		$(fullimage).html('<img src="'+thispath+image_list[0]+'" alt="">');
		if (trumbimage != '') {
			var d = image_list[0].split('.');
			$(trumbimage).html('<img src="'+thispath+d[0]+'_prev.'+d[1]+'" alt="">');
		}
		elem.change();
	}
}

function strpos (haystack, needle, offset) {
    // Finds position of first occurrence of a string within another  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/strpos
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Onno Marsman    
    // +   bugfixed by: Daniel Esteban
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
    // *     returns 1: 14
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

function heightEditor(editem, height) {
			var ed = tinyMCE.editors;
			if (ed[editem] == null) {
				var ed;
				if (editem == 0) ed = $('#edittextshort');
				else ed = $('#edittextfull');
				ed.css('height',(height)+'px');	
			} else {
				$('#'+ed[editem].id+'_tbl').css('height',(height)+'px');
				$('#'+ed[editem].id+'_ifr').css('height', (height - 53)+'px');
			}
}
