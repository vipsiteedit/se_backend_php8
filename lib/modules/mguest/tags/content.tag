<div class="content cont_guest part[part.id]" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle">
            <span ondblclick="document.location.href='[link.subpage=edit]';" class="contentTitleTxt se-login-modal">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <if:{$userAccess}==3>
    <div class="block-link-admin">
        <ul>
        <li><a href="[link.subpage=block]" class="blockip">[lang020]</a></li>
        <li><a href="[link.subpage=noconfirm]" class="noconfirm">[lang041]</a></li>
        </ul>
    </div>
    </if> 
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" value="{$usrcode}" name="usrcode">
        <div id="guest">
            <div id="comments">
                <if:{$addcomment}><h4 style="color: red;" class="info">[lang042]</h4></if>
                <repeat:comments name=com>
                    <div class="comm">
                        <div class="userdat">
                            <a class="adm_lnk" href="[link.subpage=edit]?id=[com.id]"> »</a>
                            <label class="date">
                                [com.date]
                                <if:{$admin}==1>
                                    &nbsp;[<if:[com.active]=='Y'><b style="color: green;">Подтвержден
                                    <else><b style="color: red;">Не подтвержден</if>
                                    </b>]    
                                </if></label>
                        
                            <if:{$userAccess}!=0>
                                <a class="name" href="<SE>#</SE><SERV>mailto:[com.usrmail]</SERV>" name=record[com.id]>[com.usrname]</a>
                            <else>
                                <span class="name">[com.usrname]</span>
                            </if>
                        </div>
                        <div class="com_txt">[com.usrnote]</div>
                        <if:[com.admtext]!=''>
                            <div class="adm_txt">
                                <label class="admin_label">[lang005]</label>
                                <div class="admtext">[com.admtext]</div>
                            </div>
                        </if>
                    </div>
                </repeat:comments>
                <if:{$countpage}!=0>
                    <div class="pageselector" id="navPart">
                        <if:{$pagen}==1>
                            <b class="activpg arr" id="Back">&larr;</b>
                            <b class="activpg Active">1</b>
                        <else>
                            <a class="pagesel arr" id="Back" href="<SE>#</SE><SERV>?p={$prevpage}</SERV>">&larr;</a>
                            <a class="pagesel links" href="<SE>#</SE><SERV>?p=1</SERV>">1</a>
                        </if>
                        <if:{$pgbegin}!=2>
                            <span class="points lpoints">...</span>
                        </if>
                        <repeat:pages name=pg>
                            <if:[pg.sel]==0>
                                <a class="pagesel links" href="<SE>#</SE><SERV>?p=[pg.pg]</SERV>">[pg.pg]</a>
                            <else>
                                <b class="activpg Active">[pg.pg]</b>
                            </if>
                        </repeat:pages>
                        <if:{$pgpoint_end}!=0>
                            <span class="points rpoints">...</span>
                        </if>
                        <if:{$nextpage}==0>
                            <b class="activpg Active">{$maxpage}</b>
                            <b class="activpg arr" id="Next">&rarr;</b>
                        <else>
                            <a class="pagesel links" href="<SE>#</SE><SERV>?p={$maxpage}</SERV>">{$maxpage}</a>
                            <a class="pagesel arr" id="Next" href="<SE>#</SE><SERV>?p={$nextpage}</SERV>">&rarr;</a>
                        </if>
                    </div>
                </if>     
            </div>
            <table class="tableTable" id="guesttab" width="400">
                <if:{$usrblock}!=''>
                    <div class="supererror<SE> sysedit</SE>">
                        <span>{$usrblock}</span>
                    </div> 
                </if>
                <tbody>
                    <tr>
                        <td width="40%" class="tablrow tabltext">
                            <span class="titleTab nameTtl">[lang027]</span>
                        </td>               
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp [$errstname] nameInp" value="[$usrname_inform]" name="usrname">
                            <if:{$errorname}!=''>
                                <div class="error<SE> sysedit</SE>">
                                    <span>{$errorname}</span>
                                </div>
                            </if>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow tabltext">
                            <span class="titleTab emailTtl">[lang026]</span>
                        </td>                
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp [$errstmail] emailInp" value="[$usrmail_inform]" name="usrmail">
                            <if:{$errormail}!=''>
                                <div class="error<SE> sysedit</SE>">
                                    <span>{$errormail}</span>
                                </div>
                            </if>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <label class="titleTab commentTtl">[lang025]</label>
                            <br>
                            <textarea id="textar" class="inp [$errstnote]" name="note" rows="7" maxlength="[param35]" cols="36">[$usrnote_inform]</textarea>
                            <if:{$errornote}!=''>
                                <div class="error sysedit">{$errornote}</div>
                            </if> 
                        </td>                  
                    </tr>
                    <if:[param2]=="Yes">
                        <tr> 
                            <td colspan="2" class="tablrow">
                                <serv>{$anti_spam}</serv>
                                <se><img src="[system.path]img\captcha.png"></se>
                            </td> 
                        </tr>
                    </if>
                    <tr>
                        <td class="tablrow" colspan="2">
                            [subpage name=license]
                            <if:{$errorlicense}!=''>
                                <div class="error<SE> sysedit</SE>">
                                    <span>{$errorlicense}</span>
                                </div>
                            </if>
                            <input type=<SE>"button"</SE><SERV>"submit"</SERV> value="[lang024]" name="SaveGuest[part.id]" id="but" class="buttonSend">
                        </td>
                    </tr>
                </tbody>
            </table>
            <SE>
                <br clear="all">
                <div <if:[param1]!=''>class="sysedit"</if> style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; "><b> [lang007]<br> [lang008][param1]</b> </div>
            </SE>
        </div>
    </form> 
</div>
