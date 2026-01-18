<div class="payment-list">
    <repeat:paymentlist name=payment>
    <div class="payment-type">
            <noempty:payment.logoimg>
                <div class="payment-image">
                    <img src="[payment.logoimg]" alt="">
                </div>
            </noempty>
        <div class="payment-title">[payment.name_payment]</div>
    </div>
    </repeat:paymentlist>
</div>
