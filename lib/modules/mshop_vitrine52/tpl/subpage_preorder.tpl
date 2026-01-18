<style>
.callback-form {
    padding:30px;
    min-width:360px; 
}
.callback-form .title-cback {
 margin-bottom: 15px;
     position: relative;
     font-size: 1.3em;
     font-weight: bold;
}
.callback-form .line-field-cback {
     margin-bottom: 0.5em;
}
.callback-form .block-label {
     margin-bottom: 0.2em;
}
.callback-form .block-label label {
     padding-right: 10px;
         vertical-align: middle;
         font-weight: bold;
}
.callback-form .block-label .required {
     color: #FF0000;
}
.callback-form .block-button-cback {
     margin-top: 1.5em;
}
</style>
<div id="callback-modal-form" class="blockPreorder callback-form" style="display:none;">
    <form name="cback-form" method="post" id="" action="">
        <input type="hidden" name="product" value="">
        <input type="hidden" name="count" value="">
        <div class="title-cback">Сообщить о поступлении</div>
        <div class="block-field-cback">
            <div class="line-field-cback line-cback-name">
                <div class="block-label">
                    <label for="cb_name">Ф.И.О.</label><span class="required">*</span>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_name" name="cb_name">
                    <div class="cback_error" style="display:none;">Необходимо заполнить ФИО</div>
                </div>
            </div>
            <div class="line-field-cback line-cback-email">
                <div class="block-label">
                    <label for="cb_email">E-mail</label><span class="required">*</span>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_email" name="cb_email">
                    <div class="cback_error" style="display:none;">Корректно введите ваш e-mail</div>
                </div>
            </div>
            <div class="line-field-cback line-cback-phone">
                <div class="block-label">
                    <label for="cb_phone">Телефон</label>
                </div>
                <div class="block-input">
                    <input value="" type="text" class="input-cback" id="cb_phone" name="cb_phone">
                    <div class="cback_error" style="display:none;"></div>
                </div>
            </div>
        </div>
        <div class="block-button-cback">
            <button type="submit" class="buttonSend btnConfirm btn btn-default">Отправить</button>
        </div>
    </form>
    <div class="msgAccepted" style="display:none;">
        <p>Ваша заявка принята.</p>
  <p>Когда товар появится на складе, Вам поступит уведомление о наличии.</p>
    </div>
</div>
