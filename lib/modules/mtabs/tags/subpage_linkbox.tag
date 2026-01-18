<table cellspacing="0" cellpadding="0">
    <if:[param1]=='Y'><tr></if>
<repeat:records>       
        <if:record.id!=[record.first]><if:[param1]=='N'><tr></if><td class="sep">&nbsp;</td><if:[param1]==N></tr></if></if>
        <if:[param1]=='N'><tr></if><td class="tab <serv> <if:{$obj}==0><if:"[record.id]"=="[record.first]"> active</if><else><if:[record.id]=={$obj}>active 2</if></if></serv> <se><if:record.id==[record.first]> active</if></se>">
            <table cellspacing="0" cellpadding="0" width="100%" class="cell">
            <tr>
                <td class="left"></td>
                <td class="center"> 
                    <a class="link obj[record.id]" <if:[param2]=='N'>rel="nofollow"</if> href="<se>#r[part.id]obj[record.id]</se><serv>{$thispage}/obj/[record.id]/</serv>">[record.title]<empty:record.title>[lang001]</empty></a>
                </td>
                <td class="right"></td>
            </tr>
            </table>
        </td>
        <if:[param1]=='N'></tr></if>
</repeat:records>
    <if:[param1]=='Y'></tr></if>
</table>
