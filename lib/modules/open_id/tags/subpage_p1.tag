<footer:js>   
[js:jquery/jquery.min.js]
[include_js]
</footer:js>
<div class="content openid sub1">
    <form action="" method="post">
        <span class="mailbox">[param5]</span>
        <input type="hidden" name="keys" value="{$req['key']}">
        <input type="hidden" name="fnames" value="{$req['first_name']}">
        <input type="hidden" name="lnames" value="{$req['last_name']}">
        <input type="hidden" name="inden" value="{$req['identity']}">
        <input type="hidden" name="prov" value="{$req['provider']}">
        <input type="hidden" name="ava" value="{$req['avatar']}">
        <input type="text" name="emails" class="mailboxinp">
        <input [#"type="submit""] [se."type="button" onclick="document.location.href='[thispage.link]'""] class="mailboxsubm buttonsend" value="[param7]" name="sending" disabled>
    </form>
</div>
