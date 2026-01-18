<wrapper> [*addobj]
                    <repeat:records>  
                        <tr class="tableRow  ObjRow" vAlign="top" align="left">
                            <if:record.text1=="title"> 
                                <td colspan="2" class="objTitls">
                                    <noempty:record.image>                  
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText titletext">[*edobj][record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.image_alt]">
                                </td> 
                            </if>
                            <if:record.text1=="*string"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>                  
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.image_alt]">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="[record.field]" name="mformobj[record.id]" value="[record.note]" title="">
                                    <input type="hidden" name="mformcheck[record.id]" value="1">
                                </td> 
                            </if>
                            <if:record.text1=="*email"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.title]">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="[record.field]" name="email" value='[record.note]' title="[record.image_alt]">
                                </td> 
                            </if>
                            <if:record.text1=="email"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.title]">
                                </td> 
                            </if>
                            <if:record.text1=="string"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.image_alt]">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="[record.field]" name="mformobj[record.id]" value="[record.note]" title="">
                                </td> 
                            </if>
                            <if:record.text1=="*list"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]<span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle[record.id]" value="">
                                </td>
                                <td class="objArea">
                                    <select class="contentForm select" size="[record.field]" Name="mformobj[record.id]" title="">
                                        [textline.<option value="@textlineval" %SELECTED%>@textline</option> /textline]
                                    </select>
                                    <input type="hidden" name="mformcheck[record.id]" id="mformcheck[record.id]" value="1">
                                </td> 
                            </if>
                            <if:record.text1=="list"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>    
                                    <input type="hidden" name="mformtitle[record.id]" value="">
                                </td> 
                                <td class="objArea">
                                    <select class="select" class="contentForm" SIZE="[record.field]" Name="mformobj[record.id]">
                                        [textline.<option value="@textlineval" %SELECTED%>@textline</option> /textline]
                                    </select>
                                </td> 
                            </if>
                            <if:record.text1=="field"> 
                            
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="">
                                </td> 
                                <td class="objArea">
                                    <textarea class="textArea contentForm" name="mformobj[record.id]" rows="[record.field]" wrap="virtual">[record.note]</textarea> 
                                </td> 
                            </if>
                            <if:record.text1=="chbox"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="">
                                    <input type="hidden" name="ischeckbox[record.id]" value="1">
                                </td>
                                <td class="objArea">
                                    <div class="checkbx">
                                        <input class="contentForm objcheck" type="checkbox" name="mformobj[record.id]" value="[param25]" [record.text2]>
                                        <span class="objchecktext">[record.note]</span>
                                    </div> 
                                </td> 
                            </if>
                            <if:record.text1=="radio"> 
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.image_alt]">
                                </td> 
                                <td class="objArea">
                                    <div class="commonradio">
                                        [textline.<div class="radio"><input class="contentForm objRadio" type="radio" name="mformobj[record.id]" value="@textline_num @textlineval"%CHECKED%><span class="objRadiotext">@textline</span></div> /textline]
                                    </div>
                                </td> 
                            </if>
                            <if:record.text1=="file">
                                <td class="objTitl">[*edobj]
                                    <noempty:record.image>
                                        <img class="objectTitleImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_alt]">
                                    </noempty>
                                    <span class="objectTitleText">[record.title]</span>
                                    <input type="hidden" name="mformtitle[record.id]" value="[record.image_alt]">
                                    <input type="hidden" name="isfile[record.id]" value="1">
                                </td> 
                                <td class="objArea">
                                    <input type="file" class="contentForm objFile" name="mformobj[record.id]">
                                    <input type="hidden" name="isfile[record.id]" value="mformobj[record.id]">
                                </td> 
                            </if>
                        </tr> 
                    </repeat:records>
</wrapper>
