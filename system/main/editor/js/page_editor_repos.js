
		$("ul.menuPages").menu();
		$(".menuPages .itemMenu a").click(function(){
			var href = $( this ).attr('href');
			document.location.href=''+href+'';
			return false;
		});
		
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var dialog_width = 800,
			dialog_height = 500;
		var options = { 
						target: "",
						beforeSubmit: showRequestPage, // функция, вызываемая перед передачей 
						success: showResponsePage, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
		};
					
		$( "#dialog-modal-page" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
				$(".sortPages")[0].style.height = (dialog_height - 125)+'px';
			},
			buttons: {
				"Сохранить": function() {
					document.location.href='/home/';
					$( this ).dialog( "close" );
				},
				"Отмена": function() {
					$( this ).dialog( "close" );
					$('#dialog-modal-rec').remove();
				}
			}
		});

		function showRequestPage(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			alert(queryString);
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
