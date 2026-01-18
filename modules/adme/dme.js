var dme_execute = function(params){
    if(params.design == 'enable'){
        $('body').attr('id','dme');    
    } 
    $('head').after($('#infobardm'));
    $('.dmu.part'+params.partid).remove();
    $('#infobardm').css('display','block');
    if (params.image == 'off') {
       $("img").addClass("none");
	   $("a.dmdisableimage").addClass("dmimageActive");
	   $("a.dmenableimage").removeClass("dmimageActive");
	   $('div,span,body,table,td,tr,a,li,ul').addClass('dme-no-image');
    } else if(params.image == 'on') {
	   $("a.dmenableimage").addClass("dmimageActive");
       $('div,span,body,table,td,tr,a,li,ul').addClass('dme-is-image');
    }
    if (params.size == 1){
        $('body').addClass('dme-size-1');
        $("body, div, p, span, h3, a, table, td, tr, tbody, thead, header, footer, section, li, ul").addClass("dme-size-1-class");
        $("h1").addClass("dme-size-1-h1");
        $("h2").addClass("dme-size-1-h2");
        $("#infobardm, .dmchangea1, .dmchangea2, .dmchangea3, .dmdisableimage, .dmenableimage, .dmcolor1, .dmcolor2, .dmcolor3, .dmcolor4").addClass("dme-size-1-panel");
        $('.dmchangea1').addClass('dme-change-a1-active');
    }
    if (params.size == 2){
        $('body').addClass('dme-size-2');
	   //делаем все в 27 пикселях
        $("body, div, p, span, h3, a, table, td, tr, tbody, thead, header, footer, section, li, ul").addClass("dme-size-2-class");
        $("h1").addClass("dme-size-2-h1");
        $("h2").addClass("dme-size-2-h2");
        $("#infobardm, .dmchangea1, .dmchangea2, .dmchangea3, .dmdisableimage, .dmenableimage, .dmcolor1, .dmcolor2, .dmcolor3, .dmcolor4").addClass("dme-size-2-panel");
        $('.dmchangea2').addClass('dme-change-a2-active');
    }
    if (params.size == 3){
        $('body').addClass('dme-size-3');
        $("body, div, p, span, h3, a, table, td, tr, tbody, thead, header, footer, section, li, ul").addClass("dme-size-3-class");
        $("h1").addClass("dme-size-3-h1");
        $("h2").addClass("dme-size-3-h2");
        $("#infobardm, .dmchangea1, .dmchangea2, .dmchangea3, .dmdisableimage, .dmenableimage, .dmcolor1, .dmcolor2, .dmcolor3, .dmcolor4").addClass("dme-size-3-panel");
        $('.dmchangea3').addClass('dme-change-a3-active');
    }  
    if(params.style == 1){
        $('body').addClass('dme-color-1');        
    }
    if(params.style == 2){
        $('body').addClass('dme-color-2');        
    }
    if(params.style == 3){
        $('body').addClass('dme-color-3');        
    }
}
