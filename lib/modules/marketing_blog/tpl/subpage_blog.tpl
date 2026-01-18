    <div class="posts">
            
        <?php if($newbbs_add_link!=0): ?>
            <div class="row post_but_area">
                <?php if($noPost['flag']!=1): ?>
                    <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedittext/" ?>" class="newob"><?php echo $section->language->lang001 ?></a>

                <?php else: ?>
                    <span class="no_post_text">
                        <span class="intext"><?php echo $section->language->lang057 ?></span>
                        <span class="time"><?php echo $noPost['endTime'] ?><?php echo $section->language->lang058 ?></span>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="row navagation top"><?php echo $SE_NAVIGATOR ?></div>
        <div class="row postList">
            <?php foreach($section->blogs as $record): ?>  
                <div class="post">
                    <?php if($record->modarationLabel==1): ?>
                        <div class="modarationLine">
                            <span class="moderationLabel"><?php echo $section->language->lang076 ?></span>
                            <?php if($record->modarationButton==1): ?>
                                <span class="moderationButton">
                                    <button data="<?php echo $record->switch ?>" data-source="<?php echo $record->id ?>" class="powerSwitch"><?php echo $record->switch ?></button>
                                </span>                    
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="post_title">
                        <a class="alinks" href="<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/"><?php echo $record->title ?></a>
                    </div>
                    <div class="post_com_inf">
                        <div class="post_date">
                            <span class="post_date_weekday"><?php echo $record->weekday ?></span>
                            <span class="post_date_delimetery">,</span>
                            <span class="post_date_day"><?php echo $record->day ?></span>
                            <span class="post_date_month"><?php echo $record->month ?></span>
                            <span class="post_date_year"><?php echo $record->year ?></span>
                        </div>
                        <div class="post_author">
                            <span class="post_autho_titl"><?php echo $section->language->lang002 ?></span>
                            <span class="post_autho_fio">
                            <?php if(($section->parametrs->param11!='')&&($record->id_user!=0)): ?>
                                <span class="post_autho_fio_txt">
                                    <a href="<?php echo seMultiDir()."/".$section->parametrs->param11."/" ?>user/<?php echo $record->id_user ?>/"><?php echo $record->fio ?></a>
                                </span>
                            <?php else: ?>
                                <span class="post_autho_fio_txt"><?php echo $record->fio ?></span>
                            <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <noindex>
                        <div class="post_short"><?php echo $record->short ?></div>
                        <?php if($record->listimages!=''): ?>
                            <div class="post_img">
                                <?php if($record->listimages_type=='foto'): ?>
                                    <img class="post_img_picture" src="<?php echo $record->listimages ?>" border="0" alt="<?php echo $record->listimages_alt ?>">
                                <?php endif; ?>
                                <?php if($record->listimages_type=='video'): ?>
                                    <iframe style="width: 560px; height: 315px;" class="post_img_video" src="https://www.youtube.com/embed/<?php echo $record->listimages ?>" frameborder="0" allowfullscreen></iframe>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </noindex>
                    <div class="post_show">
                        <a href="<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/" class="post_show_detal"><?php echo $section->language->lang003 ?></a>                    
                    </div>
                    <?php if($record->access==1): ?>
                        <div class="post_edit">    
                                <a rel="nofollow" class="post_edit_detail" title="Редактировать" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedittext/" ?>id/<?php echo $record->id ?>/"><?php echo $section->language->lang004 ?></a>                            
                        </div>
                    <?php endif; ?>
                    <?php if($user_group==3): ?>
                        <div class="post_setting">
                            <noindex>
                                <a href="javascript:void(0);" class="post_setting_detail" rel='nofollow' data-source="<?php echo $record->id ?>">Настройка</a>
                            </noindex>
                        </div>
                    <?php endif; ?>
                    <div class="post_com_block">
                        <?php if($record->tags!=''): ?>
                            <div class="post_tags com_block">
                                <span class="post_tags_title pb_titl"><?php echo $section->language->lang005 ?></span> 
                                <span class="post_tags_area pb_val">
                                    <?php $__list = 'tagis'.$record->id; foreach($section->$__list as $tgs): ?>  
                                        <span class="post_tags_a">
                                            <a rel="nofollow" href="<?php echo seMultiDir()."/".$_page."/" ?>tag/<?php echo $tgs->tagurl ?>/"><?php echo $tgs->tags ?></a>
                                        </span>
                                    
<?php endforeach; ?>
                                </span>  
                            </div> 
                        <?php endif; ?>
                        <div class="post_cats com_block">
                            <span class="post_cats_title pb_titl"><?php echo $section->language->lang006 ?></span>
                            <span class="post_cats_area pb_val">
                                <?php $__list = 'cat'.$record->id; foreach($section->$__list as $cat): ?> 
                                    <span class="post_cats_a">
                                        <a rel="nofollow" href="<?php echo seMultiDir()."/".$_page."/" ?>blog/<?php echo $cat->edurl ?>/"><?php echo $cat->edincat ?></a>
                                    </span>
                                <?php endforeach; ?>  
                            </span>
                        </div>  
                        <div class="post_rating com_block">
                            <span class="post_rating_title pb_titl"><?php echo $section->language->lang007 ?></span>
                            <span class="post_rating_count pb_val" id="blogposts_post_rating_count_<?php echo $record->id ?>"><?php echo $record->rating ?></span>
                            <?php if($user_id>0): ?>
                                <span class="post_rating_com">
                                    <button class="post_rating_plus" onclick="post_rate('1','<?php echo $record->id ?>')" title="нравится">+</button>
                                    <button class="post_rating_minus" onclick="post_rate('-1','<?php echo $record->id ?>')" title="не нравится">-</button>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="post_favorite com_block">
                            <span class="post_favorite_titl pb_titl"><?php echo $section->language->lang008 ?></span>
                            <span class="post_favorites_count pb_val" id="blogposts_post_favorites_sign_<?php echo $record->id ?>"><?php echo $record->favorite ?></span>

                        </div>
                        <div class="post_views com_block">
                            <span class="post_views_titl pb_titl"><?php echo $section->language->lang009 ?></span>
                            <span class="post_views_count pb_val"><?php echo $record->views ?></span>
                        </div>
                    </div>
                        <?php if($record->commenting=="Y"): ?>
                            <div class="post_comment">
                                <a href="<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/#comments" class="post_comment_a">
                                    <span class="post_comment_a_count"><?php echo $section->language->lang010 ?></span>
                                    <span class="post_comment_a_titl"><?php echo $record->comment ?></span> 
                                </a>
                            </div>
                        <?php endif; ?> 
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row navagation bottom"><?php echo $SE_NAVIGATOR ?></div>    
    </div>
