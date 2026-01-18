<?php if(file_exists($__MDL_ROOT."/php/subpage_scripts.php")) include $__MDL_ROOT."/php/subpage_scripts.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_scripts.tpl")) include $__data->include_tpl($section, "subpage_scripts"); ?>
<header:css>
    [lnk:fancybox/jquery.fancybox-1.3.4.css]
</header:css>
<header:js>

    [js:fancybox/jquery.fancybox-1.3.4.js]
    <style>
        #fancybox-close { right: -45px; }   
    </style>
</header:js>
<div class="content blogposts view" itemscope itemtype="http://schema.org/Article">
    <div class="post_author">
        <span class="post_autho_avatar">
        <?php if(($section->parametrs->param11!='')&&($posts->id_user!=0)): ?>
            <a href="<?php echo seMultiDir()."/".$section->parametrs->param11."/" ?>user/<?php echo $posts->id_user ?>/"><img src="<?php echo $avatar ?>" border="0"></a>
        <?php else: ?>
            <img src="<?php echo $avatar ?>" border="0">
        <?php endif; ?>
        </span>
        <span class="post_autho_com">
            <span class="post_autho_titl"><?php echo $section->language->lang002 ?></span>
            <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                <span itemprop="name" class="post_autho_fio">
                    <?php if(($section->parametrs->param11!='')&&($posts->id_user!=0)): ?>
                        <a href="<?php echo seMultiDir()."/".$section->parametrs->param11."/" ?>user/<?php echo $posts->id_user ?>/"><?php echo $fio ?></a>
                    <?php else: ?>
                        <?php echo $fio ?>
                    <?php endif; ?>
                </span>
            </span>
        </span> 
        <span itemprop="datePublished" content="<?php echo $metaDate ?>" class="post_date">
            <?php echo $date ?>
        
        </span>
    </div>
    <div class="post_title" itemprop="name"><?php echo $title ?></div>
    <div class="post_full row" id="blogpost_fuul_text">
        <?php if($section->parametrs->param6=='text'): ?>
            <span class="post_full_txt" itemprop="description">
                <span itemprop="articleBody"><?php echo $full ?></span>
            </span>
        <?php else: ?>
            <?php if($short!=''): ?>
                <div class="post_short_text" itemprop="description">
                    <span itemprop="articleBody"><?php echo $short ?></span>
                </div>
            <?php endif; ?>
            <ul class="nav nav-tabs" id="blogTabs">
                <?php foreach($section->tabname as $name): ?>
                    <li <?php echo $name->active ?>><a href="#tab<?php echo $name->id ?>" data-toggle="tab"><?php echo $name->title ?></a></li>
                
<?php endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php foreach($section->tabtext as $texts): ?>
                    <div class="tab-pane fade <?php echo $texts->active ?>" id="tab<?php echo $texts->id ?>"><?php echo $texts->text ?></div>
                
<?php endforeach; ?>
            </div>            
        <?php endif; ?>
    </div>
    <?php if($isFotoList>0): ?>
            <div class="post_full_img" >
                <ul>
                    <?php foreach($section->fotos as $foto): ?>
                        <li>
                                <?php if($foto->foto_video=='foto'): ?>
                                    <a class='fullgallery' href='<?php echo $foto->url ?>' rel='group'>
                                        <img class="loadImg" src="<?php echo $foto->url ?>" alt="<?php echo $foto->alt ?>" itemprop="image"  />
                                    </a>
                                    <div class="loadImgAlt"><?php echo $foto->alt ?></div>
                                <?php endif; ?>
                                <?php if($foto->foto_video=='video'): ?>
                                    <iframe style="width: 560px; height: 315px;" class="loadVideo" src="https://www.youtube.com/embed/<?php echo $foto->url ?>" frameborder="0" allowfullscreen></iframe>
                                <?php endif; ?>
                        </li>
                    
<?php endforeach; ?>
                </ul>
            </div> 
    <?php endif; ?>
        
    <?php if(($section->parametrs->param22=='Y') && ($isParam==1)): ?>  
        <div class="fotoParams">
            <?php if($model!=''): ?>
                <div class="fotoCommon model">
                    <i class="fotoImage modelImg"></i>
                    <span class="fotoTitle modelTtl"><?php echo $section->language->lang081 ?></span>
                    <span class="fotoText modelTtl"><?php echo $model ?></span>
                </div>
            <?php endif; ?>
            <?php if($vidergka!=''): ?>
                <div class="fotoCommon vidergka">
                    <i class="fotoImage vidergkaImg"></i>
                    <span class="fotoTitle vidergkaTtl"><?php echo $section->language->lang082 ?></span>
                    <span class="fotoText vidergkaTtl"><?php echo $vidergka ?></span>
                </div>
            <?php endif; ?>
            <?php if($diafragma!=''): ?>
                <div class="fotoCommon diafragma">
                    <i class="fotoImage diafragmaImg"></i>
                    <span class="fotoTitle diafragmaTtl"><?php echo $section->language->lang083 ?></span>
                    <span class="fotoText vdiafragmaTtl"><?php echo $diafragma ?></span>
                </div>
            <?php endif; ?>
            <?php if($iso!=''): ?>
                <div class="fotoCommon iso">
                    <i class="fotoImage isoImg"></i>
                    <span class="fotoTitle isoTtl"><?php echo $section->language->lang084 ?></span>
                    <span class="fotoText isoTtl"><?php echo $iso ?></span>
                </div>
            <?php endif; ?>
            <?php if($obectiv!=''): ?>
                <div class="fotoCommon obectiv">
                    <i class="fotoImage obectivImg"></i>
                    <span class="fotoTitle obectivTtl"><?php echo $section->language->lang085 ?></span>
                    <span class="fotoText obectivTtl"><?php echo $obectiv ?></span>
                </div>
            <?php endif; ?>
            <?php if($vspishka!=''): ?>
                <div class="fotoCommon vspishka">
                    <i class="fotoImage vspishkaImg"></i>
                    <span class="fotoTitle vspishkaTtl"><?php echo $section->language->lang086 ?></span>
                    <span class="fotoText vspishkaTtl"><?php echo $vspishka ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="post_com_full">
    <?php if($other_count>0): ?>
        <div class="post_tags post_com">
            <span class="post_tags_title pb_titl"><?php echo $section->language->lang005 ?></span> 
            <span class="post_tags_tsg pb_val">
                <?php foreach($section->tagis as $tgs): ?>  
                    <span class="post_tags_a">
                        <a rel="nofollow" href="<?php echo seMultiDir()."/".$_page."/" ?>tag/<?php echo $tgs->tagurl ?>/"><?php echo $tgs->tags ?></a>
                    </span>
                
<?php endforeach; ?>
            </span>  
        </div> 
    <?php endif; ?>
    <div itemprop="articleSection" class="post_cats post_com">
        <span class="post_cats_title pb_titl"><?php echo $section->language->lang006 ?></span>
        <span class="post_cats_cat pb_val">
            <?php foreach($section->cats as $cat): ?> 
                <span class="post_cats_a">
                    <a rel="nofollow" href="<?php echo seMultiDir()."/".$_page."/" ?>blog/<?php echo $cat->edurl ?>/"><?php echo $cat->edincat ?></a>
                </span>
            
<?php endforeach; ?>
        </span>  
    </div>  
    <div class="post_rating post_com">
            <span class="post_rating_count pb_val" id="blogposts_post_rating_count_<?php echo $id ?>"><?php echo $rating ?></span>
        <?php if($user_id>0): ?>
            <span class="post_rating_common">
                <button class="post_rating_plus" onclick="post_rate('1','<?php echo $id ?>')">+</button>
                <button class="post_rating_minus" onclick="post_rate('-1','<?php echo $id ?>')">-</button>
            </span>
        <?php endif; ?>
    </div>
    <div class="post_favorite post_com">
        <span class="post_favorite_titl pb_titl"><?php echo $section->language->lang008 ?></span>
        <span class="post_favorites_count pb_val" id="blogposts_post_favorites_sign_<?php echo $id ?>"><?php echo $favorite ?></span>
        <?php if($user_id>0): ?>
            <span class="post_favorites_signin" onclick="post_favorite('<?php echo $id ?>');" title="добавить пост в закладки"><?php echo $section->language->lang046 ?></span>
            
        <?php endif; ?>
    </div>
    <?php if($user_id>0): ?>
        <div class="post_subscribe post_com">
            <span class="post_subscribe_signin" onclick="post_setsubsribe('<?php echo $id_subc_user ?>');" title="подписаться на блоггера"><?php echo $section->language->lang047 ?></span>
            
        </div>
    <?php endif; ?>
    <div class="post_views post_com">
        <span class="post_views_titl pb_titl"><?php echo $section->language->lang009 ?></span>
        <span class="post_views_count pb_val"><?php echo $views ?></span>
    </div> 
    <?php if($commenting=="Y"): ?>
        <div class="post_comment post_com">
            <span class="post_comment_a_titl pb_titl"><?php echo $section->language->lang010 ?></span>
            <span class="post_comment_a_count pb_val"><?php echo $comment ?></span> 
        </div>
    <?php endif; ?>
    </div>
    <div class="post_sociallike">

        <script type="text/javascript">
        (function() {
        if (window.pluso)if (typeof window.pluso.start == "function") return;
        var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
        s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
        var h=d[g]('head')[0] || d[g]('body')[0];
        h.appendChild(s);
        })();
        </script>
        <div class="pluso" data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,google" data-background="none;"></div>


    </div>
                               
    <?php if($other_count>0): ?>
        <div class="post_navig">
            <span class="post_navig_title"><?php echo $section->language->lang048 ?></span>
            <?php foreach($section->o_posts as $post): ?> 
                <span class="post_cats_a">
                    <a href="<?php echo seMultiDir()."/".$_page."/" ?>post/<?php echo $post->url ?>/"><?php echo $post->name ?></a>
                </span>
            
<?php endforeach; ?>  
        </div>
    <?php endif; ?>
    <div class="post_back">
        <a href="javascript:void(0);" onclick="window.history.back();" class="backButton"><?php echo $section->language->lang049 ?></a>    
    </div>
    <?php if($commenting=='Y'): ?>
        <div class="comments">
            <?php if($commentlist_count>0): ?>
                <?php if($err!=''): ?>
                    <div class="errorpin "><?php echo $err ?></div>
                <?php endif; ?>
                <div id="comments" class="comments_list"> 
                    <?php foreach($section->comments as $com): ?>
                        <div class="comments_list_comment comment_un<?php echo $com->level ?>">
                            <span class="comments_list_comment_ava">
                                <img src="<?php echo $com->avatar ?>" border="0">
                            </span>
                            <span class="comments_list_comment_block">
                                <span class="comments_list_comment_fio"><?php echo $com->fio ?></span>
                                <span class="comments_list_comment_date"><?php echo $com->date ?></span>
                                <span class="comments_list_comment_text"><?php echo $com->message ?></span>
                                <span class="comments_list_comment_rating">
                                    <span class="comments_list_comment_rating_title"><?php echo $section->language->lang007 ?></span>
                                    <span class="comments_list_comment_rating_count" id="blogposts_comments_list_comment_rating_count_<?php echo $com->id ?>"><?php echo $com->com_rating ?></span>  
                                    <?php if($user_id>0): ?>
                                        <span class="comments_list_comment_rating_common">
                                            <button class="comments_list_comment_rating_plus" onclick="comment_rate('1','<?php echo $id ?>','<?php echo $com->id ?>')">+</button>
                                            <button class="comments_list_commentt_rating_minus" onclick="comment_rate('-1','<?php echo $id ?>','<?php echo $com->id ?>')">-</button>
                                        </span>
                                    <?php endif; ?>
                                </span>
                                <?php if(($user_id>0)||($user_group==3)||(($section->parametrs->param8=='A')&&($user_id==0))): ?>
                                    <span class="comments_list_comment_buttons">
                                        <noindex>
                                            <a rel="nofollow" class="buttonDowload" href="javascript:void(0);" onclick="show_comment_form(<?php echo $com->id ?>);"><?php echo $section->language->lang050 ?></a>
                                            <?php if(($delComment=='Y')&&($user_group==3)): ?>
                                                <form id="delComm<?php echo $com->id ?>" style="margin:0px;" action="" method="post" >
                                                    <a rel="nofollow" class="commentDel" href="javascript:void(0);" onclick="$('#delComm'+<?php echo $com->id ?>).submit();"><?php echo $section->language->lang051 ?></a>
                                                    <input type='hidden' name='delcomments' value='<?php echo $com->id ?>'>
                                                </form>
                                            <?php endif; ?>
                                        </noindex>
                                </span>
                                <div class="linksspisok" id="linksspisok<?php echo $com->id ?>">
                                    
                                </div>
                            <?php endif; ?>
                            </span>
                        </div>
                    
<?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(($user_id>0)||($section->parametrs->param8=='A')): ?>
                <div class="linksspisok"  id="linksspisok0"> 
                    
                </div>   
                                     
            <?php else: ?>
                <div class="no_comm_user"> 
                    <?php echo $section->language->lang052 ?>
                </div>                                     
            <?php endif; ?>            
        </div>
    <?php endif; ?>   
    
    <div id="message" style="dysplay:none;">
        <a href="#top" id="top-link"><?php echo $section->language->lang063 ?></a>
    </div> 
</div>

    <script type="text/javascript">
        var partid = "<?php echo $section->id ?>";            
        
        $(window).load(function(){
            $.ajax({
                type: "POST",
                url: "?ajax<?php echo $section->id ?>_comment_form",
                data: ({id: ""+<?php echo $idlink ?>}),
                async: true,
                success: function(data){
                    $("#linksspisok"+<?php echo $idlink ?>).append(data);
                }
            });                                                                                         
            
            $('#blogpost_fuul_text a.fullgallery, .post_full_img a.fullgallery').fancybox({
                "padding" : 5
            }); 
          
        });
        
        function show_comment_form(id){
            $('#myComment').each(function(){
                $(this).hide();
            });
            $.post("?ajax<?php echo $section->id ?>_show_comment",{com: ""+id},function(data){
                $('#linksspisok'+id).append(data);
            });
        };
        
        function insert_text_cursor(_obj_name, _text) {
            var area=document.getElementsByName(_obj_name).item(0);
            if ((area.selectionStart)||(area.selectionStart=='0')) {
                var p_start=area.selectionStart;
                var p_end=area.selectionEnd;
                area.value=area.value.substring(0,p_start)+_text+area.value.substring(p_end,area.value.length);
            }
            if (document.selection) {
                area.focus();
                sel=document.selection.createRange();
                sel.text=_text;
            }
        }
        
    </script>  

<script type="text/javascript" src="[module_url]engine.js"></script>
          
