<div class="blockCartContent" id="blockCartPayment" <if:{$exist_payments}==0>style="display:none;"</if>>
<div class="blockCartTitle">
    <span>[lang046]</span>
</div>
<div class="blockCartList">                    
<repeat:paymentlist name=payment>
    <div class="paymentType" data-id="[payment.id]"<noempty:payment.delivery> style="display:none;"</noempty>>
        <div class="paymentImage">
            <label for="p_switch_[payment.id]">
                <noempty:payment.logoimg>
                    <img src="[payment.logoimg]" alt="">
                </noempty>
            </label>
        </div>   
        <div class="paymentTypeTitle">    
            <input class="paymentSwitchInp" id="p_switch_[payment.id]" name="payment_type_id" type="radio" value="[payment.id]"<noempty:payment.selected> checked</noempty>>
            <label for="p_switch_[payment.id]">
                <span>[payment.name_payment]</span>
            </label>
            <noempty:payment.startform>
                <a class="linkShowNote" href="javascript:void(0);">&nbsp;</a>
            </noempty>
        </div>
        <noempty:payment.startform>   
            <div class="paymentNote" style="display:none;">
                [payment.startform]
            </div>
        </noempty>           
    </div>
</repeat:paymentlist>
</div>
</div>
