<div class="content pageConfirmOrder">    
    <h3 class="contentTitle"><span><?php echo $section->language->lang001 ?></span></h3>
    <table class="tableOrder" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableHeader">
                <th class="thConfirmOrderArt"><?php echo $section->language->lang002 ?></td> 
                <th class="thConfirmOrderName"><?php echo $section->language->lang003 ?></td> 
                <th class="thConfirmOrderPrice"><?php echo $section->language->lang004 ?></td> 
                <th class="thConfirmOrderCount"><?php echo $section->language->lang005 ?></td> 
                <th class="thConfirmOrderSum"><?php echo $section->language->lang006 ?></td> 
            </tr> 
            <?php foreach($section->objects as $record): ?>
                <tr class="tableRow">
                    <td class="tdConfirmOrderArt" ><?php echo $record->article ?></td>
                    <td class="tdConfirmOrderName"><?php echo $record->name ?><?php if(!empty($record->paramsname)): ?>(<?php echo $record->paramsname ?>)<?php endif; ?></td>  
                    <td class="tdConfirmOrderPrice"><?php echo $record->newprice ?></td>
                    <td class="tdConfirmOrderCount"><?php echo $record->show_count ?></td>
                    <td class="tdConfirmOrderSum"><?php echo $record->newsum ?></td>
                </tr>
            
<?php endforeach; ?>                                                                                                                   
        </tbody>
    </table>
    
    <table class="tableOrderTotal" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow itogo">
                <td class="tdOrderTotalTitle"><?php echo $section->language->lang007 ?></td> 
                <td class="tdOrderTotalValue"><?php echo $total_sum_goods ?></td> 
            </tr> 
            <tr class="tableRow discount">
                <td class="tdOrderTotalTitle"><?php echo $section->language->lang008 ?></td> 
                <td class="tdOrderTotalValue"><?php echo $total_sum_discount ?></td>
            </tr> 
            <tr class="tableRow cupon">
                <td class="tdOrderTotalTitle"><?php echo $section->language->lang009 ?></td> 
                <td class="tdOrderTotalValue"><?php if($find_coupon): ?><?php echo $sum_coupon ?><?php else: ?><?php echo $section->language->lang010 ?><?php endif; ?></td>
            </tr>            
            <tr class="tableRow delivery">
                <td class="tdOrderTotalTitle"><?php echo $section->language->lang011 ?></td> 
                <td class="tdOrderTotalValue"><?php echo $delivery_price ?></td>
            </tr>        
            <tr class="tableRow total">
                <td class="tdOrderTotalTitle"><?php echo $section->language->lang012 ?></td>
                <td class="tdOrderTotalValue"><?php echo $sum_total_order ?></td>
            </tr> 
        </tbody>
    </table>
  
    <table class="tableClientInfo" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow name">
                <td class="tdClientInfoTitle">
                    <span><?php echo $section->language->lang013 ?></span>
                </td>             
                <td class="tdClientInfoValue">
                    <span class="order_name"><?php echo $contact_name ?></span>
                </td>
            </tr> 
            <tr id="tr_email" class="tableRow mail">
                <td class="tdClientInfoTitle">                 
                    <span><?php echo $section->language->lang014 ?></span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_email"><?php echo $contact_email ?></span>                     
                </td> 
            </tr>             
            <tr id="tr_phone" class="tableRow phone">
                <td class="tdClientInfoTitle">
                    <span><?php echo $section->language->lang015 ?></span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_phone"><?php echo $contact_phone ?></span> 
                </td> 
            </tr>
            <tr id="tr_index" class="tableRow index">
                <td class="tdClientInfoTitle">
                    <span><?php echo $section->language->lang016 ?></span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_index"><?php echo $contact_post_index ?></span> 
                </td> 
            </tr>
            <tr id="tr_address" class="tableRow address">
                <td class="tdClientInfoTitle">
                    <span><?php echo $section->language->lang017 ?></span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_address"><?php echo $contact_address ?></span> 
                </td> 
            </tr>
            <?php if(strval($section->parametrs->param19)=='Y'): ?>
                <?php foreach($section->userfields as $field): ?>
                    <tr class="tableRow">
                        <td class="tdClientInfoTitle">
                            <span><?php echo $field->name ?></span>
                        </td>
                        <td class="tdClientInfoValue">
                            <span class="order_<?php echo $field->code ?>"><?php echo $field->value ?></span> 
                        </td> 
                    </tr>    
                
<?php endforeach; ?>
            <?php endif; ?>              
        </tbody>
    </table>
    
    <?php if(!empty($is_urid)): ?>
        <table class="tableRequisite" cellSpacing="0" cellPadding="0" border="0">
            <tbody class="tableBody">
                <tr class="tableRow">
                    <th class="thHeadTitle" colspan="2"><?php echo $section->language->lang084 ?></th>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle"><?php echo $section->language->lang085 ?></td>
                    <td class="tdValue"><?php echo $company_name ?></td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle"><?php echo $section->language->lang086 ?></td>
                    <td class="tdValue"><?php echo $company_director ?></td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle"><?php echo $section->language->lang032 ?></td>
                    <td class="tdValue"><?php echo $company_phone ?></td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle"><?php echo $section->language->lang087 ?></td>
                    <td class="tdValue"><?php echo $company_fax ?></td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle"><?php echo $section->language->lang088 ?></td>
                    <td class="tdValue"><?php echo $company_addr ?></td>
                </tr>
                <?php if(!empty($requisite)): ?>
                    <tr class="tableRow">
                        <th class="thHeadTitle" colspan="2"><?php echo $section->language->lang089 ?></th>
                    </tr>
                    <?php foreach($section->requisite as $req): ?>
                        <tr class="tableRow">
                            <td class="tdTitle"><?php echo $req->title ?></td>
                            <td class=""><?php echo $req->value ?></td>
                        </tr>
                    
<?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <table class="tableDelivery" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow">
                <td class="tableInfoTitle"><?php echo $section->language->lang018 ?></td> 
                <td class="tdInputInfo">
                    <span class="order_delivery"><?php echo $delivery_name ?></span>
                </td>
            </tr>
            <tr class="tableRow">
                <td class="tableInfoTitle"><?php echo $section->language->lang019 ?></td> 
                <td class="tdInputInfo">
                    <span class="order_payment"><?php echo $payment_name ?></span>
                </td>
            </tr>    
        </tbody>
    </table> 
      
    <div class="buttonBlock">       
        <form style="margin:0px;" action="?<?php echo $time ?>" method="post" enctype="multipart/form-data">
            <button class="btnReturnInCart buttonSend"><?php echo $section->language->lang051 ?></button>
            
            <button class="btnConfirmOrder buttonSend" name="confirm_order" value="<?php echo $section->language->lang020 ?>"><?php echo $section->language->lang020 ?></button>
        </form>
    </div>
    
</div>
