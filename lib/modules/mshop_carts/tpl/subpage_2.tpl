<div class="forAuthor">
    <h3 class="contentTitle"><?php echo $section->parametrs->param23 ?></h3>
    <?php if($section->parametrs->param102=='M'): ?>  <!-- Если смешанный режим, то Кнопка Заказать без регистрации -->
        <?php if($display_unregBtn): ?>
        <div class="OrderUnregBtnForm"> 
            <form style="margin:0px;" method="post">
                <div class="buttonArea"> 
                    <input type="hidden" name="GoTo_OrderUnreg">
                    <input class="buttonSend GoTo_OrderUnreg" type="submit" value="<?php echo $section->parametrs->param101 ?>"> 
                </div>
                <?php if($section->parametrs->param103!=''): ?> <!-- Текст предупреждения об отсутствии скидок -->
                    <div class="UnregText">
                        <?php echo $section->parametrs->param103 ?>    
                    <div>
                <?php endif; ?>
            </form>
        </div> 
        <?php endif; ?>
    <?php endif; ?>    
    <div class="authorizeForm">
        <form style="margin:0px;" action="" method="post">
            <div class="title"><?php echo $section->parametrs->param24 ?></div>
            <div class="obj login">
                <label for="authorlogin"><?php echo $section->parametrs->param77 ?></label>
                <div>
                    <input id="authorlogin" width="50" title="<?php echo $section->parametrs->param25 ?>" maxlength="50" name="authorlogin" value="">
                </div>
            </div>
            <div class="obj password">
                <label for="authorpassword"><?php echo $section->parametrs->param26 ?></label>
                <div>
                    <input id="authorpassword" width="50" title="<?php echo $section->parametrs->param26 ?>" maxlength="50" type="password" name="authorpassword" value="">
                </div>
            </div>
            <div class="buttonArea">
                <input type="hidden" name="GoToAuthor">
                <input class="buttonSend" type="submit" value="<?php echo $section->parametrs->param54 ?>">
            </div>
            <input type="hidden" value="true" name="authorize">
        </form>
    </div>
    <a name="newauthor"></a> 
    <h3 class="contentTitle"><?php echo $section->parametrs->param27 ?></h3>
    <?php if($section->parametrs->param79==Y): ?>
        <div class="newRegInfo"><?php echo $section->parametrs->param78 ?></div>
    <?php endif; ?>
    <div class="regForm">
        <?php if($display_ex_error): ?>       
            <div class="block_message"><?php echo $_ex_error ?></div>
        <?php endif; ?>                       
        
        <form style="margin:0px;" name="frm" action="" method="post">
            <div class="title"><?php echo $section->parametrs->param28 ?></div>
            <div class="obj lastName">
                <label><?php echo $section->parametrs->param29 ?><font color="red">*</font></label>
                <div>
                    <input width="50" type="text" name="last_name" value="<?php echo $reg_last_name ?>" title="<?php echo $section->parametrs->param29 ?>">
                </div>
            </div>
            <div class="obj firstName">
                <label><?php echo $section->parametrs->param30 ?><font color="red">*</font></label>
                <div>
                    <input width="50" type="text" name="first_name" value="<?php echo $reg_first_name ?>" title="<?php echo $section->parametrs->param30 ?>">
                </div>
            </div>
            <?php if($section->parametrs->param84=='Y'): ?>
                <div class="obj secName">
                    <label><?php echo $section->parametrs->param31 ?><?php if($section->parametrs->param85=='Y'): ?><font color="red">*</font><?php endif; ?></label>
                    <div>
                        <input width="50" type="text" name="sec_name" value="<?php echo $reg_sec_name ?>" title="<?php echo $section->parametrs->param31 ?>">
                    </div>
                </div>
            <?php endif; ?>
            <div class="obj email">
                <label>
                    <?php if($section->parametrs->param74=='email'): ?><?php echo $section->parametrs->param75 ?><?php endif; ?>
                    <?php if($section->parametrs->param74=='phone'): ?><?php echo $section->parametrs->param76 ?><?php endif; ?>
                    <?php if($section->parametrs->param74=='username'): ?><?php echo $section->parametrs->param77 ?><?php endif; ?>
                    <font color="red">*</font>
                </label>
                <div>
                    <?php if($section->parametrs->param74=='email'): ?>
                        <input width="50" type="text" name="email" value="<?php echo $reg_email ?>" title="<?php echo $section->parametrs->param32 ?>">
                    <?php endif; ?>
                    <?php if($section->parametrs->param74=='phone'): ?>
                        <input width="50" type="text" name="phone" value="<?php echo $reg_phone ?>" title="<?php echo $section->parametrs->param32 ?>">
                    <?php endif; ?>
                    <?php if($section->parametrs->param74=='username'): ?>
                        <input width="50" type="text" name="username" value="<?php echo $reg_username ?>" title="<?php echo $section->parametrs->param32 ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="obj passw">
                <label><?php echo $section->parametrs->param33 ?><font color="red">*</font></label>
                <div>
                    <input width="50" type="password" name="passw" value="<?php echo $reg_passw ?>" title="<?php echo $section->parametrs->param33 ?>">
                </div>
            </div>
            <div class="obj passw">
                <label><?php echo $section->parametrs->param34 ?><font color="red">*</font></label>
                <div>
                    <input width="50" type="password" name="passw1" value="" title="<?php echo $section->parametrs->param34 ?>">
                </div>
            </div>
            <div class="buttonArea">
                <input type="hidden" name="GoToRegShop">
                <input class="buttonSend" type="submit" value="<?php echo $section->parametrs->param35 ?>">
            </div>
        </form>          
    </div>    
</div> 
