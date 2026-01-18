function loadParam(razdel, iparam, id_price,  value) {
    $('#p'+(iparam-1)+'_'+id_price+'dots')[0].style.display="inline";        
    $.post("?jquery"+razdel,{'iparam': iparam,'idprice': id_price,'value': value}, 
    function(data){
        var d = data.toString().split('|');
        if (d[0]!='') { 
             $('#prm_'+razdel+'_'+id_price).html(d[0]);
        }
        $('#price_'+razdel+'_'+id_price).html(d[1]);
        $('#count_'+razdel+'_'+id_price).html(d[2]);
        $('#old_price_'+razdel+'_'+id_price).html(d[3]); 
        
        if (d[4]) {
            $('.addcart_'+razdel+'_'+id_price)[0].style.display="inline !important";
        } else {
            $('.addcart_'+razdel+'_'+id_price)[0].style.display="none !important";
        }
        
        $('#p'+(iparam-1)+'_'+id_price+'dots')[0].style.display="none !important";
    }); 
}

function loadCompare(razdel, compare, id_price) {
   // alert();
    $.post("?ajax"+razdel,{'compare': compare, 'idprice': id_price},
    function(data){
        var div =  $('.CompareView_Count');
    //    if (d[0]!='') {
      if(div != '') {
           $(div).html(data);
      } 
    }    
 )}
