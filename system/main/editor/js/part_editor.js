function runPartEditor(name){
    if($("#dialog:ui-dialog").html() != null) return;
		var parform = $( "#dialog-modal-part #partform" ),
			partid = $("#dialog-modal-part #partid"),
			parttype = $("#dialog-modal-part #parttype"),
			parttitle = $("#dialog-modal-part #parttitle"),
			partimage = $("#dialog-modal-part #partimage"),
			partpage = $("#dialog-modal-part #partpage").val(),
			text_space = 70;

		var dialog_width = $('html')[0].clientWidth - 300,
			dialog_height = $('html')[0].clientHeight - 60;
			if (dialog_width < 800) dialog_width = 800;
			
			
		if (name=='addsection'){
		$( ".groupItem" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".group-header" )
			.addClass( "ui-widget-header ui-corner-all" )
			.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
			.end()
			.find( ".group-content" );
		}
			
			
		var $tabs = $( "#tabs-part" ).tabs();			
		$tabs.tabs('select', 0); 

		//$( "#dialog:ui-dialog" ).dialog( "destroy" );
		$( "#dialog-modal-part" ).dialog( "destroy" );
		$( "#dialog-modal-part" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			open : function(event, ui) {
				dialog_height = this.clientHeight;
				editor_tinymce(dialog_height - text_space);
				$('.tabsBox').css('height', (dialog_height - 5)+'px');
				/*var ed = tinyMCE.activeEditor, id_area;
				if (ed != null)
					id_area = ed.id
				else id_area = 'parttext';
				$('#'+id_area+'_tbl').css('height',(dialog_height - text_space)+'px');
				$('#'+id_area+'_ifr').css('height', '100%');
				*/
				$('#parttype').css('height', (dialog_height - 25)+'px');
				//$('#partparambox').css('height', '100%');
				$('#partparambox').css('height', (dialog_height - 25)+'px');
				$('#delimage').click(function(){
					$('#partimageshow').html('');
					$('#partimage').val('');
				});

				$('.part_record_ed').click(function(){
					var rec_id = $('.part_record_ed')[0].id;
					rec_id = rec_id.split('_');
					editRecord(rec_id[1], rec_id[2]);
				});
				$('.part_record_del').click(function(){
					var rec_id = $('.part_record_ed')[0].id;
					rec_id = rec_id.split('_');
					deleteRecord(rec_id[2]);
				});


			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
				var ed = tinyMCE.activeEditor;
				$('.tabsBox').css('height', (dialog_height - 5)+'px');
				$('#parttype').css('height', (dialog_height - 25)+'px');
				$('#partparambox').css('height', (dialog_height - 25)+'px');
				$('#'+ed.id+'_tbl').css('height',(dialog_height - text_space)+'px');
				$('#'+ed.id+'_ifr').css('height', (dialog_height - text_space - 46)+'px');
			},
			buttons:[
				{
					text: ed_button['save'],
					click: function() {
					var elems = $(".recordWrapper"),
					datasort = '', target_id = "";

					if (elems.html() != null){
						var d = $(elems).sortable('serialize');
						if(d != ''){
							if (datasort != '') datasort = datasort + '&';
							datasort = d.split('&group[]=').join(",").replace('group[]=','');
							$("#recordgroup").val(datasort);
						}
					}

					if (setNewSection(partid.val())){
						target_id = $(".groupItem#group_"+partid.val(), parent.document);
					} else {
						target_id = $(".groupItem#group_"+partid.val()+" > .group-content", parent.document);
					}

					//alert(partid.val());
					var options = {
						target: target_id,
						beforeSubmit: showRequestPart, // функция, вызываемая перед передачей 
						success: showResponsePart, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
					};

					var ed = tinyMCE.editors;					
					for (var i = 0; i < ed.length; i++){
						$('#'+ed[i].id).val(ed[i].getContent());
					};
					
					$('#partform').ajaxSubmit(options);
					$('#dialog-modal-part').remove();
					$( this ).dialog( "close" );
					frameWindow("hide");
					
				}},
				{
					text: ed_button['cancel'],
					click: function() {
					$( this ).dialog( "close" );
					frameWindow("hide");
					$('#dialog-modal-part').remove();
					}
				},
				{
					text: ed_button['delete'],
					click: function() { 
						messageDelSection(partid.val(), this);
					//	deleteSection(partid.val());
					//$( this ).dialog( "close" );
				
				}}
				],
			close: function () {
				frameWindow("hide");
			},
			beforeClose: function(event, ui) { 
				$('.ui-autocomplete-loading').toggleClass( "ui-autocomplete-loading" ).toggleClass( "" );
				$('#dialog-modal-part').remove();
				//parent.console.log("123");
			}
			
		});		

		function deleteRecord(record_id){
			$('#mess_dialog').html('<div id="dialog-confirm" title="Удалить страницу?">'
			+'<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Вы действительно желаете удалить запись "'+record_id+'"?</p>'
	+'</div>');
			//$(function() {
				$( "#dialog-confirm" ).dialog( "destroy" );
				//$( "#dialog-confirm" ).remove();
				$( "#dialog-confirm" ).dialog({
					resizable: false,
					height:160,
					modal: true,
					buttons: [
					{
						text: ed_button['delete'],
						click: function() {
							$('#dialog-modal-part #group_'+record_id).remove();
							$( this ).dialog( "close" );
						}
					},{
						text: ed_button['cancel'],
						click: function() {
							$( this ).dialog( "close" );
						}
					}],
					beforeClose: function(event, ui) {
							$('#mess_dialog').html('');
							$( "#dialog-confirm" ).remove();
					}
				});
			//});	
		}
		
		function editRecord(sect_id, record_id){
			var dialogmodal = $('#dialog-modal-rec');
			if (dialogmodal.html() != null){
					dialogmodal.dialog( "close" );
					dialogmodal.remove();
			}			
			var dialogmodal = $('#dialog-modal-rec');
			if (dialogmodal.html() != null){
					dialogmodal.dialog("close");
					dialogmodal.remove();
			}
			
			getEditWindow('editrecord', sect_id+'_'+record_id);
		}

		function messageDelSection(section_id, dialog){
			var arr = {
				resizable: false,
				height:180,
				modal: true,
				buttons: [{
							text: parent.ed_button['yes'],
							click:  function() {
								deleteSection(section_id);
								$( this ).dialog( "close" );
								$(dialog).dialog("close");
								//$('#dialog-modal-part').remove();							
							}},
							{
								text: parent.ed_button['no'],
								click: function() {
								$( this ).dialog( "close" );
								}
							}]
			};
			messageYesNo('mess_dialog', parent.ed_mess['delete_title'], 
							parent.ed_mess['delete_section'], arr);
		}
		
		function activationEnentSectIconFrame(target){
			$( ".group-header .ui-icon", target).click(function() {
				$( this, target).toggleClass( "ui-icon-minusthick").toggleClass( "ui-icon-plusthick" );
				$( this, target).parents( ".groupItem:first").find( ".group-content").toggle();
			});
		}

		function setNewSection(sect_id){
			var itemsect = $('.groupItem#group_'+sect_id, parent.document),
			group_id = Math.floor(sect_id / 1000);
			
			if (itemsect.html() == null){
				var	text = "<div class=\"groupItem ui-widget ui-widget-content ui-helper-clearfix ui-corner-all\" id=\"group_"+sect_id+"\"></div>";
				if (group_id < 100) {
					$('.groupWrapper#sort'+group_id, parent.document).append(text);
				} else { 
					$('.ggroupWrapper#sort'+group_id, parent.document).append(text);
				}
				//activationEnentSectIconFrame(parent.document);
				//activationEventEdPart();
				return true;
			}
			return false;
		}

		function deleteSection(sect_id){
			$.ajax({
				url: "/"+partpage+"/?on_ajax_execute",
				type: 'POST',
				data: { name: "EditorDeleteSection", value: ""+sect_id+""},
				success: function(data){
					if (data == 'ok'){
						var itemsect = $('.groupItem#group_'+sect_id, parent.document);
						if (itemsect.html() != null){
							itemsect.remove();
						}
					}
				}
			});
		}



		function showRequestPart(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			//alert(queryString);
			return true; 
		} 
 
		// вызов после получения ответа 
		function showResponsePart(responseText, statusText){
			//var dialogmodal = $('#dialog-modal-part');
			//if (dialogmodal != null) dialogmodal.remove();
			//if (setNewSection(partid.val(), responseText)){
				startEvents();
				parent.activationEnentSectIcon(parent.document);
				parent.activationEventEdPart(parent.document);
				//return false;
			//}
			//return false;
		} 			
		// вызов перед передачей данных

		$('tbody.recordWrapper').sortable({
			connectWith: "tbody.recordWrapper",
			stop :function(event, ui)
				{
				},
			start: function(event, ui){
			  // alert($(this).className)
			}
		}).disableSelection();

	
	//$('#dialog-modal-part #imageadd').click(function(){
		$('#dialog-modal-part #imageadd').click(function(){
			getImageLib('#partimage', '/', '#partimageshow');
			$('#partimage').change(function(){
			  var imp = $('#partimage').val();
			  if (strpos (imp,'/show/', 0) === false){
				$('.showsize').css('visibility', 'visible');
				$( "#sliderwidthimage" ).slider({
					range: "min",
					value: 250,
					min: 10,
					max: 800,
					slide: function( event, ui ) {
						$('#partimageshow img').css('width', ui.value+'px');
						$( "#partimagesize" ).val( ui.value );
					}
				});
				$( "#partimagesize" ).val($( "#sliderwidthimage" ).slider( "value" ) );
				$('#partimageshow img').css('width', '250px');
			  }
			});
		});
		
		$('#dialog-modal-part #imagedel').click(function(){
			$('#partimage').val('');
			$('#partimageshow img').remove();
		});
	//});
	return true;
}