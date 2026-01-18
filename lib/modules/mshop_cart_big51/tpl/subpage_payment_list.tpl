<div class="blockCartContent" id="blockCartPayment" <?php if($exist_payments==0): ?>style="display:none;"<?php endif; ?>>
<div class="blockCartTitle">
    <span><?php echo $section->language->lang046 ?></span>
</div>
<div class="blockCartList">                    
<?php foreach($section->paymentlist as $payment): ?>
    <div class="paymentType" data-id="<?php echo $payment->id ?>"<?php if(!empty($payment->delivery)): ?> style="display:none;"<?php endif; ?>>
        <div class="paymentImage">
            <label for="p_switch_<?php echo $payment->id ?>">
                <?php if(!empty($payment->logoimg)): ?>
                    <img src="<?php echo $payment->logoimg ?>" alt="">
                <?php endif; ?>
            </label>
        </div>   
        <div class="paymentTypeTitle">    
            <input class="paymentSwitchInp" id="p_switch_<?php echo $payment->id ?>" name="payment_type_id" type="radio" value="<?php echo $payment->id ?>"<?php if(!empty($payment->selected)): ?> checked<?php endif; ?>>
            <label for="p_switch_<?php echo $payment->id ?>">
                <span><?php echo $payment->name_payment ?></span>
            </label>
            <?php if(!empty($payment->startform)): ?>
                <a class="linkShowNote" href="javascript:void(0);">&nbsp;</a>
            <?php endif; ?>
        </div>
        <?php if(!empty($payment->startform)): ?>   
            <div class="paymentNote" style="display:none;">
                <?php echo $payment->startform ?>
            </div>
        <?php endif; ?>           
    </div>

<?php endforeach; ?>
</div>
</div>
