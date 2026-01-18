<form id="Go" action="" method="post">
        <div class="ml001_err">
            <span class="m001_ertxt text-danger">[$ml001_errtxt]
<SE>
                <span class='sysedit'>[lang012]</span>
</SE>
            </span> 
        </div>
        <div class="form_mail">
            <div class="field_name form-group">
                <label class="field_title">[lang013]<span class="imp_point text-danger"> *</span></label> 
                <input class="field_inp form-control" name="name" title="&quot;[lang013]&quot;" value="[$ml001_name]" required>
            </div>
            <if:[param19]=='Y'>
            <div class="field_phone form-group">
                <label class="field_title">[lang027]<if:[param16]=='Y'><span class="imp_point text-danger"> *</span></if></label> 
                <input class="field_inp form-control" name="phone" title="&quot;[lang027]&quot;" value="[$ml001_phone]"<if:[param16]=='Y'> required</if>>
            </div>
            </if>
            <if:[param18]=='Y'> 
            <div class="field_email form-group">
                <label class="field_title">[lang014]<if:[param17]=='Y'><span class="imp_point text-danger"> *</span></if></label> 
                <input class="field_inp form-control" type="email" name="email" title="&quot;[lang014]&quot;" value="[$ml001_email]"<if:[param17]=='Y'> required</if>>
            </div> 
            </if>
            <if:[param20]=='Y'>
            <div class="field_note form-group">
                <label class="field_title">[lang015]</label> 
                <textarea class="field_area form-control" name="note" rows="5" wrap="virtual">[$ml001_note]</textarea> 
            </div> 
            </if>
            <if:[param13]!='No'>
                <div class="field_pin">
                    {$anti_spam}
                </div>
            </if>
            [subpage name=license]
            <input name="GoTo" <se>type="button" onclick="document.location.href='[link.subpage=1]'"</se> value="[lang017]" class="buttonSend" <serv>type="submit"</serv>>
        </div>
</form>
