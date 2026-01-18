<?php foreach($section->reviews as $review): ?>
    <div class="reviewItem" data-id="<?php echo $review->id ?>" itemprop="review" itemscope itemtype="http://schema.org/Review">
        <div class="headReview">
            <div class="reviewUser" itemprop="author"><?php echo $review->user_fullname ?></div>
            <div class="reviewDate"><?php echo $review->date ?><meta itemprop="datePublished" content="<?php echo $review->date_iso ?>"></div>
        </div>
        <div class="reviewRating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
            <meta itemprop="ratingValue" content="<?php echo $review->mark ?>">
            <meta itemprop="worstRating" content="1">
            <meta itemprop="bestRating" content="5">
            <span class="markValue" title="<?php echo $review->mark_title ?>"><?php echo $review->mark_value ?></span>
            <span class="markTitle"><?php echo $review->mark_title ?></span>
            <label class="usetimeLabel"><?php echo $section->language->lang070 ?></label>
            <span class="usetimeValue"><?php echo $review->use_time ?></span>
            <span class="rateValue <?php echo $review->rating_type ?>"><?php echo $review->rating ?></span>
        </div>
        <?php if(!empty($review->merits)): ?>
            <div class="reviewMerits">
                <label class="meritsLLabel"><?php echo $section->language->lang067 ?></label>
                <div class="meritsContent" itemprop="reviewBody">
                    <?php echo $review->merits ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($review->demerits)): ?>
            <div class="reviewDemerits">
                <label class="demeritsLabel"><?php echo $section->language->lang068 ?></label>
                <div class="demeritsContent" itemprop="reviewBody">
                    <?php echo $review->demerits ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="reviewComment">
            <label class="commentLabel"><?php echo $section->language->lang069 ?></label>
            <div class="commentContent" itemprop="reviewBody">
                <?php echo $review->comment ?>
            </div>
        </div>
        <div class="reviewVoiting <?php if(!empty($review->disabled)): ?>disabled<?php endif; ?>">
            <span class="helpful"><?php echo $section->language->lang081 ?></span>
            <span class="lnkVote likeReview <?php if(!empty($review->user_like)): ?>selected<?php endif; ?>" title="<?php echo $section->language->lang014 ?>"><?php echo $section->language->lang014 ?></span>
            <span class="countLikes"><?php echo $review->likes ?></span>
            <span class="lnkVote dislikeReview <?php if(!empty($review->user_dislike)): ?>selected<?php endif; ?>" title="<?php echo $section->language->lang015 ?>"><?php echo $section->language->lang015 ?></span>
            <span class="countDislikes"><?php echo $review->dislikes ?></span>
        </div>
    </div>

<?php endforeach; ?>
