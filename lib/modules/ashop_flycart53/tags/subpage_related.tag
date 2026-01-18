<noempty:{$related}>
<div class="cart-content-related">
    <h3>[lang035]</h3>
    <div class="related-list">
    <repeat:related name=related>
        <div class="related-item productItem" style="display:inline-block;">
            <div class="related-item-name">
                <a class="" href="[related.url]">[related.name]</a>
            </div>
            <div class="related-item-image">
                <a class="" href="[related.url]">
                    <img class="" src="[related.image]">
                </a>
            </div>
            <div class="related-item-price">
                <span class="">[related.price]</span>
            </div>
            <div class="buttonBox">
                <form class="form_addCart" method="post" action="">
                    <input type="hidden" name="addcart" value="11165">
                    <button class="buttonSend addcart btn btn-default">[lang021]</button>
                    <a class="related-item-link" href="[related.url]">[lang036]</a>
                </form>
            </div>
        </div>
    </repeat:related>
    </div>
</div>
</noempty>
