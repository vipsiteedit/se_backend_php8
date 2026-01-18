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
            <?php if($user_p==0): ?>
                <?php if($puser_p==0): ?>
                    <div id="forumPath">
                        <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>?id=<?php echo $aid ?>' id='forums'><?php echo $Area ?></a>
                        <span class="divider"><?php echo $section->parametrs->param14 ?></span>
                        <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $fid ?>' id='themes'><?php echo $Forum ?></a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($theme_exists==0): ?>
                <div id='message_warning'><?php echo $section->language->lang154 ?></div>
                <div id='butlayer'>
                    <input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' value='<?php echo $section->language->lang126 ?>'>
                </div>
            <?php else: ?>
                <?php if($ipages!=0): ?>
                    <div id='steplist'>|
                        <?php foreach($section->ipages as $ipage): ?>
                            <?php if($ipage->status==1): ?>
                                <b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
                            <?php else: ?>
                                <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
                            <?php endif; ?>
                        
<?php endforeach; ?>
                    </div>                  
                <?php endif; ?>
                <h3 class='forumTitle' id='titleTopic'>
                    <?php if($user_p!=0): ?>
                        <?php echo $section->language->lang015 ?>
                    <?php else: ?>
                        <?php if($puser_p!=0): ?>
                            <?php echo $section->language->lang016 ?>
                        <?php else: ?>
                            <?php echo $titleTopic ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </h3>
                <?php if($all_msg!=0): ?>
                    <table id='tableTopic' class='tableForum' border=1>
                        <tbody class='tableBody'>
                            <?php foreach($section->messages as $message): ?>
                                <tr>
                                    <td class='title' id='title_ShowTopicAuthor'>
                                        <div id='topic_ShowTopicAuthor'>
                                            <a name='t<?php echo $message->cur ?>'></a>
                                            <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $message->id_users ?>' id='topic_showTopicAuthorNick'><?php echo $message->nick ?></a>
                                            <?php if($message->img_exists!=0): ?>
                                                <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $message->id_users ?>' title='<?php echo $section->language->lang007 ?>'>
                                                    <img id='topic_showTopicAuthorImg' src="/modules/forum/images/<?php echo $message->img ?>" border=0>
                                                </a>
                                            <?php endif; ?>
                                            <div id='topic_showTopicAuthorStatus'><?php echo $message->status ?></div>
                                            <div id='topic_showTopicLoc'><?php echo $message->location ?></div>
                                            <a id='topic_lnkShowTopicMsg' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>?user=<?php echo $message->id_users ?>&forums[]=all&text=&result_type=messages&time=0'>
                                                <?php echo $section->language->lang069 ?>:&nbsp;<?php echo $message->count_msg ?>
                                            </a>
                                            <?php if($message->id_whom!=0): ?>
                                                <div class='whom'>
                                                    <span class='whom_txt'><?php echo $section->language->lang017 ?></span>&nbsp;
                                                    <a class='author_whom' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $message->id_whom ?>'><?php echo $message->nick_whom ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>                                            
                                    </td>
                                    <td class='field' id='field_ShowTopicMsg'>
                                        <div id='topic_MessageText'>
                                            <div id='topic_showTopicMsgDate'>
                                                <?php echo $message->date ?>
                                            </div>
                                            <div id='topic_showTopicMsgMenu'> 
                                                <?php if($new_msg==0): ?>
                                                    <a class='shTmenitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $ext_id ?>' id='showTopicMsgMenuNew'><?php echo $section->language->lang186 ?></a>
                                                    <a class='shTmenitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?quote=<?php echo $message->id ?>&id=<?php echo $ext_id ?>#edit' id='showTopicMsgMenuReply'><?php echo $section->language->lang187 ?></a>
                                                <?php endif; ?> 
                                                <?php if($message->edit!=0): ?>
                                                    &nbsp;
                                                    <a class='shTmenitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?><?php echo $message->edit_p ?>?mid=<?php echo $message->id ?>' id='showTopicMsgMenuEdit'><?php echo $section->language->lang188 ?></a>
                                                <?php endif; ?>
                                                <?php if($message->moderator!=0): ?>
                                                    &nbsp;
                                                    <a class='shTmenitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $ext_id ?>&mod=<?php echo $message->id ?>' id='showTopicMsgMenuModer'><?php echo $section->language->lang150 ?></a>
                                                <?php endif; ?>
                                                <?php if($message->todel!=0): ?>
                                                    &nbsp;
                                                    <a class='shTmenitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub8/" ?>?id=<?php echo $message->id ?>' id='showTopicMsgMenuDel'><?php echo $section->language->lang039 ?></a>
                                                <?php endif; ?>
                                            </div>
                                            <div id='topic_showTopicMsgText'>
                                                <?php echo $message->text ?>
                                            </div>
                                            <?php if($message->msg_id!=0): ?>
                                                <div id="question">
                                                    <span class='AnswerString'><?php echo $section->language->lang018 ?></span>
                                                    <div id='qstninfo'>
                                                        <a id='puser' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $message->pid_users ?>'><?php echo $message->pnick ?></a>,
                                                        <span id='msgdate'><?php echo $message->pdate ?></span> 
                                                    </div>
                                                    <span id='qstntext'><?php echo $message->ptext ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($message->origin!=''): ?>
                                                <div class='origin'>
                                                    <?php echo $message->origin ?>
                                                </div>
                                            <?php endif; ?>
                                            <div id='editblock'>
                                                <?php if($message->date_time_edit_show!=0): ?>
                                                    <br>
                                                    <div id='edit'>
                                                        <?php echo $section->language->lang097 ?>&nbsp;<?php echo $message->date_time_edit_date ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($message->moderator_edit=='Y'): ?>
                                                    <br>
                                                    <div id='moder'>
                                                        <?php echo $section->language->lang098 ?>&nbsp;<?php echo $message->moderator_edit_date ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id='user_menu'>
                                                <?php if($message->email_exists!=0): ?>
                                                    <a id='topic_Email' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub15/" ?>?id=<?php echo $message->id_users ?>&topic=<?php echo $ext_id ?>"><?php echo $section->language->lang155 ?></a>
                                                    &nbsp;
                                                <?php endif; ?>
                                                <?php if($message->icq_exists!=0): ?>
                                                    <a id='topic_ICQ' href='http://wwp.icq.com/<?php echo $message->icq ?>#pager' target=_blank><?php echo $section->language->lang156 ?> 
                                                        <img border=0 src='<?php echo $iconssmiles ?>/icq_icon.gif' width=13 height=13>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            
<?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class=sysedit>
                        <div id='message_warning'><?php echo $section->language->lang019 ?></div>
                        <div id='butlayer'>
                            <input class='buttonSend' id='btBack' type='button' value='<?php echo $section->language->lang126 ?>' onclick='javascript:history.go(-1)'>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if($ipages!=0): ?>
                    <div id='steplist'>|
                        <?php foreach($section->ipages as $ipage): ?>
                            <?php if($ipage->status==1): ?>
                                <b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
                            <?php else: ?>
                                <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
                            <?php endif; ?>
                        
<?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
<!--Конец тела страницы-->
            <div id=footinfo><?php echo $section->language->lang143 ?>&nbsp;
                <span id="allusers">
                    <b><?php echo $section->language->lang144 ?>:</b>&nbsp;<?php echo $all_users ?>
                </span>
                <span id="regusers">
                    <b><?php echo $section->language->lang145 ?>:</b>&nbsp;<?php echo $reg_users ?>
                </span>
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
<!--03-->
