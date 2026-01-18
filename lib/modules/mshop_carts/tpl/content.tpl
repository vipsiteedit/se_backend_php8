<!-- <a onmouseover='this.style.cursor="pointer" ' onfocus='this.blur();' onclick="document.getElementById('PopUp').style.display='block'">
    <span style="text-decoration: underline;">Щелкать здесь!</span>
</a>
<br>
<div style='text-align: right;'>
    <a onmouseover='this.style.cursor="pointer" ' style='font-size: 12px;' onfocus='this.blur();' onclick="document.getElementById('PopUp').style.display = 'none' ">
        <span style="text-decoration: underline;">закрыть</span>
    </a>
</div> 
<div id='PopUp' style='display: none; position: absolute; left: 50px; top: 50px; border: solid black 1px; padding: 10px; background-color: rgb(255,255,225); text-align: justify; font-size: 12px; width: 135px;'>
<div id="authorizeForm">
    <div id="authorin">
        <form style="margin: 0;" action="" method="post">
            <span id="title">Логин:</span>
            <span id="textlogin"></span>
            <span id="title">Пароль:</span>
            <input id="authorpassw" title="Ваш пароль" type="password" maxlength="50" name="authorpassword">
            <input class="buttonSend" id="authorsend" title="Вход" type="submit" value="OK" name="GoToAuthor">
            <div id="authorSave">
                <input id="authorSaveCheck" type="checkbox" value="1" name="authorSaveCheck"> <label for="authorSaveCheck" id="authorSaveWord">Запомнить</label>
            </div>
        </form>
        <a id="link" href="/registration/">Регистрация</a> > <a id="link" href="/remember/">Забыли пароль?</a>
    </div>    
</div>
</div> -->
<div class="content contShopCart" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="block_message">
        <?php echo $error_message ?>
        
    </div>
    <form style="margin:0px;" name="form1" action="" method="post" enctype="multipart/form-data">
        <table border="0" cellPadding="0" cellSpacing="0" class="tableTable tableListGoods" width="100%">
            <tbody class="tableBody">
                <tr class="tableRow tableHeader"> <!-- Заголовок строк заказа -->
                    <td class="cartitem_art"><?php echo $section->parametrs->param3 ?></td> 
                    <td class="cartitem_name"><?php echo $section->parametrs->param4 ?></td> 
                    <td class="cartitem_pcn"><?php echo $section->parametrs->param5 ?></td> 
                    <td class="cartitem_price"><?php echo $section->parametrs->param6 ?></td> 
                    <td class="cartitem_cn"><?php echo $section->parametrs->param7 ?></td>
                    <td class="cartitem_sum"><?php echo $section->parametrs->param8 ?></td>
                    <td class="cartitem_del">&nbsp;</td>
                </tr> 
                <?php foreach($section->objects as $record): ?>                   <!-- Строки заказа -->
                    <?php if($record->unsold==true): ?>
                        <tr class="tableRow <?php echo $record->class ?>" vAlign="top">
                            <td colspan="3" class="cartitem_userprice"></td>
                            <td colspan="4" class="cartitem_userprice"><?php echo $section->parametrs->param114 ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="tableRow <?php echo $record->class ?>" vAlign="top">
                        <td class="cartitem_art" ><?php echo $record->article ?></td>
                        <td class="cartitem_name">
                            <a class="linkname" href="/<?php echo $section->parametrs->param1 ?>/viewgoods/<?php echo $record->id ?>/">
                                <?php echo $record->name ?>
                                <?php if(!empty($record->params)): ?>(<?php echo $record->paramstr ?>)<?php endif; ?>
                            </a>
                        </td>  
                        <td class="cartitem_pcn"><?php echo $record->presence_count ?></td>
                        <td class="cartitem_price">
                            <?php if($record->unsold==false): ?>
                                <?php echo $record->price_discounted ?>
                            <?php else: ?>
                                <input class="cartitem_inputprice" type="text" name="countitem[<?php echo $record->key ?>][price]" value="<?php echo $record->userprice ?>" size="7" maxlength="10" OnKeyPress="EnsureNumeric()">
                            <?php endif; ?>
                        </td>
                        <td class="cartitem_cn">
                            <script language="Javascript">
                                function EnsureNumeric() { 
                                    if (((window.event.keyCode < 48) || (window.event.keyCode > 57)) && (window.event.keyCode != 13)) {
                                        window.event.returnValue = false; 
                                    }
                                }
                            </script>
                            <input type="hidden" name="countitem[<?php echo $record->key ?>][params]" value="<?php echo $record->params ?>">
                            <input type="hidden" name="countitem[<?php echo $record->key ?>][id]" value="<?php echo $record->id ?>">
                            <input class="cartitem_inputcn" type="text" name="countitem[<?php echo $record->key ?>][count]" value="<?php echo $record->count ?>" size="3" maxlength="<?php echo $record->lencn ?>" OnKeyPress="EnsureNumeric()">
                        </td>
                        <td class="cartitem_sum"><?php echo $record->sum ?></td>
                        <td class="cartitem_del">
                            <input class="buttonSend" name="dellcart[<?php echo $record->key ?>]" type="submit" value="<?php echo $section->parametrs->param9 ?>" title="<?php echo $section->parametrs->param9 ?>">
                        </td>
                    </tr>
                    <?php if($section->parametrs->param118=='Y'): ?>
                    <?php if($record->flag_ordered_before==true): ?>
                    <tr class="tableRow <?php echo $record->class ?>" vAlign="top">
                        <td class="cartitem_ordered_before" colspan="7">
                            <div class="div_cartitem_ordered_before">
                          Вы заказывали данный товар <?php echo $record->date_ordered_before ?> в количестве: <?php echo $record->count_ordered_before ?><!-- => <a href="" onclick="">Полный список</a> -->
                            </div>
                        </td>
                    </tr>   
                    <?php endif; ?>      
                    <?php endif; ?>              
                
<?php endforeach; ?>
            </tbody> 
        </table>
        <div class="total">
            <span class="text"><?php echo $section->parametrs->param44 ?></span> <!-- Итого -->
            <span class="sum"><?php echo $summa_order ?></span>
        </div>
        <div class="buttonArea"> <!-- Кнопки Продолжить покупки, Очистить, Пересчитать -->
            <input class="buttonSend goGoods" type="button" onClick="document.location.href='<?php echo $backpage ?>'" value="<?php echo $section->parametrs->param11 ?>">
            <input class="buttonSend clear" name="shcart_clear" type="submit" value="<?php echo $section->parametrs->param13 ?>">
            <input class="buttonSend reLoad" name="shcart_reload" type="submit" value="<?php echo $section->parametrs->param12 ?>">
        </div>
    </form>
    
    
        <?php if($display_resume): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_1.tpl")) include $__MDL_ROOT."/tpl/subpage_1.tpl"; ?>
        <?php endif; ?>
        <?php if($display_author): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__MDL_ROOT."/tpl/subpage_2.tpl"; ?>
        <?php endif; ?>
    
</div>
