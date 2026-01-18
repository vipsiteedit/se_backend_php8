//  подписаться
        function setSubscribe(id){
            $.post("?ajax_setSubscribe",{id_bl: ""+id}, function(data){
                if(data=='OK'){
                    var d = $('#topblogger_blogger_subscribe_count_'+id);
                    var v = parseInt(d.text()) + 1;
                    d.text(v);     
                } else if(data=='NO_CHANGE'){
                    alert('Вы уже подписаны');
                } else if(data=='NO_ACCESS'){
                    alert('Вы не авторизованы на сайте');
                }
            });
        }
