<h3 class="titleHead" id="reviews">
    <span class="">[lang060]</span>
    <span class="countReviews">{$count_reviews}</span>
</h3>
<div class="content">                    
<div class="reviews">
    <if:{$user_group}!=0>
        <if:{$user_review}!=''>
            <div class="msgReviewed">[lang088]</div>
        <else>
        <serv><div class="msgSuccess" style="display:none;">[lang089]</div></serv>
        <a class="linkShow" href="javascript:void(0)">[lang061]</a>
        <div class="addReview" style="display:none;">
            <form method="post" action="">
                <div class="addMark form-group">
                    <label class="markLabel"><span class="star">*</span>[lang083]</label>
                    <div class="blockEditMark">
                        <input type="hidden" required>
                        <label class="markItem" title="[lang062]">
                            <input type="radio" name="review[mark]" value="1" style="display:none;">
                            <span style="display:none;">1</span>
                        </label><label class="markItem" title="[lang063]">
                            <input type="radio" name="review[mark]" value="2" style="display:none;">
                            <span style="display:none;">2</span>
                        </label><label class="markItem" title="[lang064]">
                            <input type="radio" name="review[mark]" value="3" style="display:none;">
                            <span style="display:none;">3</span>
                        </label><label class="markItem" title="[lang065]">
                            <input type="radio" name="review[mark]" value="4" style="display:none;">
                            <span style="display:none;">4</span>
                        </label><label class="markItem" title="[lang066]">
                            <input type="radio" name="review[mark]" value="5" style="display:none;">
                            <span style="display:none;">5</span>
                        </label>
                        <span class="markTitle"></span>
                    </div>
                </div>
                <div class="addMerits form-group">
                    <label for="merits">[lang067]</label>
                    <textarea id="merits" class="form-control" name="review[merits]" placeholder="[lang071]"></textarea>
                </div>
                <div class="addDemerits form-group">
                    <label for="demerits">[lang068]</label>
                    <textarea id="demerits" class="form-control" name="review[demerits]" placeholder="[lang072]"></textarea>
                </div>
                <div class="addComment form-group">
                    <label for="comment"><span class="star">*</span>[lang069]</label>
                    <textarea id="comment" class="form-control" name="review[comment]" placeholder="[lang073]" required></textarea>
                </div>
                <div class="addUsetime form-group">
                    <label for="usetime"><span class="star">*</span>[lang070]</label>
                    <select id="usetime" class="form-control" name="review[usetime]" required>
                        <option value="1">[lang074]</option>
                        <option value="2">[lang075]</option>
                        <option value="3">[lang076]</option>
                    </select>
                </div>
                <div class="msgRequired">[lang077]</div>
                <div class="blockButton form-group">
                    <button class="buttonSend btnAdd btn btn-default"><span>[lang078]</span></button>
                    <a class="linkCancel btn btn-link" href="javascript:void(0)">[lang079]</a>
                </div>
            </form>
        </div>
        </if>
    </if>
    <se>
        <div class="sysedit msgAuth">[lang080]</div>
        <div class="sysedit msgReviewed">[lang088]</div>
        <div class="sysedit msgSuccess">[lang089]</div>
        <div class="sysedit msgNotReviews">[lang082]</div>
    </se>
    <if:{$reviews}!=''>
        <div class="sortReviews">
            <label class="sortLabel">[lang084]</label>
            <!--noindex-->
            <repeat:reviews_sort name=sort>
                <a class="sortField<noempty:sort.selected> selected</noempty>" href="[sort.link]" data-sort="[sort.field]" rel="nofollow">[sort.name][sort.direction]</a>
            </repeat:reviews_sort>
            <!--/noindex-->
        </div>
        <div class="reviewsList">
            [subpage name=reviewslist]
        </div>
        <if:({$count_reviews} > {$count_visible})>
            <div class="moreReviews" data-offset="{$count_visible}">
                <button class="buttonSend btnShowNext">
                    <span>[lang092]</span>
                    (<span class="countNext">{$count_next}</span>)
                </button>
                <button class="buttonSend btnShowAll">
                    <span>[lang090]</span>
                    (<span class="countReviews">{$count_reviews}</span>)
                </button>
            </div>
        </if>
    <else>
        <div class="msgNotReviews">[lang082]</div>
    </if>
    <if:{$user_group}==0>
        <a href="#" class="msgAuth se-login-modal" data-target="#reviews">[lang080]</a>
    </if>    
    
</div>
</div>  
