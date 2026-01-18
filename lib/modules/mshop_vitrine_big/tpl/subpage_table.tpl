<!-- Subpage 4. Таблица --> 
<a name="productlst"></a>
<table class="tableTable tablePrice" border="0" cellSpacing="0" cellPadding="0">
    <tbody>
    <tr class="tableRow tableHeader" vAlign="top">

        <?php if($section->parametrs->param73!='N'): ?>
            <th class="hpicture">
                <span class="htitle"><?php echo $section->language->lang026 ?></span> 
            </th>
        <?php endif; ?>
        <th class="hname">
            <span class="htitle">
                <noindex>
                    <a class="<?php echo $classsort_n ?>" href="?orderby=<?php echo $order_n ?>#productlst" rel="nofollow"><?php echo $section->language->lang006 ?></a> 
                </noindex>
            </span>
            &nbsp;<?php echo $imgsort_n ?> 
        </th>

        <?php if($section->parametrs->param74!='N'): ?>
            <th class="hnote">
                <span class="htitle"><?php echo $section->language->lang027 ?></span> 
            </th>
        <?php endif; ?>

        <?php if($section->parametrs->param65!='N'): ?>
            <th class="hmanuf">
                <span class="htitle">
                    <noindex>
                        <a class="<?php echo $classsort_m ?>" href="?orderby=<?php echo $order_m ?>#productlst" rel="nofollow"><?php echo $section->language->lang028 ?></a> 
                    </noindex>
                </span>
                &nbsp;<?php echo $imgsort_m ?> 
            </th>
        <?php endif; ?>

        <?php if($section->parametrs->param55!='N'): ?>
            <th class="hpresence">
                <span class="htitle"><?php echo $section->language->lang009 ?>&nbsp;</span> 
            </th>
        <?php endif; ?>

        <?php if($section->parametrs->param66!='N'): ?>
            <th class="hanalog">
                <span class="htitle"><?php echo $section->language->lang029 ?>&nbsp;</span>
            </th>
        <?php endif; ?>

        <th class="hprice">
            <span class="htitle">
                <noindex>
                    <a class="<?php echo $classsort_p ?>" href="?orderby=<?php echo $order_p ?>#productlst" rel="nofollow"><?php echo $section->language->lang008 ?></a> 
                </noindex>
            </span>
            &nbsp;<?php echo $imgsort_p ?> 
        </th>
        <th class="hcart" width="15%">&nbsp;</th>
    </tr>
    <?php foreach($section->objects as $record): ?>
        <tr class="tableRow <?php echo $record->style ?> blockGoods">
            <form class="form_addCart" style="MARGIN: 0px" method="post" name="frm<?php echo $razdel ?>_<?php echo $record->id ?>" action="">
                <?php if($section->parametrs->param73!='N'): ?>
                    <td class="hpicture" align="center">
                        <div class="image blockImage">
                            <a href="<?php echo $record->linkshow ?>">
                                <img title="<?php echo $record->img_alt ?>" border="0" alt="<?php echo $record->img_alt ?>" src="<?php echo $record->image_prev ?>"> 
                            </a>
                            <?php if($record->flag_hit=='Y'): ?>
                                <span class="flag_hit"><?php echo $section->language->lang057 ?></span>
                            <?php endif; ?>
                            <?php if($record->flag_new=='Y'): ?>
                                <span class="flag_new"><?php echo $section->language->lang058 ?></span> 
                            <?php endif; ?>
                            <?php if($record->unsold=='Y'): ?>
                                <span class="user_price"><?php echo $section->language->lang059 ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if($section->parametrs->param266=='Y'): ?>
                            <div class="objectRating">
                                <span class="objectVotesTitle"><?php echo $section->language->lang051 ?></span>
                                <span class="objectVotes">
                                    <?php echo $record->votes ?>
                                </span> 
                            </div>
                        <?php endif; ?>
                        <?php if($section->parametrs->param269=='Y'): ?>
                            <div class="comparebox">
                                <input onclick="if(this.checked){loadCompare('<?php echo $record->id ?>','on');} else {loadCompare('<?php echo $record->id ?>','off');}" value="onCheck" type="checkbox" <?php echo $record->compare ?>>
                                <label> <?php echo $section->language->lang013 ?> </label>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="hname">
                    <?php if($section->parametrs->param57!='N'): ?>
                        <span class="hart"><?php echo $section->parametrs->param274 ?>&nbsp;<?php echo $record->article ?></span>
                    <?php endif; ?>
                    <a class="goodsname" title="<?php echo $record->img_alt ?>" href="<?php echo $record->linkshow ?>"><?php echo $record->name ?></a>
                    <?php if(!empty($record->params)): ?>
                        <div class="divparam"><?php echo $record->params ?> </div>
                    <?php endif; ?>
                </td>
                <?php if($section->parametrs->param74!='N'): ?>
                    <td class="hnote">
                        <?php if(!empty($record->note)): ?>
                            <span class="text"><?php echo $record->note ?></span>
                        <?php endif; ?> 
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param65!='N'): ?>
                    <td class="hmanuf">
                        <?php if(!empty($record->manufacturer)): ?>
                            <div class="cmanuf"><?php echo $record->manufacturer ?></div>
                            <?php endif; ?>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param55!='N'): ?>
                    <td class="hpresence" align="left">
                        <?php if($record->nullprice=='0'): ?>
                            <span id="count_<?php echo $razdel ?>_<?php echo $record->id ?>" class="cpresence"><?php echo $record->count ?></span>
                        <?php else: ?>
                            <span id="count_<?php echo $razdel ?>_<?php echo $record->id ?>" class="cpresence"><?php echo $section->parametrs->param294 ?></span>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param66!='N'): ?>
                    <td class="hanalog">
                        <?php if(!empty($record->analogs)): ?>
                            <div class="canalog">
                                <a href="<?php echo $record->linkshow ?>#goodsanalogs"><?php echo $record->analogs ?></a> 
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="hprice">
                    <?php if($record->nullprice=='0'): ?> 
                        <div class="priceStyle"><?php echo $record->newprice ?></div>
                    <?php endif; ?>
                </td>
                <td class="hcart">
                    <?php if($section->parametrs->param205!='N'): ?>
                        <input class="cartscount" name="addcartcount" value="1" size="3">
                    <?php endif; ?>
                    <button class="buttonSend buttonAddCart addcart_<?php echo $razdel ?>_<?php echo $record->id ?>" title="<?php echo $section->language->lang015 ?>" style="<?php echo $record->show_addcart ?>"><?php echo $section->language->lang037 ?></button>
                    <input name="addcart" value="<?php echo $record->id ?>" type="hidden"> 
                    <input type="hidden" name="listcartparam" value="<?php echo $record->listcartparam ?>">
                </td>
            </form>
        </tr>
        <?php if($record->bayurls!=''): ?>
        <tr class="tableRow <?php echo $record->style ?> blockGoods">
                <?php if($section->parametrs->param73!='N'): ?>
                    <td></td>
                <?php endif; ?>
                <td class="hname"></td>
                <?php if($section->parametrs->param74!='N'): ?>
                    <td class="hnote"></td>
                <?php endif; ?>
                <?php if($section->parametrs->param65!='N'): ?>
                    <td class="hmanuf"></td>
                <?php endif; ?>
                <?php if($section->parametrs->param55!='N'): ?>
                    <td class="hpresence" align="left"></td>
                <?php endif; ?>
                <?php if($section->parametrs->param66!='N'): ?>
                    <td class="hanalog"></td>
                <?php endif; ?>
                <td colspan=2>
                    <?php $__list = 'bayurls'.$record->id; foreach($section->$__list as $bay): ?>
                    <input class="partnerurl"  onclick="document.location.href='<?php echo $bay->url ?>';" type="button" value="<?php echo $bay->name ?>"> 
                    
<?php endforeach; ?>
                </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>
