function runEditorContent(){
	var $tabs = $( "#dialog-modal-page #tabs-page" ).tabs();			
		$tabs.tabs('select', 0); 

  $('#dialog-modal-page #pagetitle').focusout(function(){
		var namepage = $("#namepage"), pagetitle = $("#pagetitle").val();
		if (namepage.val() == '') {
			namepage.val(translite(pagetitle));
		}
		//alert(pagetitle);
  })
		
		
  $('#dialog-modal-page #cancelEditData').click(function(){ 
		$( '#dialog-modal-page' ).dialog("close");
		$('#dialog-modal-page').remove();
  }); 

  $('#dialog-modal-page #editPageForm').submit(function(){ 
		var namepage = $("#namepage").val(), pagetitle = $("#pagetitle").val();
		if (namepage!='' && namepage.match(/^[a-z0-9\-]+$/)==null){
			alert(ed_mess['bad_symbol_page']);
			return false;
		} 
		if (pagetitle == ''){
			alert(ed_mess['not_title_page']);
			return false;
		}
		if (namepage == ''){
			alert(ed_mess['not_name_page']);
			return false;
		}		
		//$(this).ajaxSubmit(options); 
		return true;
  });
 
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var dialog_width = 800,
			dialog_height = 510;
					
		$( "#dialog-modal-page" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
				//$(".sortPages")[0].style.height = (dialog_height - 125)+'px';
			},
			beforeClose: function(event, ui) {
				$( "#dialog-modal-page" ).remove();
			},
			buttons:[
			{
					text: ed_button['save'],
					click: function() {
					/*(var options = { 
						target: '',
						beforeSubmit: showRequestPage, // функция, вызываемая перед передачей 
						success: showResponsePage, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
					};*/
						$('#editPageForm').submit();
						//ajaxSubmit(options);
						//$( this ).dialog( "close" );
					}
			},{
					text: ed_button['cancel'],
					click: function() {
						$( this ).dialog("close");
						$('#dialog-modal-page').remove();
					}
			}],
			close: function () {
				frameWindow("hide");
			}
			
		});

		function transliteTitle() {
			var namepage = $("#namepage"), pagetitle = $("#pagetitle").val();
			namepage.val(translite(pagetitle));
		}
		//alert(pagetitle);
		
		
		function showRequestPage(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			//alert(queryString);
			return true; 
		} 
 
		// вызов после получения ответа 
		function showResponsePage(responseText, statusText)  {
			var dialogmodal = $('#dialog-modal-page');
			if (dialogmodal != null) dialogmodal.remove();
			//alert(responseText);
			//$('.groupItem .group-content .contentTitle').live('dblclick', function(){});
			//alert('#'+parteditid.val()+' .contentTitle');
		} 			
		
		function getMasterUrl(){
			var sitedomain = $('#domainname').val();
			var namepage = $('#namepage').val();
			return 'http://webmaster.yandex.ru/addurl.xml?url='+sitedomain+'%2F'+namepage+'%2F';
		}
}