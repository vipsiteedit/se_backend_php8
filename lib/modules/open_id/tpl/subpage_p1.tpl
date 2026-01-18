<footer:js>   
[js:jquery/jquery.min.js]
[include_js]
</footer:js>
<div class="content openid sub1">
    <form action="" method="post">
        <span class="mailbox"><?php echo $section->parametrs->param5 ?></span>
        <input type="hidden" name="keys" value="<?php echo $req['key'] ?>">
        <input type="hidden" name="fnames" value="<?php echo $req['first_name'] ?>">
        <input type="hidden" name="lnames" value="<?php echo $req['last_name'] ?>">
        <input type="hidden" name="inden" value="<?php echo $req['identity'] ?>">
        <input type="hidden" name="prov" value="<?php echo $req['provider'] ?>">
        <input type="hidden" name="ava" value="<?php echo $req['avatar'] ?>">
        <input type="text" name="emails" class="mailboxinp">
        <input type="submit"  class="mailboxsubm buttonsend" value="<?php echo $section->parametrs->param7 ?>" name="sending" disabled>
    </form>
</div>
