<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript">
    function loadBox(id, name, value) {
        $('#'+id).load("/<?php echo $_page ?>/<?php echo $razdel ?>/sub1/"+name+"/"+value,{});
    } 
</script>
<noindex>
<div class="content user_pers" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="warning sysedit">
        <?php echo $rekv_message ?>
        
    </div> 
    <div class="ok_mess sysedit">
        <?php echo $ok_mess ?>
        
    </div> 
    <form style="margin:0px;" action="" method="post">
        <table border="0" cellPadding="0" cellSpacing="0" class="tableTable">
            <tbody class="tableBody">
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls"><?php echo $section->language->lang004 ?></td>
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang005 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="last_name" value="<?php echo $last_name ?>"></td>
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang006 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="first_name" value="<?php echo $first_name ?>"></td>
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang007 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="sec_name" value="<?php echo $sec_name ?>"></td>
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang031 ?></td> 
                    <td class="value"> 
                        <select name="sex" onChange="loadBox('namegroup','group',this.value);">
                            <option <?php echo $groupoption3 ?> value="N">--</option>
                            <option <?php echo $groupoption1 ?> value="M"><?php echo $section->language->lang032 ?></option> 
                            <option <?php echo $groupoption2 ?> value="F"><?php echo $section->language->lang033 ?></option> 
                        </select> 
                    </td>
                </tr>
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls"><?php echo $section->language->lang008 ?></td>
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang009 ?></td> 
                    <td class="value"><input type="text" maxlength="2" class="inp" size="2" name="b_day" value="<?php echo $b_day ?>"></td> 
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang010 ?></td> 
                    <td class="value"><input type="text" class="inp" maxlength="2" size="2" name="b_month" value="<?php echo $b_month ?>"></td> 
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang011 ?></td> 
                    <td class="value"><input type="text" class="inp" maxlength="4" size="4" name="b_year" value="<?php echo $b_year ?>"></td>
                </tr> 
                <?php if($section->parametrs->param22=="Y"): ?>
                    <tr class="tableRow tableHeader">
                        <td colspan="2" class="titls"><?php echo $section->language->lang023 ?></td>
                    </tr> 
                    <tr class="tableRow tableRowOdd">
                        <td class="titl"><?php echo $section->language->lang024 ?></td> 
                        <td class="value"><input type="text" class="inp" maxlength="10" size="10" name="doc_ser" value="<?php echo $doc_ser ?>"></td> 
                    </tr> 
                    <tr class="tableRow tableRowEven">
                        <td class="titl"><?php echo $section->language->lang025 ?></td>
                        <td class="value"><input type="text" class="inp" maxlength="10" size="10" name="doc_num" value="<?php echo $doc_num ?>"></td> 
                    </tr>
                    <tr class="tableRow tableRowOdd">
                        <td class="titl"><?php echo $section->language->lang026 ?></td> 
                        <td class="value"><input type="text" class="inp" maxlength="250" size="40" name="doc_registr" value="<?php echo $doc_registr ?>"></td> 
                    </tr>
                <?php endif; ?>
                <tr class="tableRow tableHeader">
                    <td colspan="2" class="titls"><?php echo $section->language->lang012 ?></td> 
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang013 ?></td> 
                    <td class="value"> 
                        <input size="6" type="text" class="inp" name="post_index" value="<?php echo $post_index ?>">
                    </td> 
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang014 ?></td> 
                    <td class="value"> 
                        <select name="country" onChange="loadBox('nameregion','region',this.value);">
                            <?php echo $countryhtml ?>
                            
                        </select>
                    </td> 
                </tr> 
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang015 ?></td> 
                    <td class="value"> 
                        <div id="nameregion">
                            <select name="state" onChange="loadBox('nametown','town',this.value);">
                                <?php echo $regionhtml ?>
                                
                            </select>
                        </div>
                    </td>
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang016 ?></td> 
                    <td class="value"> 
                        <div id="nametown">
                            <select name="city">
                                <?php echo $townhtml ?>
                                
                            </select>
                        </div>
                    </td> 
                </tr> 
  
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang017 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="addr" value="<?php echo $addr ?>"></td>
                </tr> 
  
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang018 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="phone" value="<?php echo $phone ?>"></td>
                </tr> 
  
                <tr class="tableRow tableRowOdd">
                    <td class="titl"><?php echo $section->language->lang019 ?></td> 
                    <td class="value"><input size="40" type="text" class="inp" name="email" value="<?php echo $email ?>"></td>
                </tr> 
                <tr class="tableRow tableRowEven">
                    <td class="titl"><?php echo $section->language->lang020 ?></td> 
                    <td class="value"><input size="30" type="text" class="inp" name="icq" value="<?php echo $icq ?>"></td>
                </tr> 
                <tr class="tableRow">
                    <td class="buttontd" colspan="2">
                        <input type="submit"  class="buttonSend" name="GoToPers" value="<?php echo $section->language->lang021 ?>">
                    </td>
                </tr>
            </tbody>
        </table> 
    </form> 
</div> 
</noindex>
