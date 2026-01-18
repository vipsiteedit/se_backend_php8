 <footer:js> 
[js:jquery/jquery.min.js]
[js:jquery/jquery.printElement.js]
</footer:js>
<div class="content aPayee">
    <span class="blank">
        <?php echo $blank ?>
        
    </span>
    <div class="buttonArea back">
        <input class="buttonSend closepayee" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->language->lang004 ?>">
    <?php if($type=='p'): ?>
        <input class="buttonSend inpayee" value="<?php echo $section->language->lang003 ?>" type="button" onclick="$('.aPayee .blank').printElement({pageTitle:'<?php echo $thispagetitle ?>'});">
    <?php endif; ?>
    </div>
</div>
