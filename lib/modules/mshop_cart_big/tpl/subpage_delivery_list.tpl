<?php foreach($section->deliverylist as $delivery): ?>
    <div class="deliveryType" data-id="<?php echo $delivery->id ?>"> 
        <label title="<?php echo $section->language->lang030 ?>">
            <input class="radioDeliveryType" type="radio" name="delivery_type_id" value="<?php echo $delivery->id ?>"<?php echo $delivery->sel ?> data-addr="<?php echo $delivery->addr ?>">
            <span class="deliveryTypeName"><?php echo $delivery->name ?></span>
        </label>
        <div class="deliveryTypePriceTime">
            <span class="deliveryTypePrice"><?php echo $delivery->price ?></span>, <span class="deliveryTypeTime"><?php echo $delivery->time ?></span>
        </div>
        <?php if(!empty($delivery->note)): ?>
            <div class="deliveryTypeNote"><?php echo $delivery->note ?></div>
        <?php endif; ?>
    </div>

<?php endforeach; ?>
