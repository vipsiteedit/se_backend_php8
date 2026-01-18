var ashop_catalog_picture_execute = function(params){ 
var mshop_catalog_picture_execute = function(params){ 
    var prcInWork = 0;
    var prcWaiting = [];
    var queuePos = 0;
    
    $('.shopGrouppic.part. menu-action').click(function(){
        var id = $(this).data('id'), code = $(this).data('code'), count = $(this).data('count'), level = $(this).data('level');
        SHMenu(id, code, count, level);
        return false;
    });
    
    function nextProcess() {
        ++queuePos;
        if (queuePos < prcWaiting.length) {
            prcWork();
        }
    }
    function SHMenu(id, code, count, level) {
        if (queuePos == prcWaiting.length) {
//console.log("Cleaning queue\r\n");
            queuePos = 0;
            prcInWork = 0;
            prcWaiting = [];      
        }
        prcWaiting.push([id, code, count, level]);
        if (prcInWork == 0) {
            prcInWork = 1;
            prcWork();
        }
    }
    function prcWork() {
        var id = prcWaiting[queuePos][0];
        var code = prcWaiting[queuePos][1];
        var count = prcWaiting[queuePos][2];
        var level = prcWaiting[queuePos][3];
//console.log('Start process #' + queuePos + ": " + id + " " + code + " " + count + " " + level + "\r\n");
        var goToGoods = 1;
        if (params.param4 == -1) {
            goToGoods = 0;
            if ($(".submenu_mu" + id).css('display') == undefined) {
                if (count && (params.param9 == 2)) {
                    goToGoods = 1;
                } else if (params.param13 == 'Y') {
                    $.post('?ajax'+params.part_id+'&loadsub', {id: id, level: level}, function(data){
                        if (data != '') {
                            $(".groupList .menuUnit" + id).html($(".groupList .menuUnit" + id).html() + data);
                            nextProcess();
                        } else {
                            document.location = params.multilink + code + params.url_end;
                        }
                    }, 'html');
                } else {
                    goToGoods = 1;
                }
            } else {
                if ($(".submenu_mu" + id).css('display') == 'none') {
                    $(".submenu_mu" + id).css('display', 'block');
                } else {
                    $(".submenu_mu" + id).css('display', 'none');        
                }
                nextProcess();
            }
        }
        if (goToGoods) {
            document.location = params.multilink + code + params.url_end;
        } 
    }
}
}
