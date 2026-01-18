<repeat:reviews name=review>
    <div class="reviewItem" data-id="[review.id]" itemprop="review" itemscope itemtype="http://schema.org/Review">
        <div class="headReview">
            <div class="reviewUser" itemprop="author">[review.user_name]</div>
            <div class="reviewDate">[review.date]<meta itemprop="datePublished" content="[review.date_iso]"></div>
        </div>
        <div class="reviewRating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
            <meta itemprop="ratingValue" content="[review.mark]">
            <meta itemprop="worstRating" content="1">
            <meta itemprop="bestRating" content="5">
            <span class="markValue" title="[review.mark_title]">[review.mark_value]</span>
            <span class="markTitle">[review.mark_title]</span>
            <label class="usetimeLabel">[lang070]</label>
            <span class="usetimeValue">[review.use_time]</span>
            <span class="rateValue [review.rating_type]">[review.rating]</span>
        </div>
        <noempty:review.merits>
            <div class="reviewMerits">
                <label class="meritsLLabel">[lang067]</label>
                <div class="meritsContent" itemprop="reviewBody">
                    [review.merits]
                </div>
            </div>
        </noempty>
        <noempty:review.demerits>
            <div class="reviewDemerits">
                <label class="demeritsLabel">[lang068]</label>
                <div class="demeritsContent" itemprop="reviewBody">
                    [review.demerits]
                </div>
            </div>
        </noempty>
        <div class="reviewComment">
            <label class="commentLabel">[lang069]</label>
            <div class="commentContent" itemprop="reviewBody">
                [review.comment]
            </div>
        </div>
        <div class="reviewVoiting <noempty:[review.disabled]>disabled</noempty>">
            <span class="helpful">[lang081]</span>
            <span class="lnkVote likeReview <noempty:[review.user_like]>selected</noempty>" title="[lang014]">[lang014]</span>
            <span class="countLikes">[review.likes]</span>
            <span class="lnkVote dislikeReview <noempty:[review.user_dislike]>selected</noempty>" title="[lang015]">[lang015]</span>
            <span class="countDislikes">[review.dislikes]</span>
        </div>
    </div>
</repeat:reviews>
