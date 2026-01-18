var partid = $("#partid"),
	recid = $("#recid"),
	element_id = ".groupItem#group_"+partid.val()+" #se-edit-record-"+recid.val()+" .se-edit-record-field";

  // привязываем событие submit к форме
  $('#editFieldForm').submit(function(){ 
	var options = { 
		target: element_id
	};
			$(this).ajaxSubmit(options); 
            return false;
  });