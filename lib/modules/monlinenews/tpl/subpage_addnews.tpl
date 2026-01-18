<?php if(file_exists($__MDL_ROOT."/php/subpage_addnewshead.php")) include $__MDL_ROOT."/php/subpage_addnewshead.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_addnewshead.tpl")) include $__data->include_tpl($section, "subpage_addnewshead"); ?>
<div class="content contOnNewsEdit add">
    <b class="errorText"><?php echo $errortext ?></b> 
    <form method="post" action="" enctype="multipart/form-data" style="margin:0px;">
        <table class="tableTable">
            <tbody> 
                
                <tr>
                    <td class="title"><?php echo $section->language->lang020 ?>*</td>
                    <td class="field">
                        <input class="date day" type="text" name="day" maxlength="2" size="2" value="<?php echo $_day ?>">
                        <input class="date month" type="text" name="month" maxlength="2" size="2" value="<?php echo $_month ?>">
                        <input class="date year" type="text" name="year" maxlength="4" size="4" value="<?php echo $_year ?>">  
                        <span class="publics">
                            <input class="control" name='publics' type='checkbox' <?php echo $checked ?>>
                            <span class="publics_txt"><?php echo $section->language->lang021 ?></span>
                        </span>      
                    </td> 
                </tr> 
                     
                <tr>
                    <td class="title">
                        <label for="titleNews"><?php echo $section->language->lang014 ?>*</label
                    </td>
                    <td class="field">
                        <input id="titleNews" type="text" name="title" value="<?php echo $_title ?>">
                    </td>
                </tr> 
                   
                <tr>
                    <td class="title">
                        <label for="userfile"><?php echo $section->language->lang015 ?></label>
                    </td>
                    <td>
                        <input id="userfile" class="field" type="file" name="userfile">
                    </td>
                </tr> 
                
                <tr>
                    <td class="title" valign="top" colspan="2"><?php echo $section->language->lang016 ?>*</td> 
                </tr>
                 
                <tr>
                    <td colspan="2">
                        <textarea style="width:100%;" rows="10" cols="40" id="edittar" name="text" class="field"><?php echo $_text ?></textarea>
                    </td>
                </tr> 
                
                <tr> 
                    <td colspan="2"></td> 
                </tr> 
                
                <tr> 
                    <td colspan="2">
                        <input class="buttonSend edSave" type="submit" name="Save" value="<?php echo $section->language->lang017 ?>">
                        <input class="buttonSend edBack" type="button" value="<?php echo $section->language->lang019 ?>" onclick="document.location='<?php echo seMultiDir()."/".$_page."/" ?>';">          
                    </td> 
                </tr> 
            </tbody> 
        </table>     
    </form> 
</div>
