<div class="rowDelivery">
    <span class="cellOrderTitle cellOrderDeliverySystemTitle">name</span> 
    <span class="cellOrderVal cellOrderDeliverySystemVal"><?php echo $show_delivery_system_name ?></span>
</div>                
<div class="tableRow rowOrderDelivery">
    <span class="cellOrderTitle cellOrderDeliveryTermTitle">term</td> 
    <span class="cellOrderVal cellOrderDeliveryTermVal"><?php echo $show_delivery_term ?></span>
</div>
<div class="rowDeliveryCity">
    <span class="cellOrderTitle">
        <?php echo $section->parametrs->param105 ?><font color="red">*</font> 
    </span>
    <span class="tdInputInfo">
        <select class="contentForm" name="city_to" onchange="cardSubmit();">
            <?php echo $CITIES ?>
        </select>
    </span>
</div>
