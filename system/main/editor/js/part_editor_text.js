var partid = $("#editPartSmileForm #partid"),
	textelement_id = ".groupItem#group_"+partid.val()+" .contentText",
	parttext = $('#editPartSmileForm #editparttext');
//	ed = tinyMCE.activeEditor;					
	$(document).keydown(function(eventObject){          
		if (eventObject.which == 27){
			seLoadBox('', textelement_id, 'showsectiontext',partid.val());
			textelement_id = null;
		}
   });
	
  // привязываем событие submit к форме  
  //".groupItem#group_"+partid.val()+" ."+formeditname.val()
  
  		$('#editPartSmileForm').submit(function() { 
				var ed = tinyMCE.activeEditor;					
				$('#'+ed.id).val(ed.getContent());

		//var partid = $('#partid'),
		//	formeditname = $('#formeditname'),
		var options = {
				target: textelement_id,
				timeout: 3000
			};
			$(this).ajaxSubmit(options); 
			return false;
		});
