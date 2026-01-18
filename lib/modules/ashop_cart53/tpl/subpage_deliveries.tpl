<?php foreach($section->deliverylist as $delivery): ?>
    <div class="deliveryType" data-id="<?php echo $delivery->id ?>"> 
        <label title="<?php echo $section->language->lang030 ?>">
            <input class="radioDeliveryType" type="radio" name="delivery_type_id" value="<?php echo $delivery->id ?>"<?php echo $delivery->sel ?> data-addr="<?php echo $delivery->addr ?>">
            <span class="deliveryTypeName"><?php echo $delivery->name ?></span>
        </label>
        <div class="deliveryTypePriceTime">
            <span class="deliveryTypePrice"><?php echo $delivery->price ?></span><span class="deliveryTypeTime">, <?php echo $delivery->time ?></span>
        </div>
        <?php if(!empty($delivery->note)): ?>
            <div class="deliveryTypeNote"><?php echo $delivery->note ?></div>
        <?php endif; ?>
        <?php if(!empty($delivery->sub)): ?>
            <div class="subdeliveries" <?php if($delivery->sel!=' checked'): ?>style="display:none;"<?php endif; ?>>
                <?php $__list = 'sublist'.$delivery->id; foreach($section->$__list as $sub): ?>
                    <div class="subType">
                        <label>
                            <input type="radio" name="delivery_sub_<?php echo $delivery->id ?>" value="<?php echo $sub->id ?>" <?php if(!empty($sub->sel)): ?>checked<?php endif; ?> data-addr="<?php echo $delivery->addr ?>">
                            <span class="subTypeName"><?php echo $sub->name ?></span>
                        </label>
                        <div class="subTypePriceTime">
                            <span class="subTypePrice"><?php echo $sub->price ?></span><span class="subTypeTime">, <?php echo $sub->time ?></span>
                        </div>
                        <?php if(!empty($sub->note)): ?>
                            <div class="subTypeNote"><?php echo $sub->note ?></div>
                        <?php endif; ?>
                    </div>
                
<?php endforeach; ?>    
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
