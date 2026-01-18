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
                <a class="<?php echo $classsort_n ?>" href="?orderby=<?php echo $order_n ?>#productlst"><?php echo $section->parametrs->param202 ?></a>
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
                    <a class="<?php echo $classsort_m ?>" href="?orderby=<?php echo $order_m ?>#productlst"><?php echo $section->parametrs->param67 ?></a>
                </span>&nbsp;<?php echo $imgsort_m ?>
            </th>
        <?php endif; ?>
        <?php if($section->parametrs->param55!='N'): ?>
            
            <th class="hpresence">
                <span class="htitle"><?php echo $section->parametrs->param198 ?>&nbsp;</span>
            </th>
        <?php endif; ?>
        <!-- if:<?php echo $section->parametrs->param66 ?>!='N'>
            
            <th class="hanalog">
                <span class="htitle"><?php echo $section->parametrs->param68 ?>&nbsp;</span>
            </th>
        </if -->
        <th class="hprice">
             
            <span class="htitle">
                <a class="<?php echo $classsort_p ?>" href="?orderby=<?php echo $order_p ?>#productlst"><?php echo $section->parametrs->param42 ?></a>
            </span>&nbsp;<?php echo $imgsort_p ?>
        </th>
        <th class="hcart">&nbsp;</th>
    </tr>
    <?php foreach($section->shop_price as $record): ?>
        <form name="frm<?php echo $razdel ?>_<?php echo $record->id ?>" style="margin:0px;" method="post" action="">
            <tr class="tableRow <?php echo $record->style ?>">
                <?php if($section->parametrs->param73!='N'): ?>
                    <td class="hpicture" align="center">
                        <div class="image">
                            <a href="<?php echo $record->linkshow ?>">
                                <img src="<?php echo $record->image_prev ?>" alt="<?php echo $record->img_alt ?>" title="<?php echo $record->img_alt ?>" border="0"<?php if($section->parametrs->param206!=0): ?> width="<?php echo $section->parametrs->param206 ?>"<?php endif; ?>>
                            </a>
                        </div>
                        <?php if($section->parametrs->param266=='Y'): ?>                <!-- Рейтинг -->
                            <div class="objectRating">
                                <span class="objectVotesTitle"><?php echo $section->parametrs->param244 ?></span>
                                <span class="objectVotes"><?php echo $record->votes ?></span>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="hname">
                    <?php if($section->parametrs->param57!='N'): ?>      <!-- Артикул -->
                        <span class="hart"><?php echo $section->parametrs->param274 ?>&nbsp;<?php echo $record->article ?></span>
                    <?php endif; ?>
                    <a class="goodsname" href="<?php echo $record->linkshow ?>" title="<?php echo $record->img_alt ?>"><?php echo $record->name ?></a> 
                    <!-- if:<?php echo $section->parametrs->param269 ?>=='Y'><div class="comparebox">
                        <input type="checkbox"  value="onCheck" <?php echo $record->compare ?>
                            onclick="if(this.checked){loadCompare('<?php echo $section->id ?>','on','<?php echo $record->id ?>');}
                            else
                            {loadCompare('<?php echo $section->id ?>','off','<?php echo $record->id ?>');}"><label> Сравнить </label>
                    </div></if -->
                </td>
                <?php if($section->parametrs->param74!='N'): ?>
                    <td class="hnote">
                        <span class="text"><?php echo $record->note ?></span>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param65!='N'): ?>            <!-- Производитель -->
                    <td class="hmanuf">
                        <div class="cmanuf"><?php echo $record->manufacturer ?></div>
                    </td>
                <?php endif; ?>
                <?php if($section->parametrs->param55!='N'): ?>            <!-- Наличие -->
                    <td align="left" class="hpresence">
                        <span class="cpresence" id="count_<?php echo $razdel ?>_<?php echo $record->id ?>"><?php echo $record->count ?></span>
                    </td>
                <?php endif; ?>
                <!-- if:<?php echo $section->parametrs->param66 ?>!='N'>            <!-- Аналоги -- >
                    <td class="hanalog">
                        <div class="canalog">
                            <?php if(!empty($record->analogs)): ?>
                                <a href="<?php echo $record->linkshow ?>#goodsanalogs"><?php echo $record->analogs ?></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </if -->
                <td class="hprice">
                    <div class="priceStyle"><?php echo $record->newprice ?></div>
                </td>
                <td class="hcart">
                    <!-- if:<?php echo $section->parametrs->param205 ?>!='N'>    <!-- Поле для ввода "Количество" -- >
                        <input class="cartscount" name="addcartcount" value="1" size="3">
                    </if-->
                    <!-- input type="hidden" name="addcart" value="<?php echo $record->id ?>" -->
                    
                         <?php if($flag): ?>
                            <div class="buttongoodedittabl ">
                                <a class="buttonSend recordEdittabl" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub12/" ?>id/<?php echo $record->id ?>/"><?php echo $section->parametrs->param306 ?></a>
                            </div>
                            <div class="buttongooddeletetabl ">
                                <a class="buttonSend recordDeletetabl" href="<?php echo seMultiDir()."/".$_page."/" ?>delete/<?php echo $record->id ?>/"><?php echo $section->parametrs->param307 ?></a>
                            </div> 
                         <?php endif; ?>       
                </td>
            </tr>
        </form> 
    
<?php endforeach; ?>
</table>
