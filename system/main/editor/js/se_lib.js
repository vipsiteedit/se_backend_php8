  function seLoadBox(section, id, name, value) {
  $.ajax({
	url: "/?on_ajax_execute"+section+"",
        context: document.body,
        type: 'POST',
        data: {name: ""+name+"",value: ""+value+""},
        success: function(data){
            //alert(data);
            $(id).html(data);
	}
    });
    //$(id).post("?on_ajax_execute"+section+"", {name: ""+name+"",value: ""+value+""});
  }