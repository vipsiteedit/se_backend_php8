<div class="content adminUserList deletRecord">
    <div class="mesError"><?php echo $messagetext ?></div> 
    <span class="contentTitles"><?php echo $section->language->lang002 ?> <?php echo $_user ?>?</span> 
    <form name="frm" action="" method="POST">
        <input type="hidden" name="user" value="<?php echo $_user ?>">
        <input class="buttonSend del" type="submit"  name="GoToRekvDelete" value="<?php echo $section->language->lang003 ?>">
        <input class="buttonSend back" onclick="document.location='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>user/<?php echo $_user ?>/';" type="button" value="<?php echo $section->language->lang004 ?>">
    </form> 
</div> 


