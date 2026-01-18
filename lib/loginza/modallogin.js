$(document).ready(function() {
    var $login = $(".onShowLogin");
    for (i=0; i < $login.length; i++){
       var text = $($login[i]).html(), targ = $($login[i]).data('target');
       console.log(text);
       $($login[i]).html('<a href="/system/main/login.php?target='+targ+'" class="se-modal-login">'+text+'</a>');
    }
    $('a.se-modal-login').fancybox({width:310, height:305,autoSize: false, live:false, type: 'iframe'});
});