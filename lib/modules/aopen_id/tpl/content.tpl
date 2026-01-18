<header:js>

    <script src="//loginza.ru/js/widget.js" type="text/javascript"></script>


</header:js>
<div class="content openid" <?php echo $section->style ?>>
    <?php if($enter=="logout"): ?>
        <div class='logoutblock row'>
            <div class='title col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <img class='img-responsive img-rounded title_img' src='<?php if($lists_photo): ?><?php echo $lists_photo ?><?php else: ?>[module_url]nofoto.gif<?php endif; ?>'>
            </div>
            <div class='invitation col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <?php echo $section->parametrs->param14 ?><span class='username'>[NAMEUSER]</span>
            </div>
            <?php if($seUserGroup!=3): ?>
                    <div class='soc_link_a col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <a href='<?php echo $url ?>'><?php echo $section->parametrs->param18 ?></a>
                        <?php if($extra_avatar): ?>
                            <a href='?id_account'><?php echo $section->parametrs->param26 ?></a>
                        <?php endif; ?>
                    </div>
                    <?php if($extra_avatar): ?>
                        <div class='extra_images col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span class='extra_title'><?php echo $section->parametrs->param13 ?></span>
                            <?php echo $extra_avatar ?>
                        </div>
                    <?php endif; ?>
            <?php endif; ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 lnkout">
                <a class='links' href='?logout'><?php echo $section->parametrs->param15 ?></a>
            </div>
        </div>
    <?php endif; ?>
    <?php if($enter=='login'): ?>
        <div class='loginblock row'>
            <?php if((strval($section->parametrs->param23)=='f')||(strval($section->parametrs->param23)=='t')): ?>
                <div class='openIdlogin col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                    <form action='' method='post'>
                        <div class="form-group">
                            <span class='title'><?php echo $section->parametrs->param3 ?></span>
                        </div>
                        <div class="form-group">
                            <input type='hidden' value='true' name='authorize'>
                        </div>
                        <div class="form-group">
                            <input class='authorlogin' name='authorlogin' value='' placeholder='<?php echo $section->parametrs->param21 ?>'>
                        </div>
                        <div class="form-group">
                            <input class='authorpassw' type='password' name='authorpassword' value='' placeholder='<?php echo $section->parametrs->param22 ?>'>
                        </div>
                        <?php if(strval($section->parametrs->param24)=='y'): ?>
                            <div class="checkbox authorSave">
                                <label >
                                    <input id='authorSaveCheck' type='checkbox' value='1' name='authorSaveCheck'>
                                    <?php echo $section->parametrs->param19 ?>
                                </label>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-default loginsend" name='GoToAuthor'  ><?php echo $section->parametrs->param10 ?></button>
                    </form>
                    <?php if(strval($section->parametrs->param25)=='y'): ?>
                        <a class='links regi' href='<?php echo $reg_link ?>'><?php echo $section->parametrs->param20 ?></a>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param32)!=''): ?>
                        <a class='links remem' href='<?php echo $restore_link ?>'><?php echo $section->parametrs->param33 ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if((strval($section->parametrs->param23)=='s')||(strval($section->parametrs->param23)=='t')): ?>
                <div class='openIdBlock col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <?php if(strval($section->parametrs->param6)=='s'): ?>
                    <div class='loginblocktxt'><?php echo $section->parametrs->param2 ?></div>
                    <a href='<?php echo $url ?>' class='loginzain'   >
                        <img class='imgs' src='[module_url]social_add.png'/>
                    </a>
                <?php else: ?>
                    <div class='loginblocktxt'><?php echo $section->parametrs->param2 ?></div>
                    <a href='<?php echo $url ?>' class='loginzain'   ><?php echo $section->parametrs->param1 ?></a>
                <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>    

    <?php endif; ?>
</div>    
