<div class="content top_topics" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>>
        <span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="workspace row">
        <?php foreach($section->topics as $topic): ?>
            <div class="top">
                <div class="title">
                <?php if(strval($section->parametrs->param2)!=''): ?>
                    <a href="<?php echo seMultiDir()."/".$section->parametrs->param2."/" ?>post/<?php echo $topic->url ?>/"><?php echo $topic->title ?></a>
                <?php else: ?>
                    <?php echo $topic->title ?>
                <?php endif; ?>
                </div>
                <div class="info">
                    <span class="rating"><?php echo $topic->rating ?></span>
                    <span class="txt">балла</span>
                    <span class="delimeter">/</span>
                    <span class="txt1">автор:</span>
                    <span class="author">
                        <?php if(($topic->id_user!=0)&&(strval($section->parametrs->param2)!='')): ?>
                            <a href="<?php echo seMultiDir()."/".$section->parametrs->param2."/" ?>user/<?php echo $topic->id_user ?>/"><?php echo $topic->fio ?></a>
                        <?php else: ?>
                            <?php echo $topic->fio ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        
<?php endforeach; ?>
    </div>
</div>
