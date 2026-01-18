<h3 class="titleHead" id="reviews">
    <span class=""><?php echo $section->language->lang060 ?></span>
    <span class="countReviews" style="color:grey;"><?php echo $count_reviews ?></span>
</h3>
<div class="content">                    
<div class="reviews">
    <?php if($user_group!=0): ?>
        <?php if($user_review!=''): ?>
            <div class="msgReviewed"><?php echo $section->language->lang088 ?></div>
        <?php else: ?>
        <div class="msgSuccess" style="display:none;"><?php echo $section->language->lang089 ?></div>
        <a class="linkShow" href="javascript:void(0)"><?php echo $section->language->lang061 ?></a>
        <div class="addReview" style="display:none;">
            <form style="margin:0px;" method="post" action="">
                <div class="addMark">
                    <label class="markLabel"><span class="star">*</span><?php echo $section->language->lang083 ?></label>
                    <div class="blockEditMark">
                        <input type="hidden" required>
                        <label class="markItem" title="<?php echo $section->language->lang062 ?>">
                            <input type="radio" name="review[mark]" value="1" style="display:none;">
                            <span style="display:none;">1</span>
                        </label><label class="markItem" title="<?php echo $section->language->lang063 ?>">
                            <input type="radio" name="review[mark]" value="2" style="display:none;">
                            <span style="display:none;">2</span>
                        </label><label class="markItem" title="<?php echo $section->language->lang064 ?>">
                            <input type="radio" name="review[mark]" value="3" style="display:none;">
                            <span style="display:none;">3</span>
                        </label><label class="markItem" title="<?php echo $section->language->lang065 ?>">
                            <input type="radio" name="review[mark]" value="4" style="display:none;">
                            <span style="display:none;">4</span>
                        </label><label class="markItem" title="<?php echo $section->language->lang066 ?>">
                            <input type="radio" name="review[mark]" value="5" style="display:none;">
                            <span style="display:none;">5</span>
                        </label>
                        <span class="markTitle"></span>
                    </div>
                </div>
                <div class="addMerits">
                    <label for="merits"><?php echo $section->language->lang067 ?></label>
                    <textarea id="merits" name="review[merits]" placeholder="<?php echo $section->language->lang071 ?>"></textarea>
                </div>
                <div class="addDemerits">
                    <label for="demerits"><?php echo $section->language->lang068 ?></label>
                    <textarea id="demerits" name="review[demerits]" placeholder="<?php echo $section->language->lang072 ?>"></textarea>
                </div>
                <div class="addComment">
                    <label for="comment"><span class="star">*</span><?php echo $section->language->lang069 ?></label>
                    <textarea id="comment" name="review[comment]" placeholder="<?php echo $section->language->lang073 ?>" required></textarea>
                </div>
                <div class="addUsetime">
                    <label for="usetime"><span class="star">*</span><?php echo $section->language->lang070 ?></label>
                    <select id="usetime" name="review[usetime]" required>
                        <option value="1"><?php echo $section->language->lang074 ?></option>
                        <option value="2"><?php echo $section->language->lang075 ?></option>
                        <option value="3"><?php echo $section->language->lang076 ?></option>
                    </select>
                </div>
                <div class="msgRequired"><?php echo $section->language->lang077 ?></div>
                <div class="blockButton">
                    <button class="buttonSend btnAdd"><span><?php echo $section->language->lang078 ?></span></button>
                    <a class="linkCancel" href="javascript:void(0)"><?php echo $section->language->lang079 ?></a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="msgAuth onShowLogin" data-target="reviews"><?php echo $section->language->lang080 ?></div>
    <?php endif; ?>
    
    <?php if($reviews!=''): ?>
        <div class="sortReviews">
            <label class="sortLabel"><?php echo $section->language->lang084 ?></label>
            <!--noindex-->
            <?php foreach($section->reviews_sort as $sort): ?>
                <a class="sortField<?php if(!empty($sort->selected)): ?> selected<?php endif; ?>" href="<?php echo $sort->link ?>" data-sort="<?php echo $sort->field ?>" rel="nofollow"><?php echo $sort->name ?><?php echo $sort->direction ?></a>
            
<?php endforeach; ?>
            <!--/noindex-->
        </div>
        <div class="reviewsList">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_reviewslist.php")) include $__MDL_ROOT."/php/subpage_reviewslist.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_reviewslist.tpl")) include $__data->include_tpl($section, "subpage_reviewslist"); ?>
        </div>
        <?php if($count_reviews > $count_visible): ?>
            <div class="moreReviews" data-offset="<?php echo $count_visible ?>">
                <button class="buttonSend btnShowNext">
                    <span><?php echo $section->language->lang092 ?></span>
                    (<span class="countNext"><?php echo $count_next ?></span>)
                </button>
                <button class="buttonSend btnShowAll">
                    <span><?php echo $section->language->lang090 ?></span>
                    (<span class="countReviews"><?php echo $count_reviews ?></span>)
                </button>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="msgNotReviews"><?php echo $section->language->lang082 ?></div>
    <?php endif; ?>
</div>
</div>  
