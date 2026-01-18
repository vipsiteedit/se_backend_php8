var prcInWork = 0;
var url_SHMenu = '';
var type_SHMenu = -1;
var gr_SHMenu = 1;
var pre_SHMenu = 1;

var prcWaiting = [];
var queuePos = 0;
    //url, param4, param9, param13
    function setSHMenuParam(url, type, gr, pre) {
       url_SHMenu = url;
       type_SHMenu = type;
       gr_SHMenu = gr;
       pre_SHMenu = pre;
    }
    
    function nextProcess() {
        ++queuePos;
        if (queuePos < prcWaiting.length) {
            prcWork();
        }
    }
    function SHMenu(id, code, count, level) {
        if (queuePos == prcWaiting.length) {
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
        if (type_SHMenu == -1) {
            goToGoods = 0;
            if ($(".submenu_mu" + id).css('display') == undefined) {
                if (count && (gr_SHMenu == 2)) {
                    goToGoods = 1;
                } else if (pre_SHMenu == 'Y') {
                    $.post('?loadsub', {id: id, level: level}, function(data){
                        if (data != '') {
//console.log("Process #" + queuePos + " ended\r\n");
                            $(".groupList .menuUnit" + id).html($(".groupList .menuUnit" + id).html() + data);
                            nextProcess();
                        } else {
                            document.location = url_SHMenu  + "/cat/" + code + "/";
                        }
                    });
                } else {
                    goToGoods = 1;
                }
            } else {
//console.log("Process #" + queuePos + " ended\r\n");
                if ($(".submenu_mu" + id).css('display') == 'none') {
                    $(".submenu_mu" + id).css('display', 'block');
                } else {
                    $(".submenu_mu" + id).css('display', 'none');        
                }
                nextProcess();
            }
        }
        if (goToGoods) {
            document.location = url_SHMenu + "/cat/" + code + "/";
        } 
    }
