<div class="content orderList" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>           
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <?php echo $MANYPAGE ?>
        <table class="tableTable mainOrderList" border="0" cellPadding="0" cellSpacing="0">
            <thead class="tableHeader">
                <tr class="trHeader">
                    <th class="thOrderNum"><?php echo $section->language->lang018 ?></th> 
                    <th class="thOrderDate"><?php echo $section->language->lang017 ?></th> 
                    <th class="thOrderContract"><?php echo $section->language->lang027 ?></th> 
                    <th class="thOrderSum"><?php echo $section->language->lang019 ?></th> 
                    <th class="thOrderDelivery"><?php echo $section->language->lang025 ?></th> 
                    <th class="thOrderStatus"><?php echo $section->language->lang020 ?></th> 
                </tr>
            </thead>
            <tbody class="tableBody"> 
            <?php foreach($section->objects as $record): ?>
                <tr class="tableRow">
                    <td class="ordertd_order">
                        <a href="<?php echo seMultiDir()."/".$_page."/" ?>detail_order/<?php echo $record->idorder ?>/"><?php echo $record->id_order ?></a>
                    </td>
                    <td class="ordertd_date">
                        <span><?php echo $record->date ?></span>
                    </td>
                    <td class="ordertd_dogovor">
                        <span><?php echo $record->contract ?></span>
                    </td>
                    <td class="ordertd_price" align="right">
                        <span class="orderPrice"><?php echo $record->summ ?></span>
                    </td>
                    <td class="ordertd_delivery">
                        <span><?php echo $record->delivery ?></span>
                    </td>
                    <td class="ordertd_status">
                        <span><?php echo $record->status ?></span>
                    </td>
                </tr>
            
<?php endforeach; ?>
            </tbody>
        </table>
    </div> 
</div>
