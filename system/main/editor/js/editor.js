var image_list = null,
	fullimage = '',
	trumbimage = '',
	thiselem = '',
	thispath = '',
	this_dialog_modal = '',
	flag_show_logotype = false;

	/*
function editor_tinymce(dialog_height, elements){

	if (elements == undefined){
	tinyMCE.init({
        language : editor_language,
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
        language : editor_language,
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
}*/


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
	}
}

function getImageLib(elem, path, showimage, smallimage)
{	
	thiselem = elem;
	thispath = path;
	fullimage = showimage;
	trumbimage = smallimage;
	image_list = new Array();
	var imageWindow = window.open('/lib/imanager/imagesmanager.php','','width=700,height=500,location=0,menubar=0,toolbar=0');
	//while (!imageWindow.closed);
}

function executeWindow(name, value)
{	
	seeditor.executeWindow(name, value);
}

function getEditWindow(name, value) {
	seeditor.getEditWindow(name, value);
}

function activationEnentSectIcon(target){
		/*$( ".group-header .ui-icon", target).click(function() {
			$( this).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			$( this).parents( ".groupItem:first" ).find( ".group-content" ).toggle();
		});*/
}

function getPartNum(elem){
            if (elem == undefined) return;
			var sect_id = $(elem).firstParent(".groupItem")[0].id;
				sect_id = sect_id.split('_');
				return sect_id[1];
}

function getRecordNum(elem){
			var sect_id = $(elem).firstParent(".groupItem")[0].id,
				rec_id = $(elem).firstParent(".record-item")[0].id;
				sect_id = sect_id.split('_');
				rec_id = rec_id.split('-');
				return  new Array(sect_id[1],rec_id[3]);
}


function activationEventEdPart(target){
			/*$('.group-header .se-edit-content', target).unbind('click');
			$('.group-header .se-edit-content', target).click(function() {
				var dialogmodal = $('#dialog-modal-part', seeditor);
				if (dialogmodal == null || dialogmodal.html() == null) {
					var sect_id = getPartNum(this);
					$(this).addClass( "ui-autocomplete-loading" );
					getEditWindow('editsection', sect_id);
					$( this ).toggleClass( "ui-autocomplete-loading" ).toggleClass( "" );
				} else {
					dialogmodal.dialog( "close" );
					dialogmodal.remove();
				}
			});
			
			$('.group-header .se-add-record', target).unbind('click');
			$( ".group-header .se-add-record", target).click(function() {
				var dialogmodal = $('#dialog-modal-rec', seeditor);
				if (dialogmodal == null || dialogmodal.html() == null) {
					var sect_id = getPartNum(this);
					$(this).addClass( "ui-autocomplete-loading" );
					getEditWindow('addrecord', sect_id);
					$( this ).toggleClass( "ui-autocomplete-loading" ).toggleClass( "" );
				} else {
					dialogmodal.dialog( "close" );
					dialogmodal.remove();
				}
			});*/

}

function editThisRecord(name){
		$( ".record-"+name ).removeClass( "se-edit-record-"+name );
		$( ".record-"+name ).addClass( "se-edit-record-"+name );
		/*$('.se-edit-record-'+name).unbind('dblclick');
		$( ".se-edit-record-"+name ).dblclick(function() {
			var rec = getRecordNum(this);
			var partid_ = $( "#partid" ).val();
			if (name == 'note' && (partid_ != undefined)) {
				alert(form_mess['not_completed']);
				return;
			}
			 $.ajax({
				url: parent.document.location.pathname+"?on_ajax_execute",
				type: 'POST',
				data: {name: "editrecord"+name+"",value: ""+rec[0]+"_"+rec[1]+""},
				success: function(data){
					$(".groupItem#group_"+rec[0]+" #se-edit-record-"+rec[1]+" .se-edit-record-"+name).html(data);
					$import('/system/main/editor/js/record_editor_'+name+'.js', false);
				}
			});
		});*/
}

function activationEventEdRec(target){
		/*if ($('.group-header', target).html() != null){
			$('.recordsWrapper', target).css('padding','22px 0 0 0');
		}
		$('.recordsWrapper', target).sortable(
			{
				connectWith: this,
				handle: ".itemRecordHeader",
				items: ".record-item",
				stop: function(event, ui){
					processStopRec(this.id);
				}
			}
		).disableSelection();*/
		
		/*$('.groupItem .se-edit-record', target).unbind('click');
		$( ".groupItem .se-edit-record", target).click(function() {
			var dialogmodal = $('#dialog-modal-rec', seeditor);
			if (dialogmodal.html() != null){
					dialogmodal.dialog( "close" );
					dialogmodal.remove();
			}			
			var dialogmodal = $('#dialog-modal-rec', seeditor);
			if (dialogmodal.html() != null){
					dialogmodal.dialog("close");
					dialogmodal.remove();
			}
			
			var element = $(this).firstParent(".groupItem"),
					sect_id = element[0].id, rec_id = $(this)[0].id;
					sect_id = sect_id.split('_');
					rec_id = rec_id.split('-');
					getEditWindow('editrecord', sect_id[1]+'_'+rec_id[2]);
		});*/

		/*$('.groupItem .record-item', target).hover(
			function () {
				//$(this).css('margin','-1px');
				$(this).css('outline', '#ff0000 dashed 1px');
				$(this).find(".itemRecordHeader").show().css('visibility','visible'); 
				//$(this).css('padding','5px');
			},
			function () {
				//$(this).css('margin','0');
				$(this).css('outline','');
				$(this).find(".itemRecordHeader").hide(); 
				
				//.css('visibility','hidden');
				//$(this).find(".se-edit-record").css('visibility','hidden');
				//$(this).css('padding','5px');
			}
		);*/
		/*
		editThisRecord('field');
		editThisRecord('title');
		editThisRecord('pimage');
		editThisRecord('image');
		editThisRecord('note');
		editThisRecord('text');
		*/
}

function showRequest(formData, jqForm, options) { 
    var queryString = $.param(formData); 
    return true; 
}  
function showResponse(responseText, statusText)  {
	/* здесь можно убрать прогресс */
}

function setMenuIcon(menuClass, level, target) {
		//$(menuClass, target).prepend('<span class="pageMenuIcon" title="'+ed_mess['edit_menu']+'"></span>');

		/*$(menuClass+' .pageMenuIcon', target).click(function(){
			getEditWindow('editpage_menu', level);
		});*/
		
		/*$(menuClass).hover(
			function () {
				$(menuClass+' .pageMenuIcon', target).css('visibility','visible');
				$(this).css('outline','1px dashed #0000FF');
			},
			function () {
				$(menuClass+' .pageMenuIcon', target).css('visibility','hidden');
				$(this).css('outline','');
			}
		);*/
}

function setLogotype(target){
		//$import('/system/main/editor/js/i18n/'+editor_language+'.js', true);
		/*$('#LogotypeBlock', target).prepend('<div class="se-editor-logo">'
		+'<h4 style="width: 100%; color: #000;"><span width="90%"; float: left;>'+ed_logo['title']+'</spam><span style="float:right; cursor: pointer;" onClick="editLogoClose();" title="'+ed_logo['close']+'">x</span></h4>'
		+'<form style="margin:0px;" id="LogotypeForm" method="post" enctype="multipart/form-data" action="?jqueryform=sitelogo">'
		+'<input type="file" name="filelogo[0]" id="add_logo"><br>'
		+'<input title="'+ed_logo['message']+'" type="text" size="3" value="120" name="logo_width" id="logo_width">'
		+'<input type="submit" value="'+ed_logo['upload']+'" name="GoToEditContent" id="sendData">'
		+'</form></div>'
		);*/

		/*$('#LogotypeBlock .se-editor-logo #LogotypeForm', target).click(function(){ 
			flag_show_logotype = true; 
		});*/

		/*$('#LogotypeBlock', target).hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).find(".se-editor-logo").css('visibility','visible');
				},function () {
				if (flag_show_logotype == false) {
					$(this).css('outline','');
					$(this).find(".se-editor-logo").css('visibility','hidden');
				}
			}
		);*/
}


function startEvents(target){
		/*setLogotype(target);

		var mouseUpp = false;
		var mouseDrop = null;
		$('.groupWrapper .groupItem', target).mousedown(function(e){
			mouseUpp = true;
			if (mouseDrop == null)
				mouseDrop = this;
		});
		$('.groupWrapper .groupItem', target).mousemove(function(e){
			//$(this).mouseMove(function(e){alert('111');})
			if (mouseUpp == true && mouseDrop != null){
				//$(mouseDrop).css('width','150px');
				//$(mouseDrop).css('visibility','hidden');
				$('.group-header.ui-widget-header', mouseDrop).css('visibility','visible');
			}
			
		});

		$('.groupWrapper', target).mouseup(function(e){
			mouseUpp = false;
			if (mouseDrop != null){
			$(mouseDrop).css('width','100%');
			$(mouseDrop).css('visibility','visible');
			mouseDrop = null;
			}
		});
		
		
		// Иконки Меню
		setMenuIcon('#pageMenu', 1, target);
		setMenuIcon('#pageMainmenu', 0, target);
			
		//$('.groupWrapper .groupItem').css('outline','#85A8C6 dashed 1px');
		$('.groupWrapper .groupItem', target).hover(
			function () {
				//$(this).css('outline','#85A8C6 dashed 1px');
				var headtitle = $(this).find(".ui-widget-header");
				$(headtitle).css('visibility','visible');
				//$(headtitle).css('bottom','10px');
				$(this).find(".se-add-record").css('visibility','visible');
			},
			function () {
				$(this).find(".ui-widget-header").css('visibility','hidden');
				$(this).find(".se-add-record").css('visibility','hidden');
				}
			);

			
		$('div.groupWrapper', target).sortable(
			{
				connectWith: "div.groupWrapper",
				handle: "div.group-header",
				items: "div.groupItem",
				over: function(event, ui) {
					$('div.groupItem').css('visibility','visible');
				},
				stop: function(event, ui){
					processStop(this, $('.groupWrapper'));
					$('div.groupItem').css('visibility','visible');
				}
			}
		).disableSelection();

		//$('.ggroupWrapper .groupItem').css('outline','#85A8C6 dashed 1px');
		$('.ggroupWrapper .groupItem', target).hover(
			function () {
				//$(this).css('outline','#85A8C6 dashed 1px');
				var headtitle = $(this).find(".ui-widget-header");
				$(headtitle).css('visibility','visible');
				$(this).find(".se-add-record").css('visibility','visible');
			},
			function () {
				$(this).find(".ui-widget-header").css('visibility','hidden');
				$(this).find(".se-add-record").css('visibility','hidden');
				//$(this).css('outline','');
				}
			);

			
		$('div.ggroupWrapper', target).sortable(
			{
				connectWith: "div.ggroupWrapper",
				handle: "div.group-header",
				items: "div.groupItem",
				over: function(event, ui) {
					$('div.groupItem').css('visibility','visible');
				},
				start: function(event, ui){
					//$(this).css('width','100%');
				},
				stop: function(event, ui){
					processStop(this, $('.ggroupWrapper'));
					//$('div.groupItem').css('width','100%');
					$('div.groupItem').css('visibility','visible');
				}
			}
		).disableSelection();

		
		
		$( ".groupItem", target).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".group-header" )
			.addClass( "ui-widget-header ui-corner-all" )
			.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
			.end()
			.find( ".group-content" );
		
        var sitetitle = $( "#pageTitle" , target);
			sitetitle.hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).css('cursor','pointer');
				//$(this).find(".se-editor-sitetitle").css('visibility','visible');
				
				},
			function () {
				$(this).css('outline','');
				$(this).css('cursor','');
				//$(this).find(".se-editor-sitetitle").css('visibility','hidden');
			});

			/*sitetitle.dblclick(function() {
			  getEditWindow('editpage_content','');
			});*/

*/
		// Редактировать заголовок сайта
        /*var sitetitle = $( "#siteTitle" , target);
			sitetitle.hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).css('cursor','pointer');
				//$(this).find(".se-editor-sitetitle").css('visibility','visible');
				
				},
			function () {
				$(this).css('outline','');
				$(this).css('cursor','');
				//$(this).find(".se-editor-sitetitle").css('visibility','hidden');
			});*/

			/*sitetitle.dblclick(function() {
			  getEditWindow('siteedit_vars','prj_sitetitle');
			});*/

		// Редактировать заголовок сайта
        /*var sitesubtitle = $( "#siteSubtitle" , target);
		sitesubtitle.hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).css('cursor','pointer');
				//$(this).find(".se-editor-sitetitle").css('visibility','visible');
				
				},
			function () {
				$(this).css('outline','');
				//$(this).find(".se-editor-sitetitle").css('visibility','hidden');
				
			}
		);*/
		/*sitesubtitle.dblclick(function() {
			  getEditWindow('siteedit_vars','prj_sitesubtitle');
		});*/
		
		
		/*$('.groupItem .group-content .contentTitle', target).hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).css('padding','5px');
				//$(this).css('margin','-1px');
				//$("#parttitle", this).css({ 'background':'none repeat scroll 0 0 #F4E7E9', 'border':'1px solid #FF0000', 'border-radius':'3px', 'padding':'0 3px' });
				
				},
			function () {
				$(this).css('outline','');
				$(this).css('padding','5px');
				//$(this).css('margin','0');
				//$("#parttitle", this).css({ 'background':'none repeat scroll 0 0 transparent', 'border':'0px solid #FF0000', 'border-radius':'0px', 'padding':'0 3px' });
				
			}
		);*/

// Редактирование элементов раздела
		/*$('.groupItem .group-content .contentTitle', target).unbind('dblclick');
		$( ".groupItem .group-content .contentTitle", target).dblclick(function() {
			var element = $(this).firstParent(".groupItem");
			var sectname = element[0].id;
			sect_id = sectname.split('_');
			 $.ajax({
				url: parent.document.location.pathname+"?on_ajax_execute",
				type: 'POST',
				data: {name: "editsectiontitle",value: ""+sect_id[1]+""},
				success: function(data){
					$('.groupItem#'+sectname+' .contentTitle').html(data);
					$import('/system/main/editor/js/part_editor_title.js', false);
					//partEditorSmile();
				}
			});
		});*/

		
		/*$('.groupItem .group-content .contentText', target).unbind('dblclick');
		$( ".groupItem .group-content .contentText", target).dblclick(function() {
			var element = $(this).firstParent(".groupItem");
			var sectname = element[0].id;
			sect_id = sectname.split('_');
			 $.ajax({
				url: parent.document.location.pathname+"?on_ajax_execute",
				type: 'POST',
				data: {name: "editsectiontext",value: ""+sect_id[1]+""},
				success: function(data){
					$('.groupItem#'+sectname+' .contentText').html(data);
					editor_tinymce(200);
					$import('/system/main/editor/js/part_editor_text.js', false);
					//partEditorSmile();
				}
			});
		});*/
		/*$('.groupItem .group-content .contentText', target).hover(
			function () {
				$(this).css('outline','1px dashed #ff0000');
				$(this).css('padding','5px');
				//$(this).css('margin','-1px');
			},
			function () {
				$(this).css('outline','');
				$(this).css('padding','5px');
				//$(this).css('margin','0');
			}
		);*/
		/*$('.groupItem .group-content div.contentImage', target).unbind('dblclick');
		$( ".groupItem .group-content div.contentImage", target).dblclick(function() {
			var element = $(this).firstParent(".groupItem");
			var sectname = element[0].id;
			sect_id = sectname.split('_');
			 $.ajax({
				url: parent.document.location.pathname+"/?on_ajax_execute",
				type: 'POST',
				data: {name: "editsectionimage",value: ""+sect_id[1]+""},
				success: function(data){
					$('.groupItem#'+sectname+' div.contentImage').html(data);
					$import('/system/main/editor/js/part_editor_image.js', false);
					partEditorSmile();
				}
			});
		});*/
		// При наведении на картинку
		/*$('.groupItem .group-content div.contentImage', target).hover(
			function () {
				$(this).css('outline','1px dotted #ff0000');
				$(this).css('padding','5px');
				//$(this).css('margin','-1px');
			},
			function () {
				$(this).css('outline','');
				$(this).css('padding','5px');
				//$(this).css('margin','0');
			}
		);
		activationEventEdRec();
		$import('/system/main/editor/js/record_editor_images.js', false);*/
}

function editLogoClose(){
	flag_show_logotype = false;
	$('#LogotypeBlock').css('outline','');
	$('#LogotypeBlock .se-editor-logo').css('visibility','hidden');	
}
		
function partEditorSmile(){
		$('#editPartSmileForm').submit(function() { 
		var partid = $('#partid'),
			formeditname = $('#formeditname'),
			options = {
				target: ".groupItem#group_"+partid.val()+" ."+formeditname.val(),
				beforeSubmit: showRequest,  
				success: showResponse,
				timeout: 3000
			};
			if (formeditname.val() == 'contentText'){
				var ed = tinyMCE.activeEditor;					
				$('#'+ed.id).val(ed.getContent());
			}
			$(this).ajaxSubmit(options); 
			return false;
		});
}


function processStopRec(rid)
{
	var elems = $("#"+rid);
	var data = '';
	for (var i = 0; i < elems.length; i++){
		var id = elems[i].id;
		var d = $('#'+id).sortable('serialize');
			if(d != ''){
				if (data != '') data = data + '&';
				data = data + d.split('se-edit-record[]').join(id+'[]');
			}
	}
	$.ajax({
		url: parent.document.location.pathname+"?on_ajax_execute&sortablerec",
		type: 'POST',
		data: {data: ""+data+""},
		success: function(res){}
	});
}

function $import(src, cache){
	var ms = new Date().getTime().toString(),
    scriptElem = document.createElement('script');
    if (cache != true) src = src + '?'+ms;
	scriptElem.setAttribute('src',src);
    scriptElem.setAttribute('type','text/javascript');
    document.getElementsByTagName('head')[0].appendChild(scriptElem);
}

$(document).ready(
	function () {

		activationEnentSectIcon(document);
		activationEventEdPart(document);
		startEvents(document);
	}
);

function processStop(object, elems)
{
  var data = '';
		for (var i = 0; i < elems.length; i++){
			var id = elems[i].id;
			var d = $('#'+id).sortable('serialize');
			if(d != ''){
				if (data != '') data = data + '&';
				data = data + d.split('group[]').join(id+'[]');
			}
		} 		
		setData(data);
}

function setData(data){  

  $.post('?on_ajax_execute&sortable', { data:data },function(res){
  if (res!=''){
	var res_id = res.split('|');
	$(".groupItem#group_"+res_id[0]+" .group-header").find('.se-edit-content').html(res_id[2]); 
	$(".groupItem#group_"+res_id[0])[0].id="group_"+res_id[1]; 
  }
  });
}


