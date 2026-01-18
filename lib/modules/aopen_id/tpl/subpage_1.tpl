<div class="content openid sub1 row">
    <form action="" method="post">
        <input type="hidden" name="keys" value="<?php echo $req['key'] ?>">
        <input type="hidden" name="fnames" value="<?php echo $req['first_name'] ?>">
        <input type="hidden" name="lnames" value="<?php echo $req['last_name'] ?>">
        <input type="hidden" name="inden" value="<?php echo $req['identity'] ?>">
        <input type="hidden" name="prov" value="<?php echo $req['provider'] ?>">
        <input type="hidden" name="ava" value="<?php echo $req['avatar'] ?>">
        <div class="form-group">
            <label for="mailbox"><?php echo $section->parametrs->param5 ?></label>
            <input type="email" name="emails" class="form-control mailboxinp" id="mailbox" placeholder="Email*">
        </div>
        <button type="submit" class="btn btn-primary mailboxsubm disabled"  name="sending"><?php echo $section->parametrs->param7 ?></button>
    </form>
</div>
<script type="text/javascript"> 
    $(document).ready(function() { 
        $('.mailboxinp').on('keyup', function(){    
            var txt = $('.mailboxinp').val();    console.log(txt);  
            if(txt == ''){                         
                if(!$('button.mailboxsubm').hasClass('disabled')) 
                    $('button.mailboxsubm').addClass("disabled");                
            } else {                                
                $('button.mailboxsubm').removeClass("disabled");
            };
        });
    });
</script>  
