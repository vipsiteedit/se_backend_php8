<header:js>
[lnk:rouble/rouble.css]
</header:js>
<div class="content payment" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" >
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"><span class="contentTitleTxt"><?php echo $section->title ?></span>
            <?php echo $NAMEFORAUTHOR ?>  
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->title ?>" border="0" class="contentImage" src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    
    <?php if($user_group!=0): ?>
    <table border="0" cellPadding="0" cellSpacing="0" class="tableTable orderTable" width="100%">
        <tbody class="tableBody">
            <tr class="tableRow tableHeader">
                <th class="dateOp">
                    <span><?php echo $section->language->lang009 ?></span>
                </th>
                <th class="in_payee">
                    <span><?php echo $section->language->lang008 ?></span>
                </th>
                <th class="out_payee">
                    <span><?php echo $section->language->lang007 ?></span>
                </th>
                <th class="linkbalans">
                    <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subpersonal/" ?>"><?php echo $section->language->lang006 ?> <?php echo $thisdate ?></a>
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
    <?php else: ?>
        <div class="paymentMessage"><?php echo $section->parametrs->param32 ?></div>
    <?php endif; ?>
    <h3 class="contentTitle order">
        <span class="contentTitleTxt"><?php echo $section->language->lang010 ?></span>
    </h3>
    <div class="orderSelect">
        <div class="titsel"><?php echo $section->language->lang005 ?></div>
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
            <?php foreach($section->objects as $record): ?>
                <div class="obj" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <h4 class="objectTitle"><?php echo $record->name_payment ?></h4>
                    <?php if(!empty($record->logoimg)): ?>
                        <img class="objectImage" src="<?php echo $record->logoimg ?>">
                    <?php endif; ?> 
                    <div class="objectNote"><?php echo $record->note ?></div>
                    <div class="buttonArea">
                        <input type="button" class="buttonSend"  onclick="document.location.href='<?php echo $record->invnum ?>'"   value="<?php echo $section->language->lang012 ?>">  
                    </div>
                </div>
            
<?php endforeach; ?>
        <?php endif; ?>
    </div>
</div> 
