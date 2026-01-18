<div class="content subDetailOrder" >
    <h3 class="contentTitle"><?php echo $section->language->lang018 ?> <span class="orderNum"><?php echo $order_num ?></span></h3> 
    <table class="tableTable tableDetailOrder" cellPadding="0" cellSpacing="0" border="0">
        <thead class="tableHeader">
            <tr class="trHeader">
                <th class="thProductNum"><?php echo $section->language->lang016 ?></th> 
                <th class="thProductArticle"><?php echo $section->language->lang021 ?></th> 
                <th class="thProductName"><?php echo $section->language->lang022 ?></th> 
                <th class="thProductPrice"><?php echo $section->language->lang023 ?></th> 
                <th class="thProductCount"><?php echo $section->language->lang024 ?></th>
                <th class="thProductSum">Сумма</th> 
            </tr>
        </thead>
        <tbody class="tableBody">
            <?php foreach($section->tovarorder as $tovar): ?>
                <tr class="tableRow">
                    <td class="tovarordertd_num"><span><?php echo $tovar->key ?></span></td>
                    <td class="tovarordertd_art"><span><?php echo $tovar->article ?></span></td>
                    <td class="tovarordertd_name"><span><?php echo $tovar->nameitem ?><?php if(!empty($tovar->license)): ?><?php echo $tovar->license ?><?php endif; ?></span></td>
                    <td class="tovarordertd_price"><span class="orderPrice"><?php echo $tovar->price ?></span></td>
                    <td class="tovarordertd_cn">
                        <span class="tovarCount"><?php echo $tovar->count ?></span>
                        <span class="tovarMeasure"><?php echo $tovar->measure ?></span>
                    </td>
                    <td class="tovarordertd_sum"><span class="orderPrice"><?php echo $tovar->sum ?></span></td>
                </tr>
            
<?php endforeach; ?>
            <tr class="tableRowDiscount">
                <td colspan="5" class="totalTitle"><span>Скидка на заказ:</span></td>
                <td class="totalSum"><span class="orderPrice"><?php echo $discount ?></span></td>
            </tr>
            <tr class="tableRowTotal">
                <td colspan="5" class="totalTitle"><span><?php echo $section->language->lang039 ?></span></td>
                <td class="totalSum"><span class="orderPrice"><?php echo $amount ?></span></td>
            </tr>            
        </tbody>
    </table>     
    <?php if($delivery_type): ?> 
        <div class="blockDeliveryInfo"> 
        <div class="titleDelivery"><span><?php echo $section->language->lang034 ?></span></div>   
            <table class="tableTable deliveryTable" cellSpacing="0" cellPadding="0" border="0">
                <tbody class="tableBody">   
                    <tr class="tableRow">
                        <td class="tdDeliveryTitle"><?php echo $section->language->lang035 ?></td>
                        <td class="tdDeliveryValue"><?php echo $delivery_name ?></td>
                    </tr>
                    <tr class="tableRow">   
                        <td class="tdDeliveryTitle"><?php echo $section->language->lang036 ?></td>
                        <td class="tdDeliveryValue"><?php echo $delivery_sum ?></td>
                    </tr>
                    <tr class="tableRow">
                        <td class="tdDeliveryTitle"><?php echo $section->language->lang037 ?></td>
                        <td class="tdDeliveryValue"><?php echo $delivery_status ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>   
    <?php if($order_paid): ?>
        <div class="blockPaidInfo">
            <div class="titlePaid"><span><?php echo $section->language->lang030 ?></span></div>
            <table class="tableTable paymentTable" cellSpacing="0" cellPadding="0" border="0">
                <tbody class="tableBody">                               
                    <tr class="tableRow">
                        <td class="tdPaidTitle"><?php echo $section->language->lang031 ?></td>
                        <td class="tdPaidValue"><?php echo $payment_name ?></span></td>
                    </tr>
                    <tr class="tableRow">   
                        <td class="tdPaidTitle"><span><?php echo $section->language->lang032 ?></span></td>
                        <td class="tdPaidValue"><span><?php echo $transact_id ?></span></td>
                    </tr>
                    <tr class="tableRow">
                        <td class="tdPaidTitle"><span><?php echo $section->language->lang033 ?></span></td>
                        <td class="tdPaidValue"><span><?php echo $payment_sum ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="blockButton">
        <form name="frm" action="<?php echo seMultiDir()."/".$_page."/" ?>" method="POST">
            <input class="buttonSend btnBack" type="submit" value="<?php echo $section->language->lang029 ?>">
            <?php if($show_button): ?>
                <input type="hidden" name="order" value="<?php echo $order_id ?>">
                <input class="buttonSend btnDelete" name="delete_order" type="submit" value="<?php echo $section->language->lang028 ?>" >
            <?php endif; ?>
        </form>
    </div> 
</div> 
