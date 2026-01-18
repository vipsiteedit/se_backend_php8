<header:js>
[js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript">
    function loadBox(id, name, value) {
        $('#'+id).load("/<?php echo $_page ?>/<?php echo $razdel ?>/sub3/"+name+"/"+value,{});
    } 
</script>
<div class="content adminUserList admDealer">
    <?php if($errorRes!=''): ?>
        <div class="error">
            <span><?php echo $errorRes ?></span>
        </div>
    <?php endif; ?>
    <form action="#<?php echo $section->id ?>" method="post">
        <table border="0" cellPadding="0" cellSpacing="0" class="tableTable">
            <tbody class="tableBody">
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls">
                        <span><?php echo $section->language->lang043 ?></span>
                    </td>
                </tr> 
                <tr class="tableRow tableRowOdd login<?php if($_user==0): ?> important<?php endif; ?>">
                    <td class="titl">
                        <?php if($_user==0): ?>
                            <font color='red'>*</font>
                        <?php endif; ?>
                        
                        <span><?php echo $section->language->lang006 ?>:</span>
                    </td> 
                    <td class="value">
                        <?php if($_user!=0): ?>
                            <span><?php echo $_a_login ?></span>
                            
                        <?php else: ?>
                            <input type='text' value='<?php echo $_a_login ?>' name='a_login' class="inp">
                        <?php endif; ?>
                    </td> 
                </tr> 
                <?php if($_user!=0): ?>
                    <tr class="tableRow tableRowEven user">
                        <td class="titl">
                            <span><?php echo $section->language->lang044 ?>:</span>
                        </td> 
                        <td class="value">
                            <span><?php echo $_user ?></span>
                            <input type="hidden" name="user" value="<?php echo $_user ?>">
                        </td> 
                    </tr> 
                <?php endif; ?>
                <tr class="tableRow tableRowOdd status">
                    <td class="titl">
                        <span><?php echo $section->language->lang045 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <div class="radio yes">
                            <input type="radio" name="reg_status" value="Y" <?php echo $REG_STATUS1 ?>>
                            <span><?php echo $section->language->lang067 ?></span>
                        </div>
                        <div class="radio no">
                            <input type="radio" name="reg_status" value="N" <?php echo $REG_STATUS2 ?>>
                            <span><?php echo $section->language->lang068 ?></span>
                        </div>
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven group important" vAlign="top">
                    <td class="titl">
                        <font color='red'>*</font><span><?php echo $section->language->lang046 ?>:</span>
                    </td> 
                    <td class="value">
                        <select name="usergroup[]" multiple>
                            <?php if(file_exists($__MDL_ROOT."/php/subpage_4.php")) include $__MDL_ROOT."/php/subpage_4.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_4.tpl")) include $__data->include_tpl($section, "subpage_4"); ?>
                        </select>
                    </td>                       
                </tr> 
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls">
                        <span><?php echo $section->language->lang047 ?></span>
                    </td>
                </tr> 
                <tr class="tableRow tableRowOdd last_name important">
                    <td class="titl">
                        <font color='red'>*</font><span><?php echo $section->language->lang048 ?>:</span>
                    </td> 
                    <td class="value">
                        <input type="text" class="inp" size="50" name="last_name" value="<?php echo $_last_name ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven first_name important">
                    <td class="titl">
                        <font color='red'>*</font><span><?php echo $section->language->lang049 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" class="inp" size="50" name="first_name" value="<?php echo $_first_name ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd sec_name">
                    <td class="titl">
                        <span><?php echo $section->language->lang050 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" class="inp" size="50" name="sec_name" value="<?php echo $_sec_name ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls">
                        <span><?php echo $section->language->lang051 ?></span>
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd doc_ser">
                    <td class="titl">
                        <span><?php echo $section->language->lang052 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" maxlength="12" class="inp" size="10" name="doc_ser" value="<?php echo $_doc_ser ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven doc_num">
                    <td class="titl">
                        <span><?php echo $section->language->lang053 ?>:</span>
                    </td> 
                    <td class="value">
                        <input type="text" maxlength="12" class="inp" size="10" name="doc_num" value="<?php echo $_doc_num ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls">
                        <span><?php echo $section->language->lang054 ?></span>
                    </td>
                </tr> 
                <tr class="tableRow tableRowOdd post_index">
                    <td class="titl">
                        <span><?php echo $section->language->lang055 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" class="inp" size="10" name="post_index" value="<?php echo $_post_index ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven country">
                    <td class="titl">
                        <span><?php echo $section->language->lang056 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <select name="country" onChange="loadBox('region', 'region', this.value);">
                            <option value="0"><?php echo $section->language->lang069 ?></option>
                            <?php foreach($section->countrylist as $country): ?>
                                <option value="<?php echo $country->id ?>" <?php if($country->sel!=0): ?>selected<?php endif; ?>><?php echo $country->name ?></option>
                            
<?php endforeach; ?>
                        </select>
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd region">
                    <td class="titl">
                        <span><?php echo $section->language->lang057 ?>:</span>
                    </td> 
                    <td class="value">
                        <select name="state" id="region" onChange="loadBox('town', 'town', this.value);">
                            <option value="0"><?php echo $section->language->lang070 ?></option>
                            <?php foreach($section->statelist as $state): ?>
                                <option value="<?php echo $state->id ?>" <?php if($state->sel!=0): ?>selected<?php endif; ?>><?php echo $state->name ?></option>
                            
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="tableRow tableRowEven town">
                    <td class="titl">
                        <span><?php echo $section->language->lang058 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <select name="city" id="town">
                            <option value="0"><?php echo $section->language->lang071 ?></option>
                            <?php foreach($section->townlist as $town): ?>
                                <option value="<?php echo $town->id ?>" <?php if($town->sel!=0): ?>selected<?php endif; ?>><?php echo $town->name ?></option>
                            
<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="tableRow tableRowOdd addr">
                    <td class="titl">
                        <span><?php echo $section->language->lang059 ?>:</span>
                    </td> 
                    <td class="value">
                        <input type="text" class="inp" size="50" name="addr" value="<?php echo $_addr ?>">
                    </td>
                </tr> 
                <tr class="tableRow tableRowEven phone">
                    <td class="titl">
                        <span><?php echo $section->language->lang060 ?>:</span>
                    </td> 
                    <td class="value">
                        <input type="text" class="inp" size="50" name="phone" value="<?php echo $_phone ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd email important">
                    <td class="titl">
                        <font color='red'>*</font><span><?php echo $section->language->lang061 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" class="inp" size="50" name="email" value="<?php echo $_email ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven icq">
                    <td class="titl">
                        <span><?php echo $section->language->lang062 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="text" maxlength="12" class="inp" size="50" name="icq" value="<?php echo $_icq ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls">
                        <span><?php echo $section->language->lang063 ?>:</span>
                        <b class="mesRepPassw"><?php echo $mess_reppassword ?></b> 
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd pass1<?php if($_user==0): ?> important<?php endif; ?>">
                    <td class="titl">
                        
                        <?php if($_user==0): ?>
                            <font color='red'>*</font>
                            <span><?php echo $section->language->lang064 ?></span>
                        <?php else: ?>
                            
                                <span><?php echo $section->language->lang065 ?>:</span>
                            
                        <?php endif; ?>
                    </td> 
                    <td class="value">
                        <input type="password" class="inp" size="50" name="newpassword" value="">
                    </td>
                </tr> 
                <tr class="tableRow tableRowEven pass2<?php if($_user==0): ?> important<?php endif; ?>">
                    <td class="titl">
                        <?php if($_user==0): ?>
                            <font color='red'>*</font>
                        <?php endif; ?>
                        
                        <span><?php echo $section->language->lang066 ?>:</span>
                    </td> 
                    <td class="value"> 
                        <input type="password" class="inp" size="50" name="confirmpassword" value="">
                    </td>
                </tr> 
                <tr class="buttons">
                    <td colspan='2'> 
                        <input class="buttonSend next" type="submit"  name="GoToRekvEdit" value="<?php echo $section->language->lang072 ?>">
                        <?php if($_user!=0): ?>
                            <input class="buttonSend dels" onclick="document.location.href='<?php echo $delLinks3 ?>';" type="button" value="<?php echo $section->language->lang003 ?>">
                        <?php endif; ?>
                        <input class="buttonSend back" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->language->lang073 ?>"> 
                    </td> 
                </tr> 
            </tbody> 
        </table> 
    </form> 
</div> 


