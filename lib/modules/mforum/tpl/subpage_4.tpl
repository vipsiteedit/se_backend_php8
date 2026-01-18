<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<script type='text/javascript'>
    var part1='';
    var selprt;             
    var part2='';
    var sel=false;
    var colormenu=true;
    function getTextData () {
        var obj=document.getElementById("erm_AreaForText"); 
        if($.browser.msie){ 
            var range=document.selection.createRange();
            var stored_range=range.duplicate();
            stored_range.moveToElementText(obj);
            stored_range.setEndPoint('EndToEnd',range);
            obj.selectionStart=stored_range.text.length-range.text.length;
            obj.selectionEnd=obj.selectionStart+range.text.length;
        }
        selprt=obj.value.substring(obj.selectionStart,obj.selectionEnd);  
        part1=obj.value.substring(0,obj.selectionStart);
        part2=obj.value.substring(obj.selectionEnd,obj.value.length);
    }
    function www(typik){
        if(typik=="mail"){
            var mail=prompt("<?php echo $section->language->lang117 ?>","");
            if (mail) {
                if(sel){
                    part1=part1+'[mailto='+mail+']'+selprt+'[/mailto]';
                    $("#erm_AreaForText").val(part1+part2);
                }else{
                    part1=$("#erm_AreaForText").val()+'[mailto='+mail+']'+mail+'[/mailto]';
                    $("#erm_AreaForText").val(part1);
                }
            }
        }else if(typik=="img"){
            var url=prompt("<?php echo $section->language->lang118 ?>", "http://");
            if (url) {
                if(sel){
                    part1=part1+'[a href='+url+']'+selprt+'[/a]';
                    $("#erm_AreaForText").val(part1+part2);
                }else{
                    part1=$("#erm_AreaForText").val()+'[img src='+url+']';
                    $("#erm_AreaForText").val(part1);
                }
            }
        }else{
            var url=prompt("<?php echo $section->language->lang119 ?>","http://");
            if (url) {
                if(sel){
                    part1=part1+'[a href='+url+']'+selprt+'[/a]';
                    $("#erm_AreaForText").val(part1+part2);
                }else{
                    part1=$("#erm_AreaForText").val()+'[a href='+url+']'+url+'[/a]';
                    $("#erm_AreaForText").val(part1);
                }
            }
        }
        sel=false;
    }
    function editText(tag){
        var text='';
        if(sel){
            part1=part1+'['+tag+']'+selprt+'[/'+tag+']';
            $("#erm_AreaForText").val(part1+part2);
        } else {
            alert (what);
        }
        sel=false;
    }
    function AddSmile(smile){
        part1=part1+smile;
        $("#erm_AreaForText").val(part1+part2);
        sel=false;
    }
    function clr_add(color){
        if(sel){
            part1=part1+'[COLOR='+color+']'+selprt+'[/COLOR]';
            $("#erm_AreaForText").val(part1+part2);
        }else{
            $("#erm_AreaForText").val($("#erm_AreaForText").val()+'[COLOR='+color+']'+'[/COLOR]');    
        }
        $("#color_div").css("visibility","hidden");
        sel=false;
        colormenu=true;
        return false;
    }
    function clr_f(color){
        $("#clr").val(color);
        $("#clr_div").css("background-color",color);
    }
    function addFile(name, img) {
        if (img) {
            part1=part1+'[attimg src='+name+']';
        } else {
            part1=part1+'[attfile src='+name+']';
        }
        $("#erm_AreaForText").val(part1+part2);
        sel=false;
    }
    function delThisFile (name, id) {
        if (confirm ("<?php echo $section->language->lang130 ?>" + ' ' + name + '?')) {
            document.location = "?delfile=" + id;
        }
    }
    $(function(){ 
        $("#erm_AreaForText").bind("keypress", 
            function(){
                $("#txtCount").val(<?php echo $msgMaxLength ?>-$("#erm_AreaForText").val().length);
                getTextData();
                sel=false;
            }
        );
        $("#erm_AreaForText").bind("focus blur mouseout change",
            function(){
                $("#txtCount").val(<?php echo $msgMaxLength ?>-$("#erm_AreaForText").val().length);
            }
        );
        $("#erm_AreaForText").bind("select",
            function(){
                getTextData();
                sel=true; 
            }
        );  
        $("#erm_AreaForText").click(
            function(){
                if (!colormenu) {
                    $("#color_div").css("visibility","hidden");
                    colormenu=true;
                }
                getTextData();
            }
        );              
        $("#erm_Add").click(
            function(){
                if(!$("#erm_AreaForText").val().length){
                    alert('<?php echo $section->language->lang120 ?>');
                }else if($("#erm_AreaForText").val().length><?php echo $msgMaxLength ?>){
                    alert('<?php echo $section->language->lang121 ?>');
                }else{
                    $("#doGo").val(1);
                    $("#form").submit();
                }
            }
        );
        $("#erm_PopUp").click(
            function(){
                if (colormenu) {
                    $("#color_div").css("visibility","visible");
                } else {
                    $("#color_div").css("visibility","hidden");
                }
                colormenu=!colormenu;
            }
        );

    });
</script>
<div class="content forum">
    <?php if(file_exists($__MDL_ROOT."/php/subpage_19.php")) include $__MDL_ROOT."/php/subpage_19.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_19.tpl")) include $__MDL_ROOT."/tpl/subpage_19.tpl"; ?>
    <?php if($enable==1): ?>
        <div id="message_warning"><?php echo $section->language->lang131 ?></div>
    <?php else: ?>
        <?php if($enable==2): ?>
            <div id="message_warning"><?php echo $section->language->lang132 ?>&nbsp;<?php echo $haltView ?>&nbsp;<?php echo $section->language->lang133 ?></div>
        <?php else: ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_21.php")) include $__MDL_ROOT."/php/subpage_21.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_21.tpl")) include $__MDL_ROOT."/tpl/subpage_21.tpl"; ?>
            <!--Начало тела страницы-->
            <?php if($uid==0): ?>
                <div id='message_warning'>
                    <?php if($newt==0): ?>
                        <?php echo $section->language->lang157 ?>
                    <?php else: ?>
                        <?php echo $section->language->lang189 ?>
                    <?php endif; ?>
                </div>
                <div id='butlayer'>
                    <input class='buttonSend' id='btBack' type='button' onclick="javascript:history.go(-1)" value='<?php echo $section->language->lang126 ?>'>
                </div>
            <?php else: ?>
                <?php if($error_exists!=0): ?>
                    <div id='message_warning'>
                        <?php echo $error ?>
                    </div>                                         
                    <div id='butlayer'>
                        <input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' value="<?php echo $section->language->lang126 ?>">
                    </div>
                <?php else: ?>
                    <?php if($personal==0): ?>
                        <div id="forumPath">
                            <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>?id=<?php echo $areaId ?>' id='forums'><?php echo $fArea ?></a>
                            <span class="divider"><?php echo $section->parametrs->param14 ?></span>
                            <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $forumId ?>' id='themes'><?php echo $forumName ?></a>
                            <?php if($newt==0): ?>
                                <span class="divider"><?php echo $section->parametrs->param14 ?></span>
                                <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $topicID ?>' id='notheme'><?php echo $topic ?></a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div id='PersonalBlock'>
                            <span id='PersonalTitle'>
                                <?php echo $section->language->lang020 ?>&nbsp;<?php echo $section->language->lang021 ?>&nbsp;<?php echo $section->language->lang022 ?>&nbsp;
                                <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub17/" ?>id/<?php echo $personal ?>/' id='PersonNick'><?php echo $to_whom ?></a>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if($ipart==1): ?>
                        <table class='tableForum' id='tableTopic'>
                            <tbody class='tableBody'>
                                <tr>
                                    <td colspan=2 id='mess_MessTheme' class='title'>
                                        <div id='mess_ThemeName'><?php echo $qtopic ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='title' id='title_ShowUserMess'>
                                        <div id='mess_ShowUserMess'>
                                            <a id='mess_showTopicAuthorNick' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $q_id_users ?>'><?php echo $qnick ?></a>
                                            <?php if($img_exists!=0): ?>
                                                <img id='mess_showTopicAuthorImg' src="/modules/forum/images/<?php echo $img ?>" >
                                            <?php endif; ?>
                                            <div id='mess_showTopicAuthorStatus'><?php echo $qstatus ?></div>
                                            <div id='topic_showTopicLoc'><?php echo $qlocation ?></div>
                                        </div>
                                    </td>
                                    <td class='field' id='field_ShowUserMess'>
                                        <div id='mess_MessageText'>     
                                            <div id='mess_showTopicMsgDate'><?php echo $qdate ?></div> 
                                            <div id=mess_showTopicMsgText><?php echo $qtext ?></div>
                                            <?php if($date_time_edit_exists!=0): ?>
                                                <br>
                                                <div id='edit'><?php echo $section->language->lang097 ?>&nbsp;<?php echo $date_time_edit ?></div>
                                            <?php endif; ?>
                                            <?php if($moderator_edit_exists!=0): ?>
                                                <br>
                                                <div id='moder'><?php echo $section->language->lang098 ?>&nbsp;<?php echo $moderator_edit ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div id='mess_showTopicMsgText'></div>
                    <?php endif; ?>
                    <!--a name='edit'></a-->
                    <form method='post' id='form' name='form' enctype='multipart/form-data' action="">
                        <?php if($createt!=0): ?>
                            <div id='erm_ThemeName'>
                                <span id='NewTheme'><?php echo $section->language->lang023 ?></span> 
                                <input class='inputForum' id='erm_ThemeText' type='text' maxlength='<?php echo $msgMaxLengthTopic ?>' name='topic_nm' value='<?php echo $topic ?>'>
                            </div>
                        <?php endif; ?>
                        <table class='tableForum' id="tableERM" border=0>
                            <tbody class='tableBody'>
                                <tr>
                                    <td id='erm_Buttons' colspan=2>
                                        <div id='erm_ClrManag'>
                                            <input class='inputForum' type='text' id='clr' readonly maxlength=7>
                                            <input class='buttonSend' id='erm_PopUp' type='button' value='<?php echo $section->language->lang158 ?>'>
                                            <div id="color_div" style="visibility:hidden;">
                                                <table cellspacing=0 cellpadding=0 class=clr_tab onmouseout="clr_f('');">
                                                    <tbody>
                                                        <?php foreach($section->colors as $color): ?>
                                                            <tr>
                                                                <?php $__list = 'color'.$color->i; foreach($section->$__list as $colori): ?>
                                                                    <td>
                                                                        <input type="button" onclick="clr_add('<?php echo $colori->color ?>'); return false;" onmousemove="clr_f('<?php echo $colori->color ?>');" style="background-color:<?php echo $colori->color ?>; width:10px; height:10px; сursor: pointer; border: 0px;" value=''>
                                                                    </td>
                                                                
<?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id='clr_div' style='background-color: ffffff;'></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id='erm_allButtons'>
                                            <button class='buttonsBlock' onclick="editText('b');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/b.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('em');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/i.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('u');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/u.gif'>
                                            </button>                   
                                            <button class='buttonsBlock' onclick="www('url');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/url.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="www('mail');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/mail.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="www('img');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/img.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('ul');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/ul.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('ol');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/ol.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('center');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/center.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('sup');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/sup.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('sub');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/sub.gif'>
                                            </button>
                                            <button class='buttonsBlock' onclick="editText('code');" type='button'>
                                                <img src='<?php echo $iconssmiles ?>/code.gif'>
                                            </button>
                                        </div>
                                    </td>
                                    <td rowspan=2 id='erm_Smiles'>
                                        <div id='erm_allSmiles'>
                                            <?php foreach($section->smilelist as $smile): ?>
                                                <div id='erm_SmilesBlock'>
                                                    <a href="javascript:AddSmile('[smile<?php echo $smile->smile ?>]');">
                                                        <img border="0" src="<?php echo $iconssmiles ?>/smile<?php echo $smile->smile ?>.gif" alt='[smile<?php echo $smile->smile ?>]' class='smileXXX'>
                                                    </a>
                                                </div>
                                            
<?php endforeach; ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id='erm_TextArea'>
                                        <textarea class='inputForum' name='text' maxlength=<?php echo $msgMaxLength ?> id='erm_AreaForText'><?php echo $text ?></textarea>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2>
                                        <div id='ERM_maxlen'>
                                            <?php echo $section->language->lang159 ?>:&nbsp;<?php echo $msgMaxLength ?>&nbsp;<?php echo $section->language->lang160 ?>.&nbsp;<?php echo $section->language->lang161 ?>: 
                                            <input type='text' id='txtCount' value='<?php echo $msgMaxLength ?>' readonly>
                                        </div>
                                    </td>
                                </tr>
                                <?php if($ext_topic!=0): ?>
                                    <input type='hidden' name='topic' value='<?php echo $ext_topic ?>'>
                                <?php endif; ?>
                                <?php if($mmid!=0): ?>
                                    <input type='hidden' name='mmid' value=<?php echo $mmid ?>>
                                <?php endif; ?>
                                <?php if($newt!=0): ?>
                                    <input type='hidden' name='newt' value=1>
                                <?php endif; ?>
                                <?php if($forumId!=0): ?>
                                    <input type='hidden' name='forum' value=<?php echo $forumId ?>>
                                <?php endif; ?>
                                <?php if($mod!=0): ?>
                                    <input type='hidden' name='mod' value=1>
                                <?php endif; ?>
                                <?php if($quote_id!=0): ?>
                                    <input type='hidden' name='qoute' value=1>
                                <?php endif; ?>
                                <input type='hidden' name='doGo' id='doGo' value=0>
                                <tr>
                                    <td colspan=2>
                                        <div id='erm_mkattach'>
                                            <span id="infoAttacheFiles"><?php echo $section->language->lang162 ?></span>
                                            <input id='erm_flattach' type='file' name='userfile'>
                                            <input class='buttonSend' id='erm_btnAttach' name='upload' type='submit' value='<?php echo $section->language->lang042 ?>'>
                                            <div id='erm_attach'>
                                                <?php if($forum_attached_count!=0): ?>
                                                    <?php echo $section->language->lang163 ?>: (
                                                    <?php foreach($section->fatt as $ffatt): ?>
                                                        <a class="fileLink" href="javascript:addFile('<?php echo $ffatt->name2 ?>', <?php echo $ffatt->img ?>);">
                                                            <?php echo $ffatt->name ?>
                                                        </a>&nbsp;
                                                        <a class="delFile" href="javascript:delThisFile('<?php echo $ffatt->name ?>', '<?php echo $ffatt->id ?>');">
                                                            <?php echo $section->language->lang129 ?>
                                                        </a>
                                                        &nbsp;(<?php echo $ffatt->size ?>&nbsp;<?php echo $section->language->lang122 ?>)<?php echo $ffatt->next ?>
                                                    
<?php endforeach; ?>
                                                    ).&nbsp;    
                                                    <?php echo $section->language->lang164 ?>: <?php echo $forum_attached_count ?>, <?php echo $section->language->lang165 ?>: <?php echo $forum_attached_size ?> <?php echo $section->language->lang122 ?>
                                                    &nbsp;<?php echo $section->language->lang166 ?>: <?php echo $maxFilesAttached ?>, <?php echo $section->language->lang165 ?>:&nbsp;
                                                    <?php echo $forum_attached_max ?>&nbsp;<?php echo $section->language->lang122 ?>
                                                <?php else: ?>
                                                    <?php echo $section->language->lang167 ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php if($newt!=0): ?>
                                    <tr>
                                        <td colspan=2>
                                            <div id='erm_NewMess'>
                                                <?php echo $section->language->lang024 ?>&nbsp;
                                                <input class='inputForum' id='erm_inpNewMess' type='text' maxlength='50' name='email'>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan=2>
                                        <div id='erm_ServicesButtons'>
                                            <?php if($save_button!=0): ?>
                                                <input class='buttonSend' id='erm_Add' type='button' value='<?php echo $section->language->lang043 ?>'>
                                            <?php else: ?>
                                                <input class='buttonSend' id='erm_Add' type='button' value='<?php echo $section->language->lang168 ?>'> 
                                            <?php endif; ?>
                                            <input class='buttonSend' id='erm_Clear' type='button' value='<?php echo $section->language->lang080 ?>' onclick="javascript:history.go(-1)">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
            <!--Конец тела страницы-->
            <div id=footinfo><?php echo $section->language->lang143 ?>&nbsp;
                <span id="allusers"><b><?php echo $section->language->lang144 ?>:</b>&nbsp;<?php echo $all_users ?></span>
                <span id="regusers"><b><?php echo $section->language->lang145 ?>:</b>&nbsp;<?php echo $reg_users ?></span>
                <?php if($reg_users!=0): ?>
                    (<?php foreach($section->regusers as $reguser): ?>
                        <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $reguser->id ?>' class='reguser'><?php echo $reguser->name ?></a><?php echo $reguser->notend ?>
                    
<?php endforeach; ?>)
                <?php endif; ?>
                <span id="guestusers"><b><?php echo $section->language->lang146 ?>:</b>&nbsp;<?php echo $guest ?></span>
                <?php if($allrobots!=0): ?>
                    <b id='main_Robots'><?php echo $section->language->lang147 ?>:&nbsp;<?php echo $allrobots ?>&nbsp;
                        (<?php foreach($section->robots as $robot): ?>
                            <?php echo $robot->name ?><?php echo $robot->notend ?>
                        
<?php endforeach; ?>)
                    </b>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_20.php")) include $__MDL_ROOT."/php/subpage_20.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_20.tpl")) include $__MDL_ROOT."/tpl/subpage_20.tpl"; ?>
</div>
<!--04-->
