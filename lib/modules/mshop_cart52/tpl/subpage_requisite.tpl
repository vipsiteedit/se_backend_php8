<style>
.requisiteItem {margin-bottom:3px;}
.requisiteItem label {display:inline-block; width:150px;}
.requisiteItem input {width:200px;}
</style>
<div class="blockRequisite">
    <div class="selectUsertype">
        <label class="usertypeItem">
            <input type="radio" name="user_type" value="f"<?php if(empty($is_urid)): ?> checked<?php endif; ?>>
            <span class="titleLabel"><?php echo $section->language->lang081 ?></span>
        </label>
        <label class="usertypeItem">
            <input type="radio" name="user_type" value="u"<?php if(!empty($is_urid)): ?> checked<?php endif; ?>>
            <span class="titleLabel"><?php echo $section->language->lang082 ?></span>
        </label>
    </div>
    <div class="requisiteList"<?php if(empty($is_urid)): ?> style="display:none;"<?php endif; ?>>
        <div class="msgNotFill"><?php echo $section->language->lang083 ?></div>
        <div class="requisiteCompany">
            <h3 class="titleHead"><?php echo $section->language->lang084 ?></h3>
            <div class="requisiteItem">
                <label for="company_name"><?php echo $section->language->lang085 ?></label>
                <input id="company_name" type="text" name="company_name" value="<?php if(!empty($company_name)): ?><?php echo $company_name ?><?php endif; ?>">
            </div>
            <div class="requisiteItem">
                <label for="company_director"><?php echo $section->language->lang086 ?></label>
                <input id="company_director" type="text" name="company_director" value="<?php if(!empty($company_director)): ?><?php echo $company_director ?><?php endif; ?>">
            </div>
            <div class="requisiteItem">
                <label for="company_phone"><?php echo $section->language->lang032 ?></label>
                <input id="company_phone" type="text" name="company_phone" value="<?php if(!empty($company_phone)): ?><?php echo $company_phone ?><?php endif; ?>">
            </div>
            <div class="requisiteItem">
                <label for="company_fax"><?php echo $section->language->lang087 ?></label>
                <input id="company_fax" type="text" name="company_fax" value="<?php if(!empty($company_fax)): ?><?php echo $company_fax ?><?php endif; ?>">
            </div>
            <div class="requisiteItem">
                <label for="company_addr"><?php echo $section->language->lang088 ?></label>
                <input id="company_addr" type="text" name="company_addr" value="<?php if(!empty($company_addr)): ?><?php echo $company_addr ?><?php endif; ?>">
            </div>
        </div>
        <?php if(!empty($requisite)): ?>
            <div class="requisiteBanking">
                <h3 class="titleHead"><?php echo $section->language->lang089 ?></h3>
                <?php foreach($section->requisite as $req): ?>
                    <div class="requisiteItem">
                        <label for="bank_<?php echo $req->code ?>"><?php echo $req->title ?></label>
                        <input id="bank_<?php echo $req->code ?>" type="text" maxlength="<?php echo $req->size ?>" name="bank_<?php echo $req->code ?>" value="<?php if(!empty($req->value)): ?><?php echo $req->value ?><?php endif; ?>">
                    </div>    
                
<?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
