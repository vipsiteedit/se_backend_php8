<table class="tableTable tablePrice" border="0" cellSpacing="0" cellPadding="0">
    <tbody>
    <tr class="tableRow tableHeader" vAlign="top">
        <?php if(strval($section->parametrs->param73)!='N'): ?>
            <th class="hpicture">
                <span class="htitle"><?php echo $section->language->lang027 ?></span> 
            </th>
        <?php endif; ?>
        <th class="hname">
            <span class="htitle"><?php echo $section->language->lang006 ?></span>
        </th>
        <?php if(strval($section->parametrs->param74)!='N'): ?>
            <th class="hnote">
                <span class="htitle"><?php echo $section->language->lang017 ?></span> 
            </th>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param65)!='N'): ?>
            <th class="hmanuf">
                <span class="htitle"><?php echo $section->language->lang024 ?></span>
            </th>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param55)!='N'): ?>
            <th class="hpresence">
                <span class="htitle"><?php echo $section->language->lang009 ?></span> 
            </th>
        <?php endif; ?>
        <th class="hprice">
            <span class="htitle"><?php echo $section->language->lang008 ?></span>
        </th>
        <th class="hcart" width="15%">&nbsp;</th>
    </tr>
    <?php foreach($section->objects as $record): ?>
        <tr class="tableRow blockGoods">
                <?php if(strval($section->parametrs->param73)!='N'): ?>
                    <td class="hpicture">
                        <div class="image blockImage">
                            <a href="<?php echo $record->linkshow ?>">
                                <img title="<?php echo $record->img_alt ?>" border="0" alt="<?php echo $record->img_alt ?>" src="<?php echo $record->image_prev ?>"> 
                            </a>
                            <?php if($record->flag_hit=='Y'): ?>
                                <span class="flag_hit"><?php echo $section->language->lang049 ?></span>
                            <?php endif; ?>
                            <?php if($record->flag_new=='Y'): ?>
                                <span class="flag_new"><?php echo $section->language->lang050 ?></span> 
                            <?php endif; ?>
                            <?php if(!empty($record->percent)): ?>
                                <span class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</span>
                            <?php endif; ?>
                            <?php if($record->unsold=='Y'): ?>
                                <span class="user_price" title="<?php echo $section->language->lang051 ?>"><?php echo $section->language->lang051 ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                <?php endif; ?>
                <td class="hname">
                    <?php if(strval($section->parametrs->param275)=='Y'): ?>
                        <a class="goodsname" title="<?php echo $record->img_alt ?>" href="<?php echo $record->linkshow ?>"><?php echo $record->name ?></a>
                    <?php else: ?>
                        <span class="goodsname"><?php echo $record->name ?></span>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param57)!='N'): ?>
                        <span class="article">
                            <label class="articleLabel"><?php echo $section->language->lang005 ?></label>
                            <span class="articleValue"><?php echo $record->article ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param266)=='Y'): ?>
                        <div class="objectRating">
                            <label class="ratingLabel"><?php echo $section->language->lang057 ?></label>
                            <span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $record->rating ?> <?php echo $section->language->lang059 ?> 5">
                                <span class="ratingOn" style="width:<?php echo $record->rating_percent ?>%;"></span>
                            </span>
                            <span class="ratingValue"><?php echo $record->rating ?></span>
                            <span class="marks">(<label class="marksLabel"><?php echo $section->language->lang056 ?></label> <span class="marksValue"><?php echo $record->marks ?></span>)</span>
                        </div>
                    <?php endif; ?>
                    <?php echo $record->modifications ?>
                </td>
                <?php if(strval($section->parametrs->param74)!='N'): ?>
                    <td class="hnote">
                        <?php if(!empty($record->note)): ?>
                            <span class="text"><?php echo $record->note ?></span>
                        <?php endif; ?> 
                    </td>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param65)!='N'): ?>
                    <td class="hbrand">
                        <?php if(!empty($record->brand)): ?>
                            <div class="brand"><?php echo $record->brand ?></div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param55)!='N'): ?>
                    <td class="hpresence">
                        <span class="presence"><?php echo $record->count ?></span>
                    </td>
                <?php endif; ?>
                <td class="hprice">
                    <div class="priceStyle<?php if(empty($record->realprice)): ?> nullPrice<?php endif; ?>">
                        <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                            <span class="oldPrice"><?php echo $record->oldprice ?></span> 
                        <?php endif; ?>
                        <span class="newPrice"><?php echo $record->newprice ?></span>
                    </div>
                </td>
                <td class="hcart">
                    <form class="form_addCart" style="margin: 0px" method="post" action="">
                        <?php if(strval($section->parametrs->param205)!='N'): ?>
                            <input class="cartscount" type="number" min="<?php echo $record->step ?>" name="addcartcount" value="<?php echo $record->step ?>" size="4" step="<?php echo $record->step ?>">
                            <span class="measure"><?php echo $record->measure ?></span>
                        <?php endif; ?>
                        <button class="buttonSend buttonAddCart addcart" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?>disabled<?php endif; ?>><?php echo $section->language->lang033 ?></button>
                        <input type="hidden" name="addcart" value="<?php echo $record->id ?>"> 
                    </form>
                </td>
        </tr>
    
<?php endforeach; ?>
    </tbody>
</table>
