function runRecordEditir(){
		var partid = $("#dialog-modal-rec #partid"),
			recid = $("#dialog-modal-rec #recid"),
			height_tab1 = 50;

		var dialog_width = $('html')[0].clientWidth - 300,
			dialog_height = $('html')[0].clientHeight - 60;
			if (dialog_width < 800) dialog_width = 800;
			
			

		var $tabs = $( "#dialog-modal-rec #tabs-rec" ).tabs({
			show: function(event,ui) {
				if (ui.index == 1) {
					heightEditor(1, dialog_height - 22);
				}
			}
		});			
		$tabs.tabs('select', 0); 
		
		function showRequestRec(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			return true; 
		} 
 
		// вызов после получения ответа 
		function showResponseRec(responseText, statusText)  {
			//var dialogmodal = $('#dialog-modal-rec');
			//if (dialogmodal != null) dialogmodal.remove();
			startEvents();
			$( '#dialog-modal-rec' ).dialog( "close" );
		} 			

		
		
		//$( "#dialog:ui-dialog" ).dialog( "destroy" );
		$( "#dialog-modal-rec" ).dialog( "destroy" );
		$( "#dialog-modal-rec" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {

			},
			open : function(event, ui) {
				dialog_height = this.clientHeight;
				editor_tinymce(dialog_height - 8 - height_tab1);
				$('.tabsBox').css('height', (dialog_height - 5)+'px');
				$('#er_checkshow').css('top', (dialog_height - 20)+'px');
				$('#delimage').click(function(){
					$('#recimageshow').html('');
					$('#recimage').val('');
				});
				//heightEditor(0, dialog_height - 70);
				//heightEditor(1, dialog_height - 35);

			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
				$('.tabsBox').css('height', (dialog_height - 5)+'px');
				$('#er_checkshow').css('top', (dialog_height - 20)+'px');
				heightEditor(0, dialog_height - height_tab1);
				heightEditor(1, dialog_height - 22);
			},
			buttons: [
			{
				text: ed_button['save'],
				click: function() {
					//$(".groupItem #"+parteditid.val() ).addClass( "ui-autocomplete-loading" );
					//setNewSection(partid.val());
					var ed = tinyMCE.editors, 
					reclist = null,
					rec_id = $('#recid').val();		
					
					for (var i = 0; i < ed.length; i++){
						$('#'+ed[i].id).val(ed[i].getContent());
					};
					
					reclist = $('.recordList#group_'+rec_id+' .part_record_title', parent.document);
					if (reclist.html()!=null){
					   reclist.html($('#rectitle').val());
					}
					   
					var options = { 
						target: $(".groupItem#group_"+partid.val()+" > .group-content", parent.document),
						beforeSubmit: showRequestRec, // функция, вызываемая перед передачей 
						success: showResponseRec, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
					};
					
					$('#recordsform').ajaxSubmit(options);
				}},
				{
					text: ed_button['cancel'],
					click: function() {
					$( this ).dialog( "close" );
					$('#dialog-modal-rec').remove();
				}},
				{
					text: ed_button['delete'],
					click: function() {					
						messageDelRecord(this);
					//$( this ).dialog( "close" );
					}
				}
			],
			close: function () {
				frameWindow("hide");
			},
			beforeClose: function(event, ui) { 
				$('#dialog-modal-rec').remove();
			
			//alert(".groupItem #"+parteditid.val());
				//.find( ".group-content" ).toggle();
				//$('.ui-autocomplete-loading').toggleClass( "ui-autocomplete-loading" ).toggleClass( "" );
				//this.remove();
				//$('#edittextshort,#edittextfull').remove();
			}
			
		});

		function messageDelRecord(dialog){
			var arr = {
				resizable: false,
				height:180,
				modal: true,
				buttons: [{
							text: parent.ed_button['yes'],
							click:  function() {
								deleteRecord();
								$( this ).dialog( "close" );
								$(dialog).dialog("close");						
								frameWindow("hide");
								$('#dialog-modal-rec').remove();
							}},
							{
								text: parent.ed_button['no'],
								click: function() {
								$( this ).dialog( "close" );
								}
							}]
			};
			messageYesNo('mess_dialog', parent.ed_mess['delete_title'], 
							parent.ed_mess['delete_record'], arr);
		}

		
		function deleteRecord(){
			var part_id = $('#partid').val(),
				rec_id = $('#recid').val();
			$.ajax({
				url: parent.thispage +"?on_ajax_execute",
				type: 'POST',
				data: { name: "EditorDeleteRecord", partid: ""+part_id+"", recid: ""+rec_id+""},
				success: function(data){
					$(".groupItem#group_"+part_id+" > .group-content", parent.document).html(data);
					startEvents();
					//activationEventEdPart();
				}
			});
		}
		
		$('#dialog-modal-rec #imageadd').click(function(){
			getImageLib('#recimage', '/', '#recimageshow');
			$('#recimage').change(function(){
			  var imp = $('#recimage').val();
			  if (strpos (imp,'/show/', 0) === false){
				$('.showsize').css('visibility', 'visible');
				$( "#sliderwidthimage" ).slider({
					range: "min",
					value: 250,
					min: 10,
					max: 800,
					slide: function( event, ui ) {
						$('#recimageshow img').css('width', ui.value+'px');
						$( "#rwidth_img" ).val( ui.value );
					}
				});
				$( "#recimagesize" ).val($( "#sliderwidthimage" ).slider( "value" ) );
				$('#recimageshow img').css('width', '250px');
			  }
			});
		});
		
		$('#dialog-modal-rec #imagedel').click(function(){
			$('#recimage').val('');
			$('#recimageshow img').remove();
		});
}