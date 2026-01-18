<div class="content category addRecord">       
    
    <?php echo $errortext ?>
        <form method="post" name="frm" action="">
            <table class="tableTable useTabl1" border="0" cellSpacing="0" cellPadding="0">
               <tr class="tableRow tableHeader">
                    <td class="titl">                    
                        <div class="contentS1"><?php echo $section->language->lang014 ?>&nbsp;</div></td>
                    <td class="titl1">
                        <div class="contentS2">
                            <select style="WIDTH: 148px" name="upidctg" >
                                <option value="">
                                    <?php echo $section->language->lang015 ?>
                                </option>
                            <?php foreach($section->categories as $record): ?>
                                <option value="<?php echo $record->id ?>" <?php if($upid==$record->id): ?> selected <?php endif; ?> >                         
                                    <?php echo $record->name ?>                           
                                </option>
                            
<?php endforeach; ?> 
                            </select> 
                        </div>  
                    </td>
                </tr>                                  
            </table>
            <table class="tableTable useTabl2" border="0" cellSpacing="0" cellPadding="0">
                <tr class="tableRow tableHeader">
                    <td class="titl">&nbsp;</td>   
                    <td class="titl1">&nbsp;</td>                                            
                </tr>
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <div class="contentS3"><?php echo $section->language->lang009 ?></div>
                    </td>
                    <td class="titl1">
                        <div class="contentS4">
                            <input class="inptxt" name="namectg" value="<?php echo $namectg ?>">
                        </div>
                    </td>
                </tr>
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <div class="contentS3"><?php echo $section->language->lang010 ?></div>
                    </td>
                    <td class="titl1">
                        <div class="contentS4">
                            <input class="inptxt" name="urlctg" value="<?php echo $url ?>"> 
                        </div>
                    </td>
                </tr>
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <div class="contentS3"><?php echo $section->language->lang011 ?></div>
                    </td>
                    <td class="titl1">
                        <div class="contentS4">
                            <input class="inptxt" name="keywordctg" value="<?php echo $keywords ?>">
                        </div>
                    </td>
                </tr>             
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <div class="contentS3"><?php echo $section->language->lang012 ?></div>
                    </td>
                    <td class="titl1">
                        <div class="contentS4">
                            <input class="inptxt" type="textarea" name="descriptctg" value="<?php echo $description ?>">
                        </div>
                    </td>
                </tr>
            </table>
            <table class="tableTable useTable" border="0" cellSpacing="0" cellPadding="0">
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <input class="buttonSend edAddBtn1" name="AddCtg" value="<?php echo $section->language->lang013 ?>" type="submit"> 
                    </td>
                    <td class="titl">
                        <input class="buttonSend edAddBtn2" name="Close"  value="<?php echo $section->language->lang016 ?>" type="submit"> 
                    </td>
                    <?php if($btn): ?>
                    <td class="titl">
                        <input class="buttonSend edAddBtn3" name="Del" value="<?php echo $section->language->lang019 ?>" type="submit" onClick="return mess_del();">  
                    </td>
                    <?php endif; ?>
                    <script>
                        function mess_del(){
                            if(confirm('<?php echo $section->language->lang021 ?>')){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    </script>
                </tr>
            </table>  
        </form>
</div>
