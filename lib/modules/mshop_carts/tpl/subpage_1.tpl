<!-- Форма отправки заказа -->
<!--div class="content contShopCart">
<h3 class="contentTitle"><?php echo $section->parametrs->param36 ?></h3--> 
<header:js>
[js:jquery/jquery.min.js]
[js:jquery/jquery.form.js]
<script type='text/javascript' src='[module_url]engine.js'></script>
</header:js>
<?php if($display_ex_error): ?>                
<div class="block_message"><?php echo $_ex_error ?></div>
<?php endif; ?>


    <script type="text/javascript">
        $(document).ready(function(){
            // Назначаем функцию обработки выбора адреса
            $("#display_addr_select").append('<td class="tdInputInfo">' +
                            '<select class="contentForm" name="select_addr_id" onChange="jGetUserAddr(<?php echo $section->id ?>, this.value);">' +
                                '<?php echo $SELECT_ADDRESSES ?>' +
                            '</select></td>'); 
        
            // Если пользователь уже авторизован, то скрываем ненужные поля
            <?php if($usergroup): ?> <!-- Если авторизированный пользователь -->
                <?php if($section->parametrs->param74=='email'): ?> 
                    $("#tr_email").hide();
                <?php else: ?>
                    <?php if($section->parametrs->param74=='phone'): ?>
                        $("#tr_phone").hide();                   
                    <?php endif; ?>
                <?php endif; ?>  
                $(".otherfield").hide(); // прячем остальные поля        
            <?php endif; ?>
            
            // скрываем поле ввода пароля
            $("#PopUpPassword").hide(); 
            <?php if($section->parametrs->param102=='A'): ?>
                // Назначаем обработчик форме
                $('#shcartform').submit(function() { 
                    $(this).ajaxSubmit(options); 
                    return false;
                 
                    <?php if($section->parametrs->param74=='email'): ?>
                        var login_visible = $(".tdInputInfo #email:visible").val();
                    <?php else: ?>
                        <?php if($section->parametrs->param74=='phone'): ?>
                            var login_visible = $(".tdInputInfo #phone:visible").val();                        
                        <?php endif; ?>
                    <?php endif; ?>
                                 
                    if (login_visible) { // Если логин виден
                    
                        // получаем логин из поля ввода
                        <?php if($section->parametrs->param74=='email'): ?>
                            var clogin = $(".tdInputInfo #email").val();
                        <?php else: ?>
                            <?php if($section->parametrs->param74=='phone'): ?>
                                var clogin = $(".tdInputInfo #phone").val();                        
                            <?php endif; ?>
                        <?php endif; ?>
                        var res;
                                     
                        // Проверяем, не ошибочен ли логин и существует ли он
                        $.post("?jquery"+<?php echo $section->id ?>, {'clogin': clogin}, 
                            function(data){ // data = 1 или 0
                                alert(data);
                                if (data=='err_empty_email') { // пустой логин (email)
                                    // Выводим сообщение об ошибке
                                    $("#errLogin").html('<?php echo $section->parametrs->param99 ?>');
                                    $("#errLogin").show();      
                                    $(".tdInputInfo #email").focus(); 
                                    setTimeout(function(){return false;}, 3000);                                                                         
                                }
                                else if (data=='err_empty_phone') { // пустой логин  (телефон)
                                    // Выводим сообщение об ошибке
                                    $("#errLogin").html('<?php echo $section->parametrs->param92 ?>');
                                    $("#errLogin").show();
                                    $(".tdInputInfo #phone").focus();
                                    setTimeout(function(){return false;}, 3000);         
                                }
                                else if (data=='err_email') { // невалидный email
                                    // Выводим сообщение об ошибке
                                    $("#errLogin").html('<?php echo $section->parametrs->param64 ?>');
                                    $("#errLogin").show();
                                    $(".tdInputInfo #email").focus();
                                    setTimeout(function(){return false;}, 3000); 
                                }
                                else if (data==1) { // логин существует
                                    $("#errLogin").hide(); // прячем сообщение об ошибке логина
                                    $("#PopUpPassword").show(); // показываем форму ввода пароля  
                                    $("#PopUpPassword #authorpassword").focus(); // помещаем курсор в поле ввода пароля
                                    alert('логин существует');
                                    setTimeout(function(){return false;}, 3000);
                                }
                                else { // Логин не существут - надо создать
                                    $("#errLogin").hide(); // прячем сообщение об ошибке логина
                                    $("#PopUpPassword").hide(); // прячем форму ввода пароля
                                                                
                                    // Проверяем ввод остальных полей
                                    if ($("#shcart_client_last_name").val() == '') {
                                        $("#errLastName").show(); // ошибка пустой фамилии
                                        $("#errFirstName").hide();
                                        $("#errSecName").hide();
                                        $("#shcart_client_last_name").focus();
                                        setTimeout(function(){return false;}, 3000);
                                    }
                                    else if ($("#shcart_client_first_name").val() == '') {
                                        $("#errFirstName").show(); // ошибка пустого имени
                                        $("#errLastName").hide();
                                        $("#errSecName").hide();
                                        $("#shcart_client_first_name").focus();
                                        setTimeout(function(){return false;}, 3000);                                
                                    }
                                    <?php if(($section->parametrs->param85=='Y')&&($section->parametrs->param84='Y')): ?>
                                        else if ($("#shcart_client_sec_name").val() == '') {
                                            $("#errSecName").show(); // ошибка пустого отчества
                                            $("#errLastName").hide();
                                            $("#errFirstName").hide();
                                            $("#shcart_client_sec_name").focus();
                                            setTimeout(function(){return false;}, 3000);                                
                                        }
                                    <?php endif; ?>
                                    else {  // всё нормально
                                        $("#errLastName").hide();
                                        $("#errFirstName").hide();
                                        $("#errSecName").hide();
                                        setTimeout(function(){return true;}, 3000);
                                    }
                                }
                            }
                        );
                    }
                }); 
                
                                
                // Добавляем атрибут onchange для поля ввода логина
                <?php if($section->parametrs->param74=='email'): ?>
                    $(".tdInputInfo #email").blur(function() {afterLoginInput()});
                    $(".tdInputInfo #authorpassword").blur(function() {afterPasswordInput()});
                <?php else: ?>
                    <?php if($section->parametrs->param74=='phone'): ?>
                        $(".tdInputInfo #phone").blur(function() {afterLoginInput()});          
                        $(".tdInputInfo #authorpassword").blur(function() {afterPasswordInput()});             
                    <?php endif; ?>
                <?php endif; ?>
                            
                // Функция, выполняемая после заполнения пользователем поля логина
                function afterLoginInput() {
                    // получаем логин из поля ввода
                    <?php if($section->parametrs->param74=='email'): ?>
                        var clogin = $(".tdInputInfo #email").val();
                    <?php else: ?>
                        <?php if($section->parametrs->param74=='phone'): ?>
                            var clogin = $(".tdInputInfo #phone").val();                        
                        <?php endif; ?>
                    <?php endif; ?>
                    var res;
                    // Проверяем, не ошибочен ли логин и существует ли он
                    $.post("?jquery"+<?php echo $section->id ?>, {'clogin': clogin}, 
                        function(data){ // data = 1 или 0
                            if (data=='err_empty_email') { // пустой логин (email)
                                // Выводим сообщение об ошибке
                                $("#errLogin").html('<?php echo $section->parametrs->param99 ?>');
                                $("#errLogin").show();      
                                $(".tdInputInfo #email").focus(); 
                                res = false;                                                                         
                            }
                            else if (data=='err_empty_phone') { // пустой логин  (телефон)
                                // Выводим сообщение об ошибке
                                $("#errLogin").html('<?php echo $section->parametrs->param92 ?>');
                                $("#errLogin").show();
                                $(".tdInputInfo #phone").focus();
                                res = false;                                        
                            }
                            else if (data=='err_email') { // невалидный email
                                // Выводим сообщение об ошибке
                                $("#errLogin").html('<?php echo $section->parametrs->param64 ?>');
                                $("#errLogin").show();
                                $(".tdInputInfo #email").focus();
                                res = false; 
                            }
                            else if (data==1) { // логин существует
                                $("#errLogin").hide(); // прячем сообщение об ошибке логина
                                $("#PopUpPassword").show(); // показываем форму ввода пароля  
                                $("#PopUpPassword #authorpassword").focus(); // помещаем курсор в поле ввода пароля
                                res = '1';  // означает, что залогинились
                            }
                            else { // Логин не существут - надо создать
                                $("#errLogin").hide(); // прячем сообщение об ошибке логина
                                $("#PopUpPassword").hide(); // прячем форму ввода пароля
                                res = '2'; // означает, что логин не существует
                            }
                        }
                    );
                    return res;
                }
                
                // Функция, выполняемая после ввода пароля. Проверяет пароль на правильность
                function afterPasswordInput() {
                // получаем логин из поля ввода
                    <?php if($section->parametrs->param74=='email'): ?> 
                        var login = $(".tdInputInfo #email").val();
                        var password = $("#PopUpPassword #authorpassword").val();
                    <?php else: ?>
                        <?php if($section->parametrs->param74=='phone'): ?>
                            var login = $(".tdInputInfo #phone").val();
                            var password = $("#PopUpPassword #authorpassword").val();                        
                        <?php endif; ?>
                    <?php endif; ?>
                    // Проверяем, подходит ли пароль
                    $.post("?jquery"+<?php echo $section->id ?>, {'login': login, 'password': password}, 
                        function(data){ // data = 1 или 0
                            var d = data.toString().split('|');
                            if (d[0]=='1') { // пароль верный
                                $(".otherfield").hide(); // прячем остальные поля
                                $("#errPassword").hide(); // прячем сообщение об ошибке  
                                $("#PopUpPassword").hide(); // прячем форму ввода пароля
                                <?php if($section->parametrs->param74=='email'): ?> 
                                    $("#tr_email").hide();
                                <?php else: ?>
                                    <?php if($section->parametrs->param74=='phone'): ?>
                                        $("#tr_phone").hide();                   
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                $("#showusernametextid").html(d[1]); 
                                $("#showusernametext").show();
                            }
                            else {
                                $("#errPassword").show(); // показываем сообщение об ошибке
                                $(".otherfield").show(); // показываем остальные поля  
                            }
                        }
                    );
                    return false; 
                }   
            <?php endif; ?>
        });  
    </script>
    
<form style="margin:0px;" id="shcartform" action="" method="post" enctype="multipart/form-data">
<!-- Контактная информация о заказчике и комментарий к заказу -->  
    <table class="tableTable tableClient" cellSpacing="0" cellPadding="0" border="0" width="100%">
        <tbody class="tableBody">
            <tr class="tableRow tableHeader">
                <td class="title" align="left" colspan="2"><?php echo $section->parametrs->param37 ?></td>
            </tr>
            
            <tr class="tableRow">
                <?php if($usergroup): ?> <!-- Если авторизированный пользователь -->
                    <td class="tableInfoTitle"><?php echo $section->parametrs->param38 ?></td> 
                    <td class="tdInputInfo">
                        <span class="fullname"><?php echo $client_name ?></span>
                    </td>
                <?php endif; ?>
            </tr> 
            <tr id="showusernametext" class="tableRow" style="display: none"> <!-- показываем после того как пользователь авторизовался интерактивно -->
                <td class="tableInfoTitle"><?php echo $section->parametrs->param38 ?></td> 
                <td class="tdInputInfo">
                    <span id="showusernametextid" class="fullname"></span>
                </td>
            </tr> 
            <tr id="tr_email" class="tableRow">
                <td class="tableInfoTitle"><?php echo $section->parametrs->param39 ?><?php if($section->parametrs->param74=='email'): ?><font color="red">*</font><?php endif; ?></td> 
                <td class="tdInputInfo">
                    <input type="text" id="email" class="inputinfo" name="email" value="<?php echo $_email ?>">
                    <?php if($section->parametrs->param74=='email'): ?><font color="red"><span id="errLogin" style="display: none"></span></font><?php endif; ?> <!-- Сообщения об ошибках логина -->
                </td> 
            </tr> 
            
            <?php if($section->parametrs->param74=='email'): ?> 
                <tr id="PopUpPassword" class="tableRow">
                    <form style="margin:0px;" action="" method="post">
                        <td class="tableInfoTitle">
                            <label for="authorpassword"><?php echo $section->parametrs->param26 ?></label>
                        </td>
                        <td class="tdInputInfo">
                          <input id="authorpassword" width="50" title="<?php echo $section->parametrs->param26 ?>" maxlength="50" type="password" name="authorpassword" value="">
                          <font color="red"><span id="errPassword" style="display: none"><?php echo $section->parametrs->param116 ?></span></font> <!-- "Неверный пароль!" -->
                          <div>
                            <a id="link" href="/remember/"><?php echo $section->parametrs->param117 ?></a> <!-- "Забыли пароль?" -->
                          </div>
                        </td>
                    </form>
                </tr>
            <?php endif; ?>
            
            <tr id="tr_phone" class="tableRow">
                <td class="tableInfoTitle"><?php echo $section->parametrs->param40 ?><?php if(($section->parametrs->param90=='Y')||($section->parametrs->param74=='phone')): ?><font color="red">*</font><?php endif; ?></td> 
                <td class="tdInputInfo">
                    <input type="text" id="phone" class="inputinfo" name="phone" value="<?php echo $_phone ?>">
                    <?php if($section->parametrs->param74=='phone'): ?><font color="red"><span id="errLogin" style="display: none"></span></font><?php endif; ?> <!-- Сообщения об ошибках логина -->
                </td> 
            </tr> 
            
            <!-- Телефон -->
            <?php if($section->parametrs->param74=='phone'): ?> 
                <tr id="PopUpPassword" class="tableRow">
                    <form style="margin:0px;" action="" method="post">
                        <td class="tableInfoTitle">
                            <label for="authorpassword"><?php echo $section->parametrs->param26 ?></label>
                        </td>
                        <td class="tdInputInfo">
                          <input id="authorpassword" width="50" title="<?php echo $section->parametrs->param26 ?>" maxlength="50" type="password" name="authorpassword" value="">
                          <font color="red"><span id="errPassword" style="display: none"><?php echo $section->parametrs->param116 ?></span></font> <!-- "Неверный пароль!"-->
                          <div>
                            <a id="link" href="/remember/"><?php echo $section->parametrs->param117 ?></a> <!-- "Забыли пароль?" -->
                          </div>
                        </td>
                    </form>
                </tr>
            <?php endif; ?>
            <!-- Удобное время звонка -->
            <?php if($section->parametrs->param124=='Y'): ?>
                <tr class="tableRow">
                    <td class="tableInfoTitle"><?php echo $section->parametrs->param41 ?></td> 
                    <td class="tdInputInfo">
                        <input type="text" class="inputinfo" name="calltime" value="<?php echo $_calltime ?>">
                    </td>
                </tr>  
            <?php endif; ?>
            <?php if(!$usergroup): ?> <!-- Если неавторизованный пользователь -->
                <tr class="tableRow otherfield">
                    <td class="tableInfoTitle"><?php echo $section->parametrs->param29 ?><font color="red">*</font> </td> 
                    <td class="tdInputInfo">
                        <input type="text" class="inputinfo" id="shcart_client_last_name" name="shcart_client_last_name" value="<?php echo $client_last_name ?>">
                        <font color="red"><span id="errLastName" style="display: none"><?php echo $section->parametrs->param61 ?></span></font> <!-- Сообщения об ошибках -->
                    </td>
                </tr>
                <tr class="tableRow otherfield">
                    <td class="tableInfoTitle"><?php echo $section->parametrs->param30 ?><font color="red">*</font> </td> 
                    <td class="tdInputInfo">
                        <input type="text" class="inputinfo" id="shcart_client_first_name" name="shcart_client_first_name" value="<?php echo $client_first_name ?>">
                        <font color="red"><span id="errFirstName" style="display: none"><?php echo $section->parametrs->param18 ?></span></font> <!-- Сообщения об ошибках -->
                    </td>
                </tr>
                <?php if($section->parametrs->param84=='Y'): ?>
                    <tr class="tableRow otherfield">    
                        <td class="tableInfoTitle"><?php echo $section->parametrs->param31 ?><?php if($section->parametrs->param85=='Y'): ?><font color="red">*</font><?php endif; ?> </td> 
                        <td class="tdInputInfo">
                            <input type="text" class="inputinfo" id="shcart_client_sec_name" name="shcart_client_sec_name" value="<?php echo $client_sec_name ?>">
                            <?php if($section->parametrs->param85=='Y'): ?>
                                <font color="red"><span id="errSecName" style="display: none"><?php echo $section->parametrs->param62 ?></span></font> <!-- Сообщения об ошибках -->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?> 
        </tbody>
    </table>
    <table class="tableTable tableDelivery" cellSpacing="0" cellPadding="0" border="0" width="100%">
        <tbody class="tableBody">
            <?php if($DELIVERY_TYPES!=""): ?>
                <tr class="tableRow tableHeader">
                    <td class="title" colspan="2"><?php echo $section->parametrs->param42 ?></td> 
                </tr> 
                <tr class="tableRow">
                    <td class="tableInfoTitle"><?php echo $section->parametrs->param51 ?><font color="red">*</font></td> 
                    <td class="tdInputInfo">
                        <select class="contentForm" name="delivery_type_id" onChange="submit();">
                            <?php echo $DELIVERY_TYPES ?>
                        </select> 
                    </td> 
                </tr>
            <?php endif; ?>   
            <?php if($delivery_type_id!=0): ?>
                <?php if($section->parametrs->param91=='Y'): ?>
                    <tr class="tableRow"> <!-- Почтовый индекс --> 
                        <td class="tableInfoTitle"><?php echo $section->parametrs->param43 ?><?php if($section->parametrs->param120=="Y"): ?><font color=red>*</font><?php endif; ?></td> 
                        <td class="tdInputInfo">
                            <script language="Javascript">
                                function EnsureNumeric() { 
                                    if (((window.event.keyCode < 48) || (window.event.keyCode > 57)) && (window.event.keyCode != 13)) { 
                                        window.event.returnValue = false; 
                                    }
                                }
                            </script>        
                            <input type="text" class="inputinfo" id="post_index" name="post_index" value="<?php echo $_post_index ?>" maxlength="10" OnKeyPress="EnsureNumeric()">
                        </td>
                    </tr>
                <?php endif; ?> 
                <tr class="tableRow">            <!-- Адрес -->
                    <td class="tableInfoTitle">
                        <?php echo $section->parametrs->param50 ?><font color=red>*</font>
                    </td> 
                    <td class="tdInputInfo">
                        <input type="text" class="inputinfo" id="addr" name="addr" value="<?php echo $_addr ?>">
                    </td> 
                </tr> 
                <?php if($display_addr_select): ?>      <!-- Выпадающий список выбора адреса -->
                    <tr class="tableRow" id="display_addr_select">  
                        <td class="tableInfoTitle">
                            <?php echo $section->parametrs->param119 ?>
                        </td> 
                    </tr>
                <?php endif; ?>
      <!--          <?php if($display_cities!=0): ?>
                    <tr class="tableRow">
                        <td class="tableInfoTitle">
                            <?php echo $section->parametrs->param105 ?><font color="red">*</font> 
                        </td>
                        <td class="tdInputInfo">
                            <select class="contentForm" name="city_to" onchange="submit();">
                                <?php echo $CITIES ?>
                            </select>
                        </td>
                    </tr>
                <?php endif; ?>   /-->
            <?php endif; ?>   
            <tr class="tableRow">
                <td colspan="2">
                    <div class="tableDeliveryNote" cellSpacing="0" cellPadding="0" border="0" width="100%">
                        <?php echo $DELIVERY_NOTE ?>
                    </div>
                </td> 
            </tr>
        </tbody>
    </table> 
    <?php if($section->parametrs->param71=='Y'): ?> <!-- Выводить комментарий к заказу -->
        <table class="tableTable tableComm" cellSpacing="0" cellPadding="0" border="0" width="100%">
            <tbody class="tableBody">
                <tr class="tableRow tableHeader">
                    <td class="tableCommTitle" colspan="2"><?php echo $section->parametrs->param70 ?></td> 
                </tr> 
                <tr class="tableRow">
                    <td class="tableInfoTitle"></td> 
                    <td class="tdInputInfo">
                        <textarea class="textareacomm" rows="4" name="ordercomment"><?php echo $_ordercomment ?></textarea>
                    </td> 
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <!-- Резюме заказа -->
    <table class="tableTable tableOrder" cellSpacing="0" cellPadding="0" border="0" width="100%">
        <tbody class="tableBody">
            <tr class="tableRow tableHeader"> <!-- Заголовок таблицы строк заказа в резюме -->
                <td class="cartorder_art"><?php echo $section->parametrs->param3 ?></td> 
                <td class="cartorder_name"><?php echo $section->parametrs->param4 ?></td> 
                <td class="cartorder_price"><?php echo $section->parametrs->param6 ?></td> 
                <td class="cartorder_discount"><?php echo $section->parametrs->param10 ?></td> 
                <td class="cartorder_cn"><?php echo $section->parametrs->param7 ?></td> 
                <td class="cartorder_sum"><?php echo $section->parametrs->param8 ?></td> 
            </tr> 
            <!-- Строки заказа в резюме -->
            <?php foreach($section->resumeobj as $resumeline): ?>
                <tr class="tableRow <?php echo $resumeline->class ?>">
                    <td class="cartorder_art" ><?php echo $resumeline->article ?>&nbsp;</td>
                    <td class="cartorder_name"><?php echo $resumeline->name ?>&nbsp;<?php if(!empty($resumeline->params)): ?>(<?php echo $resumeline->paramstr ?>)&nbsp;<?php endif; ?></td>  
                    <td class="cartorder_price"><?php echo $resumeline->price ?>&nbsp;</td>
                    <td class="cartorder_discount"><?php echo $resumeline->discount ?>&nbsp;</td>
                    <td class="cartorder_cn"><?php echo $resumeline->count ?>&nbsp;</td>
                    <td class="cartorder_sum"><?php echo $resumeline->sum ?>&nbsp;</td>
                </tr>
            
<?php endforeach; ?>                                                                                                                   
        </tbody>
    </table>  
    <table class="tableTable tableOrderTotal" cellSpacing="0" cellPadding="0" border="0" width="100%">
        <tbody class="tableBody">  <!-- Итоговые суммы в резюме -->
            <tr class="tableRow rowOrderTotal">
                <td class="cellOrderTitle cellOrderTotalTitle"><?php echo $section->parametrs->param44 ?></td> 
                <td class="cellOrderVal cellOrderTotalVal"><?php echo $show_sum_order ?></td> 
            </tr> 
            <tr class="tableRow rowOrderDiscount">
                <td class="cellOrderTitle cellOrderDiscountTitle"><?php echo $section->parametrs->param45 ?></td> 
                <td class="cellOrderVal cellOrderDiscountVal"><?php echo $show_sum_discount ?></td>
            </tr> 
            <?php if($display_delivery): ?>
                <tr class="tableRow rowOrderDelivery">
                    <td class="cellOrderTitle cellOrderDeliveryTitle"><?php echo $section->parametrs->param46 ?></td> 
                    <td class="cellOrderVal cellOrderDeliveryVal"><?php echo $show_sum_delivery ?></td>
                </tr>
            <?php endif; ?>
            
     <!-- EMS-доставка      <?php if($display_delivery_system==true): ?>
                <tr class="tableRow rowOrderDelivery">
                    <td class="cellOrderTitle cellOrderDeliverySystemTitle"><?php echo $section->parametrs->param110 ?></td> 
                    <td class="cellOrderVal cellOrderDeliverySystemVal"><?php echo $show_delivery_system_name ?></td>
                </tr>
                
                <tr class="tableRow rowOrderDelivery">
                    <td class="cellOrderTitle cellOrderDeliveryTermTitle"><?php echo $section->parametrs->param111 ?></td> 
                    <td class="cellOrderVal cellOrderDeliveryTermVal"><?php echo $show_delivery_term ?></td>
                </tr>
            <?php endif; ?>    /-->
            <tr class="tableRow rowOrderSumAll">
                <td class="cellOrderTitle cellOrderSumAllTitle"><?php echo $section->parametrs->param47 ?></td> 
                <td class="cellOrderVal cellOrderSumAllVal"><b><?php echo $show_sum_all ?></b></td>
            </tr>
            <?php if($display_delivery): ?>  
            <?php if($show_delivery_weight>0): ?> 
                <tr class="tableRow rowOrderDelivery">
                    <td class="cellOrderTitle cellOrderDeliveryWeightTitle"><?php echo $section->parametrs->param112 ?></td> 
                    <td class="cellOrderVal cellOrderDeliveryWeightVal"><?php echo $show_delivery_weight ?></td>
                </tr>
            <?php endif; ?>
            <?php endif; ?>
            
        </tbody>
    </table> 
    <div class="buttonArea">
        <input class="buttonSend setOrder" type="submit" id="GoTo_ORDERS" name="GoTo_ORDERS" value="<?php echo $section->parametrs->param14 ?>" border="0">
    </div>
    <input type="hidden" name="SHOP_DELIVERY_TYPE" value="<?php echo $delivery_type_id ?>">
</form> 
<!-- /div --> 
