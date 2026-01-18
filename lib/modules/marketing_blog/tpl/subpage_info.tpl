<div class="post_info">
    <?php if($isUser!=''): ?>
        <div class="row user">
            <span class="user_avatar">
                <img class="user_avatar_img" border="0" src="<?php if($user['avatar']!=''): ?>/public/avatars/<?php echo $user['avatar'] ?><?php else: ?>[module_url]no_foto.png<?php endif; ?>">
            </span>
            <span class="user_fio"><?php echo $user['fio'] ?></span>
            <span class="user_point"><?php echo $user['point'] ?></span>
            <span class="user_url"><?php echo $user['social_url'] ?></span>
            <?php if($tosubsc==1): ?>
                <span class="user_but">
                    <input type="button" class="buttonSend" value="<?php echo $section->language->lang011 ?>" title="Добавить блоггера в закладки" onclick="post_setsubsribe('<?php echo $id_user ?>');">
                </span>
            <?php endif; ?>
            <span class="user_static">
                <span class="user_view">
                    <span class="user_view_count"><?php echo $user['view'] ?></span>
                    <span class="user_view_title"><?php echo $section->language->lang012 ?></span>
                </span>
                <span class="user_comment">
                    <span class="user_comment_count"><?php echo $user['comment'] ?></span>
                    <span class="user_comment_title"><?php echo $section->language->lang013 ?></span>
                </span>
                <span class="user_favorite">
                    <span class="user_favorite_count"><?php echo $user['favors'] ?></span>
                    <span class="user_favorite_title"><?php echo $section->language->lang014 ?></span>
                </span>
            </span>
        </div>
    <?php endif; ?>
    <div class="row navigator">
        <ul class="nav nav-tabs">
            <li <?php if($postactive!=''): ?>class="active"<?php endif; ?>>
                <a class="navigator_post " onclick="document.location.href='?tab=bypost'"><?php echo $section->language->lang015 ?></a>
            </li>
            <li <?php if($likeactive!=''): ?>class="active"<?php endif; ?>>
                <a class="navigator_favorite "  onclick="document.location.href='?tab=bylike'"><?php echo $section->language->lang016 ?></a>
            </li>
            <li <?php if($comactive!=''): ?>class="active"<?php endif; ?>>
                <a class="navigator_comment " onclick="document.location.href='?tab=bycomment'"><?php echo $section->language->lang017 ?></a>
            </li>
        </ul>
    </div>
    <?php if($newbbs_add_link!=0): ?>
        <div class="row post_but_area">
            <?php if($noPost['flag']!=1): ?>
                <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedittext/" ?>" class="newob btn btn-prymary btn-sm"><?php echo $section->language->lang001 ?></a>

            <?php else: ?>
                <span class="no_post_text">
                        <span class="intext"><?php echo $section->language->lang057 ?></span>
                        <span class="time"><?php echo $noPost['endTime'] ?><?php echo $section->language->lang058 ?></span>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>                                                                                                                                       
    <div class="row postlist">
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
                <?php if($record->picture_src!=''): ?>
                    <span class="post_picture" data="InOut"  onmouseover="this.style='cursor:pointer;'" onclick="document.location.href='<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/'">
                        <span class="post_picture_img_area">
                            <img class="post_picture_img" border="0" src="<?php echo $record->picture_src ?>">
                        </span>
                        <span class="insidetext" style="display:none;">
                            <span class="post_picture_titl"><?php echo $record->title ?></span>
                            <span class="post_picture_txt"><?php echo $record->short ?></span>
                        </span>
                    </span>
                <?php else: ?>
                    <span class="post_picture"  onmouseover="this.style='cursor:pointer;'" onclick="document.location.href='<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/'">
                        <span class="insidetext" <?php if($record->picture_src!=''): ?>style="display:none;"<?php endif; ?>>
                            <span class="post_picture_titl"><?php echo $record->title ?></span>
                            <span class="post_picture_txt"><?php echo $record->short ?></span>
                        </span>
                    </span>
                <?php endif; ?>
                <span class="post_common">
                    <span class="post_views">
                        <span class="post_views_titl"><?php echo $section->language->lang009 ?></span>
                        <span class="post_views_count"><?php echo $record->views ?></span>
                    </span>
                    <?php if($record->commenting=="Y"): ?>
                        <span class="post_comment">
                            <span class="post_comment_a_titl"><?php echo $section->language->lang010 ?></span> 
                            <span class="post_comment_a_count"><?php echo $record->comment ?></span>
                        </span>
                    <?php endif; ?> 
                    <span class="post_favorite">
                        <span class="post_favorite_titl"><?php echo $section->language->lang008 ?></span>
                        <span class="post_favorites_count"><?php echo $record->favorite ?></span>
                    </span>
                </span>
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
            </div>
        
<?php endforeach; ?> 
    </div>
    <div class="row page_navigator">
        <?php echo $SE_NAVIGATOR ?>
    </div>
</div>
