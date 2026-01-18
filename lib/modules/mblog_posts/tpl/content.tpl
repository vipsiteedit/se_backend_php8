<div class="content blogposts" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($newbbs_add_link!=0): ?>
        <input type="button" class="newob buttonSend" value='<?php echo $section->language->lang082 ?>' onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>addpost/';">
        <!-- a class="newob" title="<?php echo $section->language->lang082 ?>" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>"><?php echo $section->language->lang082 ?></a -->
    <?php endif; ?>
    <div class="contentBody" id="contentBody">
        <span class="navagation top"><?php echo $SE_NAVIGATOR ?></span>    
        <?php foreach($section->blogs as $record): ?>  
            <div class="blog-post">
                <div class="blog-post-date">
                    <span class="blog-post-date-day"><?php echo $record->date_add_rus_day ?></span>
                    <span class="blog-post-date-nedel"><?php echo $record->date_add_rus_nedel ?></span>
                    <span class="blog-post-date-god"><?php echo $record->date_add_rus_god ?></span>
                </div>
                <?php if(!empty($record->title)): ?>
                    <h4>
                        <a class="alinks" href="<?php echo $multidir ?>/<?php echo $_page ?>/post/<?php echo $record->link ?>/"><?php echo $record->title ?></a>
                    </h4>
                <?php endif; ?>
                <?php if(!empty($record->short)): ?>
                    <noindex>
                        <div class="blog-post-short"><?php echo $record->short ?></div>
                    </noindex>
                <?php endif; ?>
                <div class="blog-post-footer">
                    <?php if($record->tagcount!=0): ?>
                        <div class="blog-post-tags">
                            <span class="tags_title"><?php echo $section->language->lang054 ?></span> 
                            <?php $__list = 'tags'.$record->id; foreach($section->$__list as $tag): ?>  
                                <a href="<?php echo $multidir ?>/<?php echo $_page ?>/tag/<?php echo $tag->tag ?>/"><?php echo $tag->tag2 ?></a>
                            
<?php endforeach; ?>  
                        </div> 
                    <?php endif; ?>
                    <?php if($record->catcount!=0): ?>  
                        <div class="blog-post-cats">
                            <span class="carts_title"><?php echo $section->language->lang053 ?></span>
                            <?php $__list = 'cat'.$record->id; foreach($section->$__list as $cat): ?> 
                                <a rel="nofollow" href="<?php echo $multidir ?>/<?php echo $_page ?>/blog/<?php echo $cat->edurl ?>/"><?php echo $cat->edincat ?></a>
                            <?php endforeach; ?>  
                        </div>  
                    <?php endif; ?>
                    <?php if(!empty($record->hits)): ?> 
                        <span class="blog_hits">
                            <span class="first"><?php echo $record->hits ?></span>
                            <span class="last"><?php echo $section->language->lang051 ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if(!empty($record->rating)): ?> 
                        <span class="blog_rating">
                            <span class="first"><?php echo $record->rating ?></span>
                            <span class="last"><?php echo $section->language->lang056 ?></span>
                        </span>
                    <?php endif; ?>
                    <div class="links">
                        <?php if(!empty($record->coment)): ?>                
                            <?php if($record->commenting=="yes"): ?>
                                <a href="<?php echo $multidir ?>/<?php echo $_page ?>/post/<?php echo $record->link ?>/#comments" class="blog_comments_count">
                                    <span class="first"><?php echo $record->coment ?></span> 
                                    <span class="last"><?php echo $section->language->lang052 ?></span>
                                </a>
                            <?php endif; ?> 
                        <?php endif; ?>
                        <a href="<?php echo $multidir ?>/<?php echo $_page ?>/post/<?php echo $record->link ?>/" class="blog-post-detal"><?php echo $section->language->lang055 ?></a>
                        <?php if($record->acess==1): ?>
                            <a class="editpost" title="<?php echo $section->language->lang031 ?>" href="<?php echo seMultiDir()."/".$_page."/" ?>editpost/<?php echo $record->id ?>/"><?php echo $section->language->lang030 ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <span class="navagation bottom"><?php echo $SE_NAVIGATOR ?></span>    
    </div>
    
</div>
