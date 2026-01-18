function loadElem(name, value){
        $("#srch_for_load")[0].style.display="inline";
        $.post("?ajax_click",{name: "" + name, value: "" + value}, function(data){
            $(data).insertAfter("#first");
            $("#search_for :selected").val();
          //  text_p = $("#search_for :selected").html();
          //  document.getElementById('title_elem1').innerHTML = text_p;
            $("#search_for :selected").remove();
            $("#srch_for_load")[0].style.display="none";    
        }    
        ); 
}
