function delField(name, val) {
    $.post("?ajax_del_"+name,{name: "" + val}, function(data){
        alert(data);
      //  $('#new_'+name).remove();
        //$("#search_for").prepend($("<option value='for_lot'>Наименование</option>"));
    });
}
