<style>
.requisiteItem {margin-bottom:3px;}
.requisiteItem label {display:inline-block; width:150px;}
.requisiteItem input {width:200px;}
</style>
<div class="blockRequisite">
    <div class="selectUsertype">
        <label class="usertypeItem">
            <input type="radio" name="user_type" value="f"<empty:{$is_urid}> checked</empty>>
            <span class="titleLabel">[lang081]</span>
        </label>
        <label class="usertypeItem">
            <input type="radio" name="user_type" value="u"<noempty:{$is_urid}> checked</noempty>>
            <span class="titleLabel">[lang082]</span>
        </label>
    </div>
    <div class="requisiteList"<empty:{$is_urid}> style="display:none;"</empty>>
        <div class="msgNotFill">[lang083]</div>
        <div class="requisiteCompany">
            <h3 class="titleHead">[lang084]</h3>
            <div class="requisiteItem">
                <label for="company_name">[lang085]</label>
                <input id="company_name" type="text" name="company_name" value="<noempty:{$company_name}>{$company_name}</noempty>">
            </div>
            <div class="requisiteItem">
                <label for="company_director">[lang086]</label>
                <input id="company_director" type="text" name="company_director" value="<noempty:{$company_director}>{$company_director}</noempty>">
            </div>
            <div class="requisiteItem">
                <label for="company_phone">[lang032]</label>
                <input id="company_phone" type="text" name="company_phone" value="<noempty:{$company_phone}>{$company_phone}</noempty>">
            </div>
            <div class="requisiteItem">
                <label for="company_fax">[lang087]</label>
                <input id="company_fax" type="text" name="company_fax" value="<noempty:{$company_fax}>{$company_fax}</noempty>">
            </div>
            <div class="requisiteItem">
                <label for="company_addr">[lang088]</label>
                <input id="company_addr" type="text" name="company_addr" value="<noempty:{$company_addr}>{$company_addr}</noempty>">
            </div>
        </div>
        <noempty:{$requisite}>
            <div class="requisiteBanking">
                <h3 class="titleHead">[lang089]</h3>
                <repeat:requisite name=req>
                    <div class="requisiteItem">
                        <label for="bank_[req.code]">[req.title]</label>
                        <input id="bank_[req.code]" type="text" maxlength="[req.size]" name="bank_[req.code]" value="<noempty:[req.value]>[req.value]</noempty>">
                    </div>    
                </repeat:requisite>
            </div>
        </noempty>
    </div>
</div>
