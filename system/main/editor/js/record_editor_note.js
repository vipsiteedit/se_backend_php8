var fname = 'note';
var partid = $("#partid"),
	recid = $("#recid"),
	element_id = ".groupItem#group_"+partid.val()+" #se-edit-record-"+recid.val()+" .se-edit-record-"+fname;
	$(document).keydown(function(eventObject){          
		if (eventObject.which == 27){
			seLoadBox('', element_id, 'showrecordnote',partid.val()+"_"+recid.val());
			element_id = null;
		}
   });

  editor_tinymce(200);
  $('#cancelEditData').click(function(){ 
			seLoadBox('', element_id, 'showrecordnote',partid.val()+"_"+recid.val());
			element_id = null;
/*		$.ajax({
			url: "?on_ajax_execute",
			type: 'POST',
			data: {name: "showrecord"+fname+"",value: ""+partid.val()+"_"+recid.val()+""},
			success: function(data){
				$(element_id).html(data);
			}
		});
*/
  }); 

   
  // привязываем событие submit к форме
  $('#editTextForm').submit(function(){ 
	var options = { 
		target: element_id,
		beforeSubmit: showRequestPart, // функция, вызываемая перед передачей 
		success: showResponsePart, // функция, вызываемая при получении ответа
		timeout: 3000 // тайм-аут
	}, ed = tinyMCE.activeEditor;					
	$('#'+ed.id).val(ed.getContent());
	$(this).ajaxSubmit(options); 
    return false;
  });
	
	function showRequestPart(formData, jqForm, options) { 
		var queryString = $.param(formData);					
			return true; 
	} 
 
		// вызов после получения ответа 
	function showResponsePart(responseText, statusText)  {
		var dialogmodal = $('#dialog-modal-part');
		if (dialogmodal != null) dialogmodal.remove();
	} 			
  