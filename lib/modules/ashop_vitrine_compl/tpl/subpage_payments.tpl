<div class="payment-list">
    <?php foreach($section->paymentlist as $payment): ?>
    <div class="payment-type">
            <?php if(!empty($payment->logoimg)): ?>
                <div class="payment-image">
                    <img src="<?php echo $payment->logoimg ?>" alt="">
                </div>
            <?php endif; ?>
        <div class="payment-title"><?php echo $payment->name_payment ?></div>
    </div>
    
<?php endforeach; ?>
</div>
