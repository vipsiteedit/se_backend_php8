$(document).ready(function(){
  function setDisplayOption(toggleObject, cookieName, toggleImg1, toggleImg2) { 
    if (($.cookie(cookieName) == 0) || ($.cookie(cookieName) == null)) { 
      $(toggleObject).css("display", "none");
      $(toggleImg1).css("display", "inline-block");
      $(toggleImg2).css("display", "none");
    } else { 
      $(toggleObject).css("display", "block");
      $(toggleImg1).css("display", "none");
      $(toggleImg2).css("display", "inline-block");
    } 
  }
  function addToggleWithCookie(toggleLink, toggleObject, cookieName, toggleImg1, toggleImg2){
    var cookieValue;
    setDisplayOption(toggleObject, cookieName, toggleImg1, toggleImg2);
    if(toggleObject == "#block_tab1_price"){
        $("#block_tab1_price").css("display","inline-block");
        $("#img2_tab1_price").css("display","inline-block");
        $("#img1_tab1_price").css("display","none");  
    }
    $(toggleLink).click(function() {
      if ($.cookie(cookieName) == null) {
        cookieValue = 1;
      } else {
        cookieValue = Math.abs($.cookie(cookieName) - 1);
      }
      $.cookie(cookieName, cookieValue);
      $(toggleObject).toggle();
      $(toggleImg1).toggle();
      $(toggleImg2).toggle();
    });
  }
    
    addToggleWithCookie('#show_link_price', '#block_tab1_price', 'cookie1_price', '#img1_tab1_price', '#img2_tab1_price');
    
    var arrP = {'0':'name', 
    '1':'discount', '2':'manufacturer',
    '3':'article', '4':'code',
    '5':'note', '6':'text',
    '7':'presence_count', '8':'price_opt',
    '9':'price_opt_corp', '10':'measure',
    '11':'volume', '12':'weight',
    '13':'flag_new', '14':'flag_hit',
    '15':'group' };
      
    $.each(arrP, function(i,val){
        addToggleWithCookie('#show_link_'+val, '#block_tab1_'+val, 'cookie1_'+val, '#img1_tab1_'+val, '#img2_tab1_'+val);
    });
    
    $.each(arrP, function(i,val){
        addToggleWithCookie('#show_link_'+val, '#block_tab2_'+val, 'cookie2_'+val, '#img1_tab2_'+val, '#img2_tab2_'+val);
    });
    
    for(i='0'; i<'10000'; i++){
        if($('#show_link_param_'+i).length){
            addToggleWithCookie('#show_link_param_'+i, '#block_tab2_param_'+i, 'cookie2p_'+i, '#img1_tab2_param_'+i, '#img2_tab2_param_'+i);
        }
    }
    
    for(i='0'; i<'10000'; i++){
        if($('#show_link_param_'+i).length){
            addToggleWithCookie('#show_link_param_'+i, '#block_tab1_param_'+i, 'cookie1p_'+i, '#img1_tab1_param_'+i, '#img2_tab1_param_'+i);
        }
    }
});
$(document).ready(function(){
    if (($.cookie("cookie_dop") == 0) || ($.cookie("cookie_dop") == null)) { 
      $("#dopParam").css("display", "none");
      $("#showB").css("display", "block");
      $("#hideB").css("display", "none");
    } else { 
      $("#dopParam").css("display", "block");
      $("#showB").css("display", "none");
      $("#hideB").css("display", "block");
    }   
    var cookieValue;
    $("#showB").click(function() {
      if ($.cookie("cookie_dop") == null) {
        cookieValue = 1;
      } else {
        cookieValue = Math.abs($.cookie("cookie_dop") - 1);
      }
      $.cookie("cookie_dop", cookieValue);
      $("#dopParam").css("display","block");
      $("#hideB").css("display","block");
      $("#showB").css("display","none");
    });
    $("#hideB").click(function() {
      if ($.cookie("cookie_dop") == null) {
        cookieValue = 1;
      } else {
        cookieValue = Math.abs($.cookie("cookie_dop") - 1);
      }
      $.cookie("cookie_dop", cookieValue);
      $("#dopParam").css("display","none");
      $("#hideB").css("display","none");
      $("#showB").css("display","block");
    });
});
