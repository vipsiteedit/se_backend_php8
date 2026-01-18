//всплывающее окно
function createLBox(txtImage, txtOf, url_modul){    
	$("#photo a").lightBox({             
		txtImage:'&nbsp;'+txtImage,
		txtOf: txtOf,
		overlayOpacity: 0.6, 
		fixedNavigation:true, 
		imageLoading: url_modul+'lightbox-ico-loading.gif', 
		imageBtnPrev: url_modul+'foto_arrow_left.png', 
		imageBtnNext: url_modul+'foto_arrow_right.png', 
		imageBtnClose: url_modul+'foto_close.gif', 
		imageBlank: url_modul+'lightbox-blank.gif' 
	});
}

//изменился доп параметр
function loadParam(razdel, iparam, id_price,  value, types, textimage, textof, url_modul) {
    $('#p'+(iparam-1)+'_'+id_price+'dots')[0].style.display="inline";        
    $.post("?jquery"+razdel,{'iparam': iparam,'idprice': id_price,'value': value,'ttypes': types}, 
    function(data){         
        var d = data.toString().split('|');     
        if (d[0]!='') { 
             $('#prm_'+razdel+'_'+id_price).html(d[0]);
        }
        $('#price_'+razdel+'_'+id_price).html(d[1]);
        $('#count_'+razdel+'_'+id_price).html(d[2]);
        $('#old_price_'+razdel+'_'+id_price).html(d[3]); 
        
        if(d[4]==1){
            $('.addcart_'+razdel+'_'+id_price+'').css("display", "inline");
        }
        if(d[4]==0){
            $('.addcart_'+razdel+'_'+id_price+'').css("display", "none");
        }
        
        if (d[5]&&(types==1)) {               
            $('.goodsDetail .goodsLinkPhoto').html(d[9]);
            $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
            createLBox(textimage, textof, '/'+url_modul+'/'); 
        }
        
        $('#p'+(iparam-1)+'_'+id_price+'dots')[0].style.display="none";
    }); 
}

function loadCompare(id_prices, compares) {
//    alert('OK');
    $.post("?ajax_compare",{compare: ""+compares, idprice: id_prices}, function(){
//        alert(location.href);
        window.location.reload(true);
    });
  //  alert('NO');
}
