<table class="tableTable tablePrice" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody class="tableBody">
        <tr class="tableRow tableHeader">
            <?php if(strval($section->parametrs->param77)!="N"): ?>
                <th class="ggroup">
                    <a class="OrderActive" title="<?php echo $section->language->lang045 ?>" href="?search&<?php echo $orderb['group'] ?>"><?php echo $section->language->lang035 ?></a>
                </th>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param57)!="N"): ?>
                <th class="gart">
                    <a class="OrderActive" title="<?php echo $section->language->lang045 ?>" href="?search&<?php echo $orderb['article'] ?>"><?php echo $section->language->lang036 ?></a>
                </th>
            <?php endif; ?>
            <th class="gname">
                <a class="OrderPassive" title="<?php echo $section->language->lang045 ?>" href="?search&<?php echo $orderb['name'] ?>"><?php echo $section->language->lang037 ?></a>
            </th> 
            <?php if(strval($section->parametrs->param79)!="N"): ?>
                <th class="gmanuf">
                    <a class="OrderPassive" title="<?php echo $section->language->lang045 ?>" href="?search&<?php echo $orderb['manuf'] ?>"><?php echo $section->language->lang027 ?></a>
                </td>
            <?php endif; ?>               
            <?php if(strval($section->parametrs->param55)!="N"): ?>
                <th class="gcount"><?php echo $section->language->lang038 ?></th>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param56)!="N"): ?>
                <th class="ganalog"><?php echo $section->language->lang040 ?></th>
            <?php endif; ?>
            <th class="gprice">
                <a class="OrderPassive" title="<?php echo $section->language->lang045 ?>" href="?search&<?php echo $orderb['price'] ?>"><?php echo $section->language->lang039 ?></a>
            </th> 
            <th class="gcart"><?php echo $section->language->lang041 ?></th>
        </tr>
        <?php foreach($section->objects as $record): ?>
            <tr class="tableRow <?php echo $record->itemstyle ?>">
                <?php if(strval($section->parametrs->param77)!="N"): ?>
                    <td class="ggroup"><?php echo $record->ggroup ?></td>
                <?php endif; ?>                       
                <?php if(strval($section->parametrs->param57)!="N"): ?>
                    <td class="gart"><?php echo $record->article ?></td>
                <?php endif; ?>
                <td class="gname">
                    <a href="<?php echo $record->link ?>"><?php echo $record->name ?></a>
                </td> 
                <?php if(strval($section->parametrs->param79)!="N"): ?>
                    <td class="gmanuf"><?php echo $record->manufacturer ?></td>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param55)!="N"): ?>
                    <td class="gcount" align="left"><?php echo $record->presence_count ?></td>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param56)!="N"): ?>
                    <td class="ganalog"><?php echo $record->analog ?></td>
                <?php endif; ?>
                <td class="gprice"><?php echo $record->oldprice ?><?php echo $record->newprice ?></td> 
                <td class="gcart">
                    <form style="margin:0px;" method="post">
                        <?php if($record->buyshow!=0): ?>
                            <div>
                                <input type="text" name="addcartcount" value="1">
                            </div>
                            <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                            <input class="buttonSend buttonAddCart" type="submit" value="<?php echo $section->language->lang041 ?>" title="<?php echo $section->language->lang047 ?>">
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        
<?php endforeach; ?>
    </tbody>
</table>
