  $('#editImageForm').submit(function(){ 
	var options = { 
		target: element_id
	};
			$(this).ajaxSubmit(options); 
            return false;
  });