<div class="">
    <h3>Товар добавлен в корзину</h3>
    <div class="">
        Всего в коорзине товаров - <strong class="count-goods"><?php echo $count_goods ?></strong>.
        На сумму - <strong class="summ-goods"><?php echo $order_summ ?></strong>
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>" class="btn btn-link">Посмотреть</a>
    </div>
    <div class="blockProduct container-fluid">
        <div class="image col-sm-3">
            <a href="<?php echo $product_link ?>">
                <img class="center-block img-responsive" src="<?php echo $product_image ?>">
            </a>
        </div>
        <div class="info col-sm-6">
            <h4 class="name"><a href="<?php echo $product_link ?>"><?php echo $product_name ?></a></h4>
            <?php if(!empty($product_article)): ?>
                <div class="feature"><?php echo $product_params ?></div>
            <?php endif; ?>
            <?php if(!empty($product_article)): ?>
                <div class="article"><?php echo $product_article ?></div>
            <?php endif; ?>
            <div class="price">
                <?php if(!empty($product_discount)): ?>
                    <span style="text-decoration:line-through;"><?php echo $product_oldprice ?></span>
                <?php endif; ?>
                <span><?php echo $product_newprice ?></span>
            </div>
        </div>
        <div class="amount col-sm-3">
            <div class="count input-group input-group-sm">
                <span class="input-group-btn">
                    <button class="btn btn-default" data-action="dec">-</button>
                </span>
                <input type="text" class="form-control" name="countitem[<?php echo $product_key ?>]" value="<?php echo $product_count ?>" size="3"  data-step="<?php echo $product_step ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" data-action="inc">+</button>
                </span>
            </div>
            <div class="product-amount"><?php echo $product_amount ?></div>
        </div>
    </div>
    <div class="panel-footer">
        <span class="continueShop btn btn-link">Продолжить покупки</span>
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>#blockCartContact" class="btn btn-default pull-right" title="<?php echo $section->language->lang017 ?>"><?php echo $section->language->lang028 ?></a>
    </div>   
</div>
