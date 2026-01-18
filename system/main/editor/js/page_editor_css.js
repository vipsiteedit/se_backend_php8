
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var dialog_width = 800,
			dialog_height = 500;
					
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
					var options = { 
						target: '',
						beforeSubmit: showRequestPage, // функция, вызываемая перед передачей 
						success: showResponsePage, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
					};
					
					var sheet = addStyleSheet( '' ),
					sitestyle = $('#sitestyle').val();
					changeStyleSheet( sheet, sitestyle );
					$('#defaultCSS').href = $('#thisPageDir').val()+'/skin/'+$('#pageCss').val()+'.css';
					$('#editpagecssform').ajaxSubmit(options);
					$( this ).dialog( "close" );
				},
				"Отмена": function() {
					$( this ).dialog("close");
					$('#dialog-modal-page').remove();
				}
			},
			close: function () {
				frameWindow("hide");
			},
			beforeClose: function(event, ui) {
				$( "#dialog-modal-page" ).remove();
			}	
		});

		function showRequestPage(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			//alert(queryString);
			return true; 
		} 
 
		// вызов после получения ответа 
		function showResponsePage(responseText, statusText)  {
			//var dialogmodal = $('#dialog-modal-page');
			//if (dialogmodal != null) dialogmodal.remove();
			//alert(responseText);
			//$('.groupItem .group-content .contentTitle').live('dblclick', function(){});
			//alert('#'+parteditid.val()+' .contentTitle');
		} 			

var head = document.getElementsByTagName('head')[0];
// создаем таблицу стилей с css стилями
// и возвращаем ссылку на нее
function addStyleSheet( css ) {
	var sheet = document.createElement( 'style' );
	sheet.type = 'text/css';
	head.appendChild( sheet );
	if( sheet.styleSheet )
		sheet.styleSheet.cssText = css;
	else
		sheet.appendChild( document.createTextNode(css) );
	return sheet;
} 
// Заменяем таблицу стилей новыми css стилями
// (текстовой строкой)
function changeStyleSheet( sheet, css ) {
	if( sheet.styleSheet )
		sheet.styleSheet.cssText = css;
	else
		sheet.replaceChild(document.createTextNode(css), sheet.firstChild );
		return sheet;
} 