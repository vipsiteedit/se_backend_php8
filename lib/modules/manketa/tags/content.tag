<div class="content cont_ank"  [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" title="[part.image_title]" border="0" class="contentImage" src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div>
    </noempty>
    <noempty:part.text>
        <br clear="all">
    </noempty>
    <div id="anketa">
        [*addobj]
        <form style="margin:0px" id="Go" action="" method="post" enctype="multipart/form-data">
            <wrapper>
            <table width="100%" border="0px" cellPadding="0px" cellSpacing="0px" class="tableTable" id="objects">
                <tbody class="tableBody">
                    <tr class="tableRow" vAlign="top" align="left">
                        <td colspan="3" class="ank_tderr sysedit">
                            <font class="ank_ertxt">{$ank_err_text}<se>[lang001]</se></font> 
                        </td> 
                    </tr> 
                    <repeat:records>  
                        <tr class="ObjRow record-item" [objedit] vAlign="top" align="left">
                            <if:([record.text1]=="title" || [record.text1]=="")> 
                                <td [objedit] colspan="3" class="objTitls">
                                    <span>[*edobj][record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.image_alt]">
                                </td> 
                            </if>
                            <if:[record.text1]=="*string"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>                  
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.image_alt]">
                                </td>
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <input type="text" class="contentForm inputText" 
                                        size="[record.field]" name="formobj[record.id]" value="[record.note]" title="">
                                    <input type="hidden" name="formcheck[record.id]" value="1">
                                </td> 
                            </if>
                            <if:[record.text1]=="*email"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.title]">
                                </td>
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <input type="text" class="inputText" size="[record.field]" 
                                        name="email" value='[record.note]' title="[record.image_alt]">
                                </td> 
                            </if>
                            <if:[record.text1]=="email"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.title]">
                                </td> 
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <input type="text" class="inputText" size="[record.field]" 
                                        name="email" value='[record.note]' title="[record.image_alt]">
                                </td> 
                            </if>
                            <if:[record.text1]=="string"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.image_alt]">
                                </td>
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <input type="text" class="inputText" size="[record.field]" 
                                        name="formobj[record.id]" value="[record.note]" title="">
                                </td> 
                            </if>
                            <if:[record.text1]=="*list"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle[record.id]" value="">
                                </td>
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td> 
                                <td [objedit] class="objArea">
                                    <select class="contentForm select" size="[record.field]" Name="formobj[record.id]" title="">
                                        [textline.<option value="@textlineval" %SELECTED%>@textline</option> /textline]
                                    </select>
                                    <input type="hidden" name="formcheck[record.id]" id="formcheck[record.id]" value="1">
                                </td> 
                            </if>
                            <if:[record.text1]=="list"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>    
                                    <input type="hidden" name="formtitle[record.id]" value="">
                                </td> 
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <select class="contentForm select" size="[record.field]" Name="formobj[record.id]">
                                        [textline.<option value="@textlineval" %SELECTED%>@textline</option> /textline]
                                    </select>
                                </td> 
                            </if>
                            <if:[record.text1]=="field"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="">
                                </td> 
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <textarea class="textArea" name="formobj[record.id]" rows="[record.field]" wrap="virtual">[record.note]</textarea> 
                                </td> 
                            </if>
                            <if:[record.text1]=="chbox"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="">
                                    <input type="hidden" name="ischeckbox[record.id]" value="1">
                                </td>
                                <td width="1%" [objedit] class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <div class="checkbx">
                                        <input class="objcheck" type="checkbox" name="formobj[record.id]" value="[param43]" [record.text2]>
                                        <span class="objchecktext">[record.note]</span>
                                    </div> 
                                </td> 
                            </if>
                            <if:[record.text1]=="radio"> 
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.image_alt]">
                                </td> 
                                <td [objedit] width="1%" class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <div class="radioblock">
                                        [textline.
                                            <div class="radio">
                                                <input class="contentForm objRadio" type="radio" name="formobj[record.id]" value="@textline_num @textlineval"%CHECKED%>
                                                <span class="objRadiotext">@textline</span>
                                            </div> 
                                        /textline]
                                    </div>                            
                                </td> 
                            </if>
                            <if:[record.text1]=="file">
                                <td width="30%" [objedit] class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="formtitle[record.id]" value="[record.image_alt]">
                                    <input type="hidden" name="isfile[record.id]" value="1">
                                </td> 
                                <td [objedit] width="1%" class="objHSpace">&nbsp;</td>
                                <td [objedit] class="objArea">
                                    <input type="file" class="objFile" name="formobj[record.id]">
                                </td> 
                            </if>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td> 
                        </tr> 
                    </repeat:records>
                </tbody> 
            </table>
            </wrapper> 
            <table width="100%" border="0" cellPadding="0" cellSpacing="0" class="tableTable" id="general">
                <tbody class="tableBody">
                    <if:[param21]!='No'> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl namettl">[lang012]<if:[param23]!='No'><span class="star" style="color:red">*</span></if></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputName" name="name" title="&quot;[lang012]&quot;" value="{$name}">
                            </td> 
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div> 
                            </td> 
                        </tr> 
                    </if>
                    <if:[param24]!='No'> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl addressttl">[lang013]<if:[param26]!='No'><span class="star" style="color:red">*</span></if></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputAddress" name="address" title="&quot;[lang013]&quot;" value="{$address}">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    </if>
                    <if:[param27]!='No'>
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl phonettl">[lang014]<if:[param29]!='No'><span class="star" style="color:red">*</span></if></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputPhone" name="phone" title="&quot;[lang014]&quot;" value="{$phone}">
                            </td>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr>
                    </if>
                    <if:[param38]!='No'> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl emailttl">[lang015]<if:[param39]!='No'><span class="star" style="color:red">*</span></if></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputEmail" name="email" title="&quot;[lang015]&quot;" value="{$email}">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    </if>
                    <if:[param14]!='No'> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl pinttl">
                                    [lang011]
                                    <span class="star" style="color:red">*</span>
                                </span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea" id="ank_chimg">
                                {$anti_spam}
                            </td> 
                        </tr> 
                    </if>   
                    <if:[param55]=='Y' || [param57]=='Y'>
                        <tr>
                            <td colspan="3">[subpage name=license]</td>
                        </tr>
                    </if> 
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr> 
                    <tr> 
                        <td colspan="3" class="ank_tdbtn">
                            <input name="GoTo[part.id]" <se>type="button" onclick="document.location.href='[@subpage1]'"</se> value="[lang017]" class="buttonSend" [#"type="submit""]>
                        </td> 
                    </tr> 
                </tbody> 
            </table> 
        </form> 
        <SE> 
            <br clear="all">
            <div <if:[param1]!=''>class="sysedit"</if> style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black;">
                <b>[lang002]<br>[lang003]&nbsp;[param1]</b> 
            </div> 
        </SE> 
    </div> 
</div>
