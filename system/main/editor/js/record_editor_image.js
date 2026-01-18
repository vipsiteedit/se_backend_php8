var partid = $("#partid"),
	recid = $("#recid"),
	element_id = ".groupItem#group_"+partid.val()+" #se-edit-record-"+recid.val()+" .se-edit-record-image";

  $('#cancelEditData').click(function(){ 
		$.ajax({
			url: "?on_ajax_execute",
			type: 'POST',
			data: {name: "showrecordimage",value: ""+partid.val()+"_"+recid.val()+""},
			success: function(data){
				$(element_id).html(data);
			}
		});

  }); 

   
  // привязываем событие submit к форме
  $('#editImageForm').submit(function(){ 
	var options = { 
		target: element_id
	};
			$(this).ajaxSubmit(options); 
            return false;
  });