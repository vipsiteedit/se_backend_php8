<div class="content payment" >
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"><span class="contentTitleTxt"><?php echo $section->title ?></span>
            <?php echo $NAMEFORAUTHOR ?>  
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img alt="<?php echo $section->title ?>" border="0" class="contentImage" src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <table border="0" cellPadding="0" cellSpacing="0" class="tableTable orderTable" width="100%">
        <tbody class="tableBody">
            <tr class="tableRow tableHeader">
                <th class="dateOp">
                    <span><?php echo $section->parametrs->param2 ?></span>
                </th>
                <th class="in_payee">
                    <span><?php echo $section->parametrs->param3 ?></span>
                </th>
                <th class="out_payee">
                    <span><?php echo $section->parametrs->param4 ?></span>
                </th>
                <th class="linkbalans">
                    <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>"><?php echo $section->parametrs->param5 ?> <?php echo $thisdate ?></a>
                </th>
            </tr>
            <tr class="tableRow">
                <td class="titdate">
                    <span><?php echo $PAYEE_DATE ?></span>
                </td>
                <td class="titprih">
                    <span><?php echo $PAYEE_IN ?></span>
                </td> 
                <td class="titras">
                    <span><?php echo $PAYEE_OUT ?></span>
                </td>
                <td class="titost">
                    <span><?php echo $PAYEE_RES ?></span>
                </td> 
            </tr> 
        </tbody>
    </table>
    <h3 class="contentTitle order">
        <span class="contentTitleTxt"><?php echo $section->parametrs->param7 ?></span>
    </h3>
    <div class="orderSelect">
        <div class="titsel"><?php echo $section->parametrs->param6 ?></div>
        <div class="ordpay">                   
            <form style="margin:0px;" METHOD="POST">
                <select onchange="this.form.submit();" name="ORDER_ID">
                    <?php echo $ORDER_PAYEELIST ?>
                    
                </select>
            </form>
        </div>
    </div>        
    <div class="paySelect">
        <?php if($maxid): ?>        
            <?php if($section->parametrs->param8!=No): ?>
                <div class="obj">
                    <h4 class="objectTitle"><?php echo $section->parametrs->param9 ?></h4>
                    <div class="objectNote"><?php echo $section->parametrs->param10 ?></div>
                    <div class="buttonArea">
                        <form style="margin:0px;" action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>" method="post">
                            <input type="hidden" name="ORDER_ID" value="<?php echo $ORDER_ID ?>">
                            <input type="hidden" name="FP" value="0">
                            <input name="FORMA_PAYEE" class="buttonSend"  type="submit" value="<?php echo $section->parametrs->param11 ?>">
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <?php foreach($section->objects as $record): ?>
                <div class="obj" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <h4 class="objectTitle"><?php echo $record->name_payment ?></h4>
                    <?php if(!empty($record->logoimg)): ?>
                        <img class="objectImage" src="<?php echo $record->logoimg ?>">
                    <?php endif; ?> 
                    <div class="objectNote"><?php echo $record->note ?></div>
                    <div class="buttonArea">
                        <?php if($record->type=='p'): ?>
                            <form style="margin:0px;" action="<?php echo $record->linkblank ?>" method="post" target="_blank">
                                <input type="hidden" name="lang" value="<?php echo $lang ?>">
                                <input type="hidden" name="FP" value="<?php echo $record->id ?>">
                                <input type="hidden" name="ORDER_ID" value="<?php echo $ORDER_ID ?>">
                                <input name="FORMA_PAYEE" onclick="this.form.submit();" class="buttonSend" type="button" value="<?php echo $section->parametrs->param11 ?>">
                            </form>
                        <?php else: ?> 
                            <form style="margin:0px;" action='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>' method="post">
                                <input type=hidden name="FP" value="<?php echo $record->id ?>">
                                <input type=hidden  name="ORDER_ID" value="<?php echo $ORDER_ID ?>">
                                <input name="FORMA_PAYEE" onclick="this.form.submit();" class="buttonSend" type="button" value="<?php echo $section->parametrs->param11 ?>">
                            </form>
                        <?php endif; ?>   
                    </div>
                </div>
            
<?php endforeach; ?>
        <?php endif; ?>
    </div>
</div> 
