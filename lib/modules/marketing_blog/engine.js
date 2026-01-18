$(document).ready(function(){
//  форма настройки
        $('.post_setting .post_setting_detail').on('click',function(e){
            e.preventDefault();
            var postid = $(this).attr('data-source');
            $.post("?ajax"+partid+"_modal",{id:""+postid},function(data){
                if (data=='NO_ACCESS') {
                    data = 'Ошибка аутентификации пользователя';
                } else if (data == 'NO_CHANGE') {
                    data = 'Ой, что-то случилось';
                }
                $('#show_sets').append(data);
                $('#blogSetting').modal({show: true});
                $('#blogSetting').on('shown.bs.modal', function(e) {
//  изменить рейтинг у поста
                    $('#blogposts_post_rating_change').on('click',function(e){      
                        e.preventDefault(); 
                        changeRateOrView('#blogposts_post_rating_change','post_changeRate');
                    });  
        
//  изменить просмотр у поста
                    $('#blogposts_post_views_change').on('click',function(e){   
                        e.preventDefault();
                        changeRateOrView('#blogposts_post_views_change','post_changeView');
                    });  

//  изменить просмотр у комментария
                    $('.blogposts_post_comment_change').on('click',function(e){   
                        e.preventDefault();
                        id = $(this).attr('data-source');
                        changeRateOrView('#comment_change_'+id,'comment_changeRate');
                    });  
                
//  общая функция для изменения рейтинга и просмотров
                    function changeRateOrView(data, where) {
                        if (confirm("Изменить значение?")) {
                            var original = $(data).attr('data-original');
                            var source = $(data).attr('data-source');
                            if (where == 'comment_changeRate') {
                                data = data.replace('#','#blogposts_post_');   //  нужен для изм. rate комментария
                                var newValue = $(data).val();
                            } else {
                                var newValue = $(data+'_'+source).val();
                            }
                            newValue = (/^(-?\d+)$/.test(newValue)) ? parseInt(newValue) : 0;
                            change = newValue - parseInt(original);
                            if (where == 'post_changeView') {
                                change = (newValue < 0) ? 0 : newValue;
                                newValue = change;
                            }
                            if (newValue != original) {   
                                $.post("?ajax_"+where, {postid: ""+source,correction: ""+change}, function(dat){
                                    if(dat=='OK'){
                                        $(data).attr('data-original', newValue);
                                        //alert($('#blogposts_post_rating_change').attr('data-original', newValue));
                                        alert('Значение изменено с '+original+' на '+newValue);     
                                    } else if(dat=='NO_CHANGE'){
                                        alert('Ой, почему-то не получилось изменить значение');
                                    } else if(dat=='NO_ACCESS'){
                                        alert('Ошибка аутентификации пользователя');
                                    }
                                });
                            } else {
                                alert('Исходное значение совпадает с изменным или не может быть отрицательным');
                            }
                        }    
                    } 

                });
                $('#blogSetting').on('hidden.bs.modal', function(e) {
                    $('#show_sets').empty();
                    document.location.reload(true);
                })
            });
        });

//  смена картинки и текста в информере
        $('.post_info .postlist .post_picture[data=InOut]').each(function(){
            $(this).hover(
                function() {
                    $(this).find('.post_picture_img_area').hide();
                    $(this).find('span.insidetext').show();
                }, function(){
                    $(this).find('.post_picture_img_area').show();
                    $(this).find('span.insidetext').hide();
                }
            );
        });  
        
// переход на страницу товара     
        $('.post_goods').on('click','.main[data-ref], .underPrice[data-ref], .underGoods[data-ref]',function(){
            var lnk = $(this).attr('data-ref');
            window.location = ''+thispage+'post/'+lnk+'/';
        });   
        
//  для работы кнопки НАВЕРХ  
        $(window).scroll(function () {
            var top1 = $('html').scrollTop();
            var top2 = $('body').scrollTop();
            var butt = $('#message');
            if ((top1 > 200) || (top2 > 200)) {
                butt.fadeIn(500);
            } else {
                butt.fadeOut(500);
            }
        });
       
        $('#top-link').click(function(e) {
            e.preventDefault();
            $("html, body").animate({scrollTop: 0}, 400);
        });
        
//  реакция кнопки ВКЛ/ВЫКЛ записи        
        $('.powerSwitch').on('click',function(){
            var removeDiv = this.parentNode.parentElement;
            var source = $(this).attr('data-source');
            var actions = $(this).attr('data');
            $.post("?ajax"+partid+"_switchButton",{postid:""+source, action:""+actions},function(data){
                if(data=='OK'){
                    removeDiv.remove();     
                } else if(data=='NO_CHANGE'){
                    alert('Ой, что-то пошло не так');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        });   
                
});

//  убрать из избранного пост
        function post_unfavorite(id){
            $.post("?ajax"+partid+"_post_unfavorite",{postid: ""+id}, function(data){
                if(data=='OK'){
                    var d = $('#blogposts_post_favorites_sign_'+id);
                    var v = parseInt(d.text()) - 1;
                    d.text(v);     
                } else if(data=='NO_CHANGE'){
                    alert('Пост отсутствует в избранных');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        }

//  добавить в избранное пост
        function post_favorite(id){
            $.post("?ajax"+partid+"_post_favorite",{postid: ""+id}, function(data){
                if(data=='OK'){
                    var d = $('#blogposts_post_favorites_sign_'+id);
                    var v = parseInt(d.text()) + 1;
                    d.text(v);     
                } else if(data=='NO_CHANGE'){
                    alert('Пост уже добавлен в избранное');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        }

//  рейтинг поста
        function post_rate(val, id){
            $.post("?ajax"+partid+"_post_rate",{rate: ""+val, postid: ""+id}, function(data){
                if(data=='OK'){
                    var d = $('#blogposts_post_rating_count_'+id);
                    var v = parseInt(d.text()) + parseInt(val);
                    d.text(v);     
                } else if(data=='NO_CHANGE'){
                    alert('Вы уже голосовали');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        }

//  рейтинг комментария
        function comment_rate(val, post_id, com_id){
            $.post("?ajax"+partid+"_comment_rate",{rate: ""+val, postid: ""+post_id, com: ""+com_id}, function(data){
                if(data=='OK'){
                    var d = $('#blogposts_comments_list_comment_rating_count_'+com_id);
                    var v = parseInt(d.text()) + parseInt(val);
                    d.text(v);     
                } else if(data=='NO_CHANGE'){
                    alert('Вы уже голосовали');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        }

//  подписаться на пользователя
        function post_setsubsribe(id){
            $.post("?ajax"+partid+"_post_setsubsribe",{uid: ""+id}, function(data){
                if(data=='OK'){
                    var d = $('#blogposts_post_favorites_sign_'+id);
                    var v = parseInt(d.text()) + 1;
                    d.text(v);     
                    alert('Вы успешно подписались');
                } else if(data=='NO_CHANGE'){
                    alert('Вы уже подписаны');
                } else if(data=='NO_ACCESS'){
                    alert('Ошибка аутентификации пользователя');
                }
            });
        }
