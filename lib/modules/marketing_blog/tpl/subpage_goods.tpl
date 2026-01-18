<div class="post_goods">
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
    <div class="row page_navigator top">
        <?php echo $navigator ?>
    </div>
    <div class="row goodslist">
        <?php foreach($section->blogs as $record): ?>
            <div class="row goods">
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
                <span class="main" data-ref="<?php echo $record->url ?>"  onmouseover="this.style='cursor:pointer;'">
                    <span class="underImg">
                        <img src="<?php echo $record->img ?>">
                    </span>
                    <?php if($record->price!=''): ?>
                        <div class="underPrice" data-ref="<?php echo $record->url ?>">
                            <span class="pri"><?php echo $record->price ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="titl">
                        <a href="<?php echo $thisPageLink ?>post/<?php echo $record->url ?>/" class="show_detal"><?php echo $record->title ?></a>                    
                    </div>
                </span>
                <?php if($record->hit==1): ?>
                    <div class="underGoods" data-ref="<?php echo $record->url ?>"></div>
                <?php endif; ?>
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
    <div class="row page_navigator bottom">
        <?php echo $navigator ?>
    </div>
</div>
