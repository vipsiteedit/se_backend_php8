function runPageText(){
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var namefield = $( "#namefield" ),
			textfield = $( "#textfield" ),
			text_space = 30,
			dialog_width = 800,
			dialog_height = 500;
					
		$( "#dialog-modal" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			open : function(event, ui) {
				editor_tinymce(this.clientHeight - text_space);
			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
				heightEditor(0, dialog_height - text_space + 8);
			},
			buttons: [
			{
				text: ed_button['save'],
				click: function() {
				    //alert(parent.document.location);
					var ed = tinyMCE.activeEditor;
					$.ajax({
						url:  "/?jqueryform=sitevars",
						type: 'POST',
						data: {name: ""+namefield.val()+"", value: ""+ed.getContent()+""},
						success: function(data){
							$('#'+namefield.val() , parent.document ).html(data); //ed.getContent()
							$('#dialog-modal').remove();
						}
					});
					$( this ).dialog( "close" );
				}
			},{
				text: ed_button['cancel'],
				click: function() {
					$( this ).dialog( "close" );
					$('#dialog-modal').remove();
				}
			}],
			close: function () {
				frameWindow("hide");
			},
			beforeClose: function(event, ui) {
				$('#dialog-modal').remove();
			}
		});
}