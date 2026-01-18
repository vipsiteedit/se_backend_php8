<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<div class="content contRemember container" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
        <div class="obj name">
            <label><?php echo $section->language->lang008 ?></label>
            <span>
                <input size="30" name="name" value="<?php echo $name ?>" id="mypass">
            </span>
        </div>
        <div class="antiSpam">
            <img style="width:150px; height:30px" src="/lib/cardimage.php?session=<?php echo $sid ?>&<?php echo $time ?>" alt="">
            <label for="field_pin"><?php echo $section->language->lang009 ?></label>
            <input id="field_pin" maxlength="5" size="5" name="pin" type="text" title="<?php echo $section->language->lang009 ?>">
        </div>
        <div class="forgetPass"><?php echo $section->language->lang010 ?></div>
        <div class="buttonArea"> 
            <input class="buttonSend rembut" name="GoToRemember" type="button" onclick="getPass();"  value="<?php echo $section->language->lang011 ?>">
        </div>
    <script type="text/javascript">
        function getPass(){
            var pass = $('#mypass').val();     
            var pins = $('#field_pin').val();     
            $.post("?ajax<?php echo $section->id ?>_pass",{name: ""+pass, pin: ""+pins}, function(data){
                if(data=='OK'){
                    alert('<?php echo $section->language->lang002 ?> <?php echo $section->language->lang003 ?>');
                    document.location.href="<?php echo $path ?>";
                } else {
                    alert(data); 
                    $('#field_pin').val("");
                    document.location.reload();            
                }   
            });
        }
    </script>
    
</div>
