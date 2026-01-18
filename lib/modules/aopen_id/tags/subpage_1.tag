<div class="content openid sub1 row">
    <form action="<se>[thispage.link]</se>" method="post">
        <input type="hidden" name="keys" value="{$req['key']}">
        <input type="hidden" name="fnames" value="{$req['first_name']}">
        <input type="hidden" name="lnames" value="{$req['last_name']}">
        <input type="hidden" name="inden" value="{$req['identity']}">
        <input type="hidden" name="prov" value="{$req['provider']}">
        <input type="hidden" name="ava" value="{$req['avatar']}">
        <div class="form-group">
            <label for="mailbox">[param5]</label>
            <input type="email" name="emails" class="form-control mailboxinp" id="mailbox" placeholder="Email">
        </div>
        <button type="submit" class="btn btn-primary mailboxsubm disabled"  name="sending">[param7]</button>
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
