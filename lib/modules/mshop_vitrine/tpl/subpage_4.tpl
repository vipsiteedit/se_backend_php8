<!-- Subpage 4. Таблица-->
<a name="productlst"></a>
<table border="0" class="tableTable tablePrice" cellSpacing="0" cellPadding="0">
    <tr class="tableRow tableHeader" valign="top">
        <?php if($section->parametrs->param73!='N'): ?>
            
            <th class="hpicture">
                <span class="htitle"><?php echo $section->parametrs->param71 ?></span>
            </th>
        <?php endif; ?>
        <th class="hname">
            <span class="htitle">
                <noindex>
                    <a class="<?php echo $classsort_n ?>" href="?orderby=<?php echo $order_n ?>#productlst" rel='nofollow'><?php echo $section->parametrs->param202 ?></a>
                </noindex>
            </span>&nbsp;<?php echo $imgsort_n ?>    
        </th>
        <?php if($section->parametrs->param74!='N'): ?>
            
            <th class="hnote">
                <span class="htitle"><?php echo $section->parametrs->param45 ?></span>
            </th>
        <?php endif; ?>
        <?php if($section->parametrs->param65!='N'): ?>
            
            <th class="hmanuf">
                <span class="htitle">
                    <noindex>
                        <a class="<?php echo $classsort_m ?>" href="?orderby=<?php echo $order_m ?>#productlst" rel='nofollow'><?php echo $section->parametrs->param67 ?></a>
                    </noindex>
                </span>&nbsp;<?php echo $imgsort_m ?>
            </th>
        <?php endif; ?>
        <?php if($section->parametrs->param55!='N'): ?>
            
            <th class="hpresence">
                <span class="htitle"><?php echo $section->parametrs->param198 ?>&nbsp;</span>
            </th>
        <?php endif; ?>
        <?php if($section->parametrs->param66!='N'): ?>
            
            <th class="hanalog">
                <span class="htitle"><?php echo $section->parametrs->param68 ?>&nbsp;</span>
            </th>
        <?php endif; ?>
        <th class="hprice">
             
            <span class="htitle">
                <noindex>
                <a class="<?php echo $classsort_p ?>" href="?orderby=<?php echo $order_p ?>#productlst" rel='nofollow'><?php echo $section->parametrs->param42 ?></a>
                </noindex>
            </span>&nbsp;<?php echo $imgsort_p ?>
        </th>
        <th width="15%" class="hcart">&nbsp;</th>
    </tr>
    <?php foreach($section->objects as $record): ?>
        <form name="frm<?php echo $razdel ?>_<?php echo $record->id ?>" style="margin:0px;" method="post" action="">
            <tr class="tableRow <?php echo $record->style ?>">
                <?php if($section->parametrs->param73!='N'): ?>
                    <td class="hpicture" align="center">
                        <div class="image">
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
                        <?php if($section->parametrs->param266=='Y'): ?>
                            <div class="objectRating">
                                <span class="objectVotesTitle"><?php echo $section->parametrs->param244 ?></span>
                                <span class="objectVotes"><?php echo $record->votes ?></span>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="hname">
                    <?php if($section->parametrs->param57!='N'): ?>
                        <span class="hart"><?php echo $section->parametrs->param274 ?>&nbsp;<?php echo $record->article ?></span>
                    <?php endif; ?>
                    <a class="goodsname" href="<?php echo $record->linkshow ?>" title="<?php echo $record->img_alt ?>"><?php echo $record->name ?></a> 
                    <?php if($paramsRequired): ?>
                        <?php if($section->parametrs->param231!='N'): ?>
                            <?php if(!empty($record->original)): ?>
                                <div class="divparam">    
                                    <label class="goodsParamTitle"><?php echo $section->parametrs->param178 ?></label>
                                    <select class="goodsParamSelect" name="addcartparam[]">
                                        <?php echo $record->original ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if(!empty($record->params)): ?>
                                <div class="divparam">    
                                    <?php echo $record->params ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($section->parametrs->param269=='Y'): ?><div class="comparebox">
                        <input type="checkbox"  value="onCheck" <?php echo $record->compare ?>
                            onclick="if(this.checked){loadCompare('<?php echo $section->id ?>','on','<?php echo $record->id ?>');}
                            else
                            {loadCompare('<?php echo $section->id ?>','off','<?php echo $record->id ?>');}"><label> Сравнить </label>
                    </div><?php endif; ?>
                </td>
                <?php if($section->parametrs->param74!='N'): ?>
                    <td class="hnote">
                        <span class="text"><?php echo $record->note ?></span>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param65!='N'): ?>
                    <td class="hmanuf">
                        <div class="cmanuf"><?php echo $record->manufacturer ?></div>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param55!='N'): ?>
                    <td align="left" class="hpresence">
                        <span class="cpresence" id="count_<?php echo $razdel ?>_<?php echo $record->id ?>"><?php echo $record->count ?></span>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param66!='N'): ?>
                    <td class="hanalog">
                        <div class="canalog">
                            <?php if(!empty($record->analogs)): ?>
                                <a href="<?php echo $record->linkshow ?>#goodsanalogs"><?php echo $record->analogs ?></a>
                            <?php endif; ?>
                        </div>
                    </td>
                <?php endif; ?>
                <td class="hprice">
                    <div class="priceStyle"><?php echo $record->newprice ?></div>
                </td>
                <td class="hcart">
                    <?php if($section->parametrs->param205!='N'): ?>
                        <input class="cartscount" name="addcartcount" value="1" size="3">
                    <?php endif; ?>
                    <span class="addcart_<?php echo $razdel ?>_<?php echo $record->id ?>" style="<?php echo $record->show_addcart ?>">
                        <input class="buttonSend buttonAddCart" type="submit" value="<?php echo $section->parametrs->param3 ?>" title="<?php echo $section->parametrs->param9 ?>">
                    </span>
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                </td>
            </tr>
        </form> 
    
<?php endforeach; ?>
</table>
