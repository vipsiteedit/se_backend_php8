var partid = $("#editPartSmileForm #partid"),
	titleelement_id = ".groupItem#group_"+partid.val()+" .contentTitle",
	parttitle = $('#editPartSmileForm #parttitle');
	parttitle.focus();
	$(document).keydown(function(eventObject){          
		if ((eventObject.which == 27) && (partid.val() != unsigned)){
		$.ajax({
			url: "?on_ajax_execute",
			type: 'POST',
			data: {name: "showsectiontitle",value: ""+partid.val()+""},
			success: function(data){
				$(titleelement_id).html(data);
			}
			//error: function(xhr, er_type) { alert('Ошибка: ' + er_type); }
		});
	
	//		seLoadBox('', titleelement_id, 'showsectiontitle',partid.val());
		}
   });
	
  // привязываем событие submit к форме  
  //".groupItem#group_"+partid.val()+" ."+formeditname.val()
  
  		$('#editPartSmileForm').submit(function() { 
		//var partid = $('#partid'),
		//	formeditname = $('#formeditname'),
		var options = {
				target: titleelement_id,
				timeout: 3000
			};
			$(this).ajaxSubmit(options); 
			return false;
		});
