    <div class="requisiteList">        
        <div class="requisiteCompany">
            <h3 class="titleHead"><?php echo $section->language->lang084 ?></h3>
            <div class="requisiteItem">
                <label for="company_name">Название компании</label> <span class="required">*</span>
                <input id="company_name" type="text" name="company_name" value="<?php if(!empty($company_name)): ?><?php echo $company_name ?><?php endif; ?>" required>
            </div>
            <div class="requisiteItem">
                <label for="company_inn">ИНН</label> <span class="required">*</span>
                <input id="company_inn" type="text" name="company_inn" value="<?php if(!empty($company_inn)): ?><?php echo $company_inn ?><?php endif; ?>" required>
            </div>
            <div class="requisiteItem">
                <label for="company_email">E-mail</label> <span class="required">*</span>
                <input id="company_email" type="text" name="company_email" value="<?php if(!empty($company_email)): ?><?php echo $company_email ?><?php endif; ?>" required>
            </div>
            <div class="requisiteItem">
                <label for="company_phone">Телефон</label>
                <input id="company_phone" type="text" name="company_phone" value="<?php if(!empty($company_phone)): ?><?php echo $company_phone ?><?php endif; ?>">
            </div>
            <div class="requisiteItem">
                <label for="company_addr"><?php echo $section->language->lang088 ?></label>
                <input id="company_addr" type="text" name="company_addr" value="<?php if(!empty($company_addr)): ?><?php echo $company_addr ?><?php endif; ?>">
            </div>
        </div>
    </div>
<div id="requiredMessage"><?php echo $section->language->lang035 ?></div>
