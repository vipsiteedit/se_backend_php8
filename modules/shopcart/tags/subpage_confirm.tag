<div class="content pageConfirmOrder">    
    <h3 class="contentTitle"><span>[lang001]</span></h3>
    <table class="tableOrder" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableHeader">
                <th class="thConfirmOrderArt">[lang002]</td> 
                <th class="thConfirmOrderName">[lang003]</td> 
                <th class="thConfirmOrderPrice">[lang004]</td> 
                <th class="thConfirmOrderCount">[lang005]</td> 
                <th class="thConfirmOrderSum">[lang006]</td> 
            </tr> 
            <repeat:objects name=record>
                <tr class="tableRow">
                    <td class="tdConfirmOrderArt" >[record.article]</td>
                    <td class="tdConfirmOrderName">[record.name]<noempty:record.paramsname>([record.paramsname])</noempty></td>  
                    <td class="tdConfirmOrderPrice">[record.newprice]</td>
                    <td class="tdConfirmOrderCount">[record.show_count]</td>
                    <td class="tdConfirmOrderSum">[record.newsum]</td>
                </tr>
            </repeat:objects>                                                                                                                   
        </tbody>
    </table>
    
    <table class="tableOrderTotal" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow itogo">
                <td class="tdOrderTotalTitle">[lang007]</td> 
                <td class="tdOrderTotalValue">{$total_sum_goods}</td> 
            </tr> 
            <tr class="tableRow discount">
                <td class="tdOrderTotalTitle">[lang008]</td> 
                <td class="tdOrderTotalValue">{$total_sum_discount}</td>
            </tr> 
            <tr class="tableRow cupon">
                <td class="tdOrderTotalTitle">[lang009]</td> 
                <td class="tdOrderTotalValue"><if:{$find_coupon}>{$sum_coupon}<else>[lang010]</if></td>
            </tr>            
            <tr class="tableRow delivery">
                <td class="tdOrderTotalTitle">[lang011]</td> 
                <td class="tdOrderTotalValue">{$delivery_price}</td>
            </tr>        
            <tr class="tableRow total">
                <td class="tdOrderTotalTitle">[lang012]</td>
                <td class="tdOrderTotalValue">{$sum_total_order}</td>
            </tr> 
        </tbody>
    </table>
  
    <table class="tableClientInfo" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow name">
                <td class="tdClientInfoTitle">
                    <span>[lang013]</span>
                </td>             
                <td class="tdClientInfoValue">
                    <span class="order_name">{$contact_name}</span>
                </td>
            </tr> 
            <tr id="tr_email" class="tableRow mail">
                <td class="tdClientInfoTitle">                 
                    <span>[lang014]</span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_email">{$contact_email}</span>                     
                </td> 
            </tr>             
            <tr id="tr_phone" class="tableRow phone">
                <td class="tdClientInfoTitle">
                    <span>[lang015]</span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_phone">{$contact_phone}</span> 
                </td> 
            </tr>
            <tr id="tr_index" class="tableRow index">
                <td class="tdClientInfoTitle">
                    <span>[lang016]</span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_index">{$contact_post_index}</span> 
                </td> 
            </tr>
            <tr id="tr_address" class="tableRow address">
                <td class="tdClientInfoTitle">
                    <span>[lang017]</span>
                </td>
                <td class="tdClientInfoValue">
                    <span class="order_address">{$contact_address}</span> 
                </td> 
            </tr>
            <if:[param19]=='Y'>
                <repeat:userfields name=field>
                    <tr class="tableRow">
                        <td class="tdClientInfoTitle">
                            <span>[field.name]</span>
                        </td>
                        <td class="tdClientInfoValue">
                            <span class="order_[field.code]">[field.value]</span> 
                        </td> 
                    </tr>    
                </repeat:userfields>
            </if>              
        </tbody>
    </table>
    
    <noempty:{$is_urid}>
        <table class="tableRequisite" cellSpacing="0" cellPadding="0" border="0">
            <tbody class="tableBody">
                <tr class="tableRow">
                    <th class="thHeadTitle" colspan="2">[lang084]</th>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle">[lang085]</td>
                    <td class="tdValue">{$company_name}</td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle">[lang086]</td>
                    <td class="tdValue">{$company_director}</td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle">[lang032]</td>
                    <td class="tdValue">{$company_phone}</td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle">[lang087]</td>
                    <td class="tdValue">{$company_fax}</td>
                </tr>
                <tr class="tableRow">
                    <td class="tdTitle">[lang088]</td>
                    <td class="tdValue">{$company_addr}</td>
                </tr>
                <noempty:{$requisite}>
                    <tr class="tableRow">
                        <th class="thHeadTitle" colspan="2">[lang089]</th>
                    </tr>
                    <repeat:requisite name=req>
                        <tr class="tableRow">
                            <td class="tdTitle">[req.title]</td>
                            <td class="">[req.value]</td>
                        </tr>
                    </repeat:requisite>
                </noempty>
            </tbody>
        </table>
    </noempty>
    
    <table class="tableDelivery" cellSpacing="0" cellPadding="0" border="0">
        <tbody class="tableBody">
            <tr class="tableRow">
                <td class="tableInfoTitle">[lang018]</td> 
                <td class="tdInputInfo">
                    <span class="order_delivery">{$delivery_name}</span>
                </td>
            </tr>
            <tr class="tableRow">
                <td class="tableInfoTitle">[lang019]</td> 
                <td class="tdInputInfo">
                    <span class="order_payment">{$payment_name}</span>
                </td>
            </tr>    
        </tbody>
    </table> 
      
    <div class="buttonBlock">       
        <form style="margin:0px;" action="<se>[param2].html</se><serv>?{$time}</serv>" method="post" enctype="multipart/form-data">
            <serv><button class="btnReturnInCart buttonSend">[lang051]</button></serv>
            <se><input class="btnReturnInCart buttonSend" type="button" value="[lang051]" onClick="document.location.href='[thispage.link]'"></se>
            <button class="btnConfirmOrder buttonSend" name="confirm_order" value="[lang020]">[lang020]</button>
        </form>
    </div>
    
</div>
