  var partid = $("#partid"),
  options = { 
  	target: ".groupItem#group_"+partid.val()+" .contentImage"
  };

  // привязываем событие submit к форме
  $('#editImageForm').submit(function(){ 
			$(this).ajaxSubmit(options); 
            return false;
  }); 