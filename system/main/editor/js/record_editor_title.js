var partid = $("#editTitleForm #partid"),
	recid = $("#editTitleForm #recid"),
	recelement_id = ".groupItem#group_"+partid.val()+" #se-edit-record-"+recid.val()+" .se-edit-record-title",
	rectitle = $('#editTitleForm #rectitle');
	
	rectitle.focus();
	rectitle.keydown(function(eventObject){          
		if (eventObject.which == 27){
			seLoadBox('', recelement_id, 'showrecordtitle',partid.val()+'_'+recid.val());
			recelement_id = null;
		}
   });   
  // привязываем событие submit к форме
  $('#editTitleForm').submit(function(){ 
	var options = { 
		target: recelement_id
	};
			$(this).ajaxSubmit(options); 
            return false;
  });