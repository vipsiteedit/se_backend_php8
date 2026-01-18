<div class="content cont_golos_txt" [contedit]>
<noempty:part.title><a name="[part.id]"></a> <[part.title_tag] class="contentTitle"[part.style_title]>
  <span class="contentTitleTxt">[part.title]</span> </[part.title_tag]> </noempty>
<noempty:part.image>
   <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]">
</noempty>
<noempty:part.text><div class="contentText" [part.style_text]>[part.text]</div> </noempty>
[*addobj]
    <form action="[thispage.link]" method="post" style="margin:0px;">
        <table border="0" cellpadding="3" cellspacing="0" class="object">
        <tbody class="tableBody">
        <serv><if:({$show}==0)></serv> 
        <repeat:records>
             <tr class="votingtr" [objedit]>
                 <td vAlign="middle" class="radiotd">
                     <input class="votingradio" type="radio" name="voting_radio" value="[record.id]">
                 </td> 
                 <td vAlign="middle"  class="titletd">
                     <span class="objectItem" [objedit]>[*edobj][record.title]</span>
                 </td>               
             </tr>
        </repeat:records>
        <serv><else></serv>
        <se>
        </tbody></table>
        <table border="0" cellpadding="3" cellspacing="0" class="object">
        <tbody class="tableBody">
        </se>
            <tr><td colspan=2 style="padding-left:10px;">
            <font color="[param4]"><b class="restitle">[param3]: {$summ}</b></font></td></tr>
        <repeat:records>
             <tr>
                 <td style="padding-left:10px;">
                     <font class="restext" color="[record.field]">[record.title]</font>
                 </td>
                 <td>
                     <span style="width:30px; color:[record.field];" class="restext">[record.res]%</span>
                 </td>
            </tr>
        </repeat:records>
        <serv></if></serv>
            <tr>
                <td colspan='2' style="font-size:5px;height:5px;">&nbsp;</td> 
            </tr> 
            <tr> 
                <td colspan="2" vAlign="middle" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" class="areaButton">
                        <tr> 
                            <td>
                                <input name="GoTo_VOTING" class="buttonSend buttonVoting" 
                                    {$voting_text->buttonstyle} <SERV>type='submit'</SERV><SE>type='button'</SE> value="[param5]">
                            </td> 
                            <td>&nbsp;</td> 
                            <td>
                                <input name="GoTo_SHOW" class="buttonSend buttonResult" 
                                    {$voting_text->buttonstyle} <SERV>type='submit'</SERV><SE>type='button'</SE> value="[param6]">
                                <input type="hidden" name='razdel' value='[part.id]'>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr> 
        </tbody>
    </form>
</table>
</div> 
