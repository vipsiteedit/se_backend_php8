<!-- Subpage 3. Витрина-->
<script type="text/javascript" src="[module_url]function.js"></script>
<a name="productlst"></a>
<?php foreach($section->objects as $record): ?>
    <table border="0" class="objectTable" cellSpacing="0" cellPadding="0">
        <tbody>
            <tr valign="bottom"> <!-- Top line -->
                <td class="borderTopLeftCorner"></td>
                <td class="borderTopCenter"></td>
                <td class="borderTopRightCorner"></td>                                   
            </tr>
            <tr> <!-- Middle -->
                <td class="borderCenterLeft"></td>
                <td class="theGoodContent">
                    <h4 class="objectTitle">
                        <?php if($section->parametrs->param275=='Y'): ?>
                            <a class="textTitle" href="<?php echo $record->linkshow ?>"><?php echo $record->name ?></a>
                        <?php else: ?>
                            <span class="textTitle">
                                <?php echo $record->name ?>
                            </span>
                        <?php endif; ?>
                    </h4>
                    <div class="blockImage"> 
                        <a href="<?php echo $record->linkshow ?>">
                            <img class="objectImage" src="<?php echo $record->image_prev ?>" border="0" title="<?php echo $record->img_alt ?>" alt="<?php echo $record->img_alt ?>">
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
                    <?php if($section->parametrs->param114=='Y'): ?>
                        <div class="objectNote"><?php echo $record->note ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param115=='Y'): ?>
                        <div class="objectText"><?php echo $record->text ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param83=='Y'): ?>
                        <div class="objectCode"><?php echo $section->parametrs->param116 ?>&nbsp;<?php echo $record->article ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param84=='Y'): ?>
                       <div class="objectManufacturer"><?php echo $section->parametrs->param118 ?>&nbsp;<?php echo $record->manufacturer ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param111=='Y'): ?>
                        <div class="objectPresent">
                            <span class="presenceHeader"><?php echo $section->parametrs->param120 ?></span>
                            <span class="presenceText" id="count_<?php echo $razdel ?>_<?php echo $record->id ?>">&nbsp;<?php echo $record->count ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="priceBox">
                        <form style="margin:0px;" method="post" action="">
                            <div class="divparam">
                                <?php if($section->parametrs->param231!='N'): ?>
                                    <?php if(!empty($record->original)): ?>
                                        <div class="originallist">
                                            <label class="originaltitle"><?php echo $section->parametrs->param178 ?></label>
                                            <select class="goodsParamSelect" name="addcartparam[]">
                                                <?php echo $record->original ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo $record->params ?>
                                <?php endif; ?>
                            </div>
                            <div class="priceStyle">
                                <?php if($section->parametrs->param226=='Y'): ?>
                                    <span class="priceHeader"><?php echo $record->priceheader ?></span>
                                <?php endif; ?>
                                <?php echo $record->newprice ?>
                            </div>
                            <div class="buttonBox">
                                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                                <span class="addcart_<?php echo $razdel ?>_<?php echo $record->id ?>" style="<?php echo $record->show_addcart ?>">
                                    <a class="buttonSend addcart" href="#" onclick="this.parentNode.parentNode.parentNode.submit();" title="<?php echo $section->parametrs->param9 ?>"><?php echo $section->parametrs->param3 ?></a>
                                </span>
                                <a class="buttonSend details" href="<?php echo $record->linkshow ?>"><?php echo $section->parametrs->param2 ?></a>
                            </div>
                    <?php if($section->parametrs->param269=='Y'): ?><div class="comparebox">
                        <input type="checkbox"  value="onCheck" <?php echo $record->compare ?>
                            onclick="if(this.checked){loadCompare('<?php echo $section->id ?>','on','<?php echo $record->id ?>');}
                            else
                            {loadCompare('<?php echo $section->id ?>','off','<?php echo $record->id ?>');}"><label> Сравнить </label>
                    </div><?php endif; ?>
                            
                        </form>
                    </div>
                </td>
                <td class="borderCenterRight"></td>
            </tr>
            <tr valign="top"> <!-- Bottom line -->
                <td class="borderBottomLeftCorner"></td>
                <td class="borderBottomCenter"></td>
                <td class="borderBottomRightCorner"></td>
            </tr>
        </tbody>
    </table>

<?php endforeach; ?>
