<!-- Subpage 3. Витрина-->
<a name="productlst"></a>
<?php foreach($section->shop_price as $record): ?>  
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
                        <?php if($section->parametrs->param275=='Y'): ?>  <!-- Отображать наименование товара в виде ссылки -->
                            <a class="textTitle" href="<?php echo $record->linkshow ?>"><?php echo $record->name ?></a>
                        <?php else: ?>
                            <span class="textTitle">
                                <?php echo $record->name ?>
                            </span>
                        <?php endif; ?>
                    </h4>
                    <!-- Изображение -->
                    <div class="blockImage"> 
                    
                        <a href="<?php echo $record->linkshow ?>">
                        
                            <img class="objectImage" src="<?php echo $record->image_prev ?>" border="0" title="<?php echo $record->img_alt ?>" alt="<?php echo $record->img_alt ?>">
                        </a> 
                    </div>      
                    <?php if($section->parametrs->param266=='Y'): ?>                                      
                        <div class="objectRating">
                            <span class="objectVotesTitle"><?php echo $section->parametrs->param244 ?></span>       <!-- Рейтинг -->
                            <span class="objectVotes"><?php echo $record->votes ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param114=='Y'): ?>                                   <!-- Краткое описание -->
                        <div class="objectNote"><?php echo $record->note ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param115=='Y'): ?>                                   <!-- Подробное описание -->
                        <div class="objectText"><?php echo $record->text ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param83=='Y'): ?>
                        <div class="objectCode"><?php echo $section->parametrs->param116 ?>&nbsp;<?php echo $record->article ?></div>    <!-- Артикул -->
                    <?php endif; ?>
                    <?php if($section->parametrs->param84=='Y'): ?>
                       <div class="objectManufacturer"><?php echo $section->parametrs->param118 ?>&nbsp;<?php echo $record->manufacturer ?></div>     <!-- Производитель -->
                    <?php endif; ?>
                    <?php if($section->parametrs->param111=='Y'): ?>              <!-- Наличие -->
                        <div class="objectPresent">
                            <span class="presenceHeader"><?php echo $section->parametrs->param120 ?></span>
                            <span class="presenceText" id="count_<?php echo $razdel ?>_<?php echo $record->id ?>">&nbsp;<?php echo $record->count ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="priceBox">
                        <form style="margin:0px;" method="post" action="">
                            <div class="priceStyle">
                                <?php if($section->parametrs->param226=='Y'): ?>      <!-- Показывать цену -->
                                    <span class="priceHeader"><?php echo $section->parametrs->param121 ?></span>
                                <?php endif; ?>
                                <?php echo $record->newprice ?>
                            </div>
                            <div class="buttonBox">
                                <a class="buttonSend details" href="<?php echo $record->linkshow ?>"><?php echo $section->parametrs->param2 ?></a>
                            </div>
                    <!-- if:<?php echo $section->parametrs->param269 ?>=='Y'><div class="comparebox">
                        <input type="checkbox"  value="onCheck" <?php echo $record->compare ?>
                            onclick="if(this.checked){loadCompare('<?php echo $section->id ?>','on','<?php echo $record->id ?>');}
                            else
                            {loadCompare('<?php echo $section->id ?>','off','<?php echo $record->id ?>');}"><label> Сравнить </label>
                    </div></if -->
                         <?php if($flag): ?>
                            <div class="buttongoodedit ">
                                <a class="buttonSend recordEdit" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub12/" ?>id/<?php echo $record->id ?>/"><?php echo $section->parametrs->param306 ?></a>
                            </div>
                            <div class="buttongooddelete ">
                                <a class="buttonSend recordDelete" href="<?php echo seMultiDir()."/".$_page."/" ?>delete/<?php echo $record->id ?>/"><?php echo $section->parametrs->param307 ?></a>
                            </div> 
                         <?php endif; ?>       
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
