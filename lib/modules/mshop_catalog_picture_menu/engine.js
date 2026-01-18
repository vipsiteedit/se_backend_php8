var prcInWork = 0;
var prcWaiting = [];
var queuePos = 0;
var idGroupMenuId = 0; 


function SubMenu(ids, code, count, levels) {
    $.ajax({
        type: "POST",
        url: '?loadsub',
        async: false,
        data: {id: ids, level: levels},
        success : function(data) {
            if (data != '' && idGroupMenuId != ids) {
                           if(!$(".shopGrouppicMenu .groupList .menuUnit" + ids).data("check")) { 
                                $(".shopGrouppicMenu .groupList .menuUnit" + ids).append(data);
                           }
                           $(".shopGrouppicMenu .groupList .menuUnit" + ids).attr("data-check","true");   
            }
        },
        dataType: 'html'
    });
}



$(document).ready(function(){ 
        $(".shopGrouppicMenu .groupList .sub-sub").hide();
        $(".shopGrouppicMenu .groupList .menuUnit").hover(
            function(){
            
                level = $(this).data("level");
                id = $(this).data("id");   
                code = $(this).data("code");
                mycount = $(this).data("mycount");
                
                if (!$(".shopGrouppicMenu .groupList .submenu_mu" + id).hasClass("sub-sub")) {
                    SubMenu(id, code, mycount, level);
                }   
                $(".shopGrouppicMenu .groupList .submenu_mu" + id).show();
           
                
            },
            function(){
                id = $(this).data("id");  
                //if (!$(".shopGrouppicMenu .groupList .submenu_mu" + id).hasClass("sub-sub")) {
                //    SubMenu(id, code, mycount, level);                     
                //}
                $(".shopGrouppicMenu .groupList .submenu_mu" + id).hide();
                
            }
        );                
});
