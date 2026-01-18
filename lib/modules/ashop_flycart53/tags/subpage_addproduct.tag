<div class="">
    <h3>[lang031]</h3>
    <div class="">
        [lang032] - <strong class="count-goods">{$count_goods}</strong>.
        [lang033] - <strong class="summ-goods">{$order_summ}</strong>
        <a href="[param4].html" class="btn btn-link">[lang037]</a>
    </div>
    <div class="blockProduct container-fluid">
        <div class="image col-sm-3">
            <a href="{$product_link}">
                <img class="center-block img-responsive" src="{$product_image}">
            </a>
        </div>
        <div class="info col-sm-6">
            <h4 class="name"><a href="{$product_link}">{$product_name}</a></h4>
            <noempty:{$product_article}>
                <div class="feature">{$product_params}</div>
            </noempty>
            <noempty:{$product_article}>
                <div class="article">{$product_article}</div>
            </noempty>
            <div class="price">
                <noempty:{$product_discount}>
                    <span style="text-decoration:line-through;">{$product_oldprice}</span>
                </noempty>
                <span>{$product_newprice}</span>
            </div>
        </div>
        <div class="amount col-sm-3">
            <div class="count input-group input-group-sm">
                <span class="input-group-btn">
                    <button class="btn btn-default" data-action="dec">-</button>
                </span>
                <input type="text" class="form-control" name="countitem[{$product_key}]" value="{$product_count}" size="3"  data-step="{$product_step}">
                <span class="input-group-btn">
                    <button class="btn btn-default" data-action="inc">+</button>
                </span>
            </div>
            <div class="product-amount">{$product_amount}</div>
        </div>
    </div>
    <div class="panel-footer">
        <span class="continueShop btn btn-link">[lang034]</span>
        <a href="[param4].html#blockCartContact" class="btn btn-default pull-right" title="[lang017]">[lang028]</a>
    </div>  
    <if:[param24]=='Y'>            
        [subpage name=related]
    </if> 
</div>
