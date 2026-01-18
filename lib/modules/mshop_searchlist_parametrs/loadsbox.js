function loadSBox(id, name, value) {  
        $("#sl_dots")[0].style.display="inline";
        setTimeout(function(){
                        $.post("?ajax{$razdel}",{name: "" + name + "", value: "" + value + ""},
                            function(data){
                                $("#"+id).html(data); 
                                $("#sl_dots")[0].style.display="none";
                            }
                  ); 
        }, 1);                                                                                            
}
