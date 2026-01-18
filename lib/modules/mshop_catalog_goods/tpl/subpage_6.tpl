<tr class="tableRow <?php echo $record->style ?>">
    <?php if($section->parametrs->param145!='N'): ?>
        <td class="hpicture" align="center">
            <div>
                <a href="<?php echo $record->linkshow ?>">
                    <img src="<?php echo $record->image_prev ?>" alt="<?php echo $record->img_alt ?>" title="<?php echo $record->img_alt ?>" border="0"<?php if($section->parametrs->param206!=0): ?> width="<?php echo $section->parametrs->param206 ?>"<?php endif; ?>>
                </a>
                <?php if($record->flag_hit=='Y'): ?>
                    <span class="flag_hit"><?php echo $section->parametrs->param240 ?></span>
                <?php endif; ?>
                <?php if($record->flag_new=='Y'): ?>
                    <span class="flag_new"><?php echo $section->parametrs->param241 ?></span>
                <?php endif; ?>
                <?php if($record->unsold=='Y'): ?>
                    <span class="user_price"><?php echo $section->parametrs->param245 ?></span>
                <?php endif; ?>
            </div>
        </td>
    <?php endif; ?>
    <td class="hname">
        <?php if($section->parametrs->param140=='Y'): ?>
            <div class="priceGroup"><?php echo $section->parametrs->param144 ?>&nbsp;<?php echo $record->group ?></div>
        <?php endif; ?>
        <?php if($section->parametrs->param141!='N'): ?>
            <span class="hart">#<?php echo $record->article ?></span>
        <?php endif; ?>
        <a class="txt" href="<?php echo $record->linkshow ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
    </td>
    <?php if($section->parametrs->param146!='N'): ?>
        <td class="hnote">
            <div class=cnote><?php echo $record->note ?></div>
        </td>
    <?php endif; ?>
    <?php if($section->parametrs->param147!='N'): ?>
        <td class="hmanuf">
            <div class=cmanuf><?php echo $record->manufacturer ?></div>
        </td>
    <?php endif; ?>
    <?php if($section->parametrs->param149!='N'): ?>
        <td align="left" class="hpresence">
            <div class=hcresence><?php echo $record->count ?></div>
        </td>
    <?php endif; ?>
    <?php if($section->parametrs->param143!='N'): ?>
        <td class="hprice">
            <div class=cprice> <?php echo $record->price ?> </div>
        </td>
    <?php endif; ?>
    <!-- td class="hcart">
        < if:<?php echo $section->parametrs->param205 ?>!='N'>
            <input class="cartscount" name="addcartcount" value="1" size="3">
        <?php endif; ?>
        < if:record.count!='--'>
            <input class="buttonSend buttonAddCart" type="submit" value="<?php echo $section->parametrs->param3 ?>" title="<?php echo $section->parametrs->param9 ?>">
            <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
        <?php endif; ?>
    </td -->
</tr>           
