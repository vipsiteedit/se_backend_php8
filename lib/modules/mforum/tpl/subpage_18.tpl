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
            <?php if($find==0): ?>
                <div id=message_warning>
                    <?php echo $section->language->lang066 ?>
                </div>
                <div id='butlayer'>
                    <input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' value="<?php echo $section->language->lang126 ?>">
                </div>
            <?php else: ?>
                <?php if($ipages!=0): ?>
                    <div id='steplist'>|
                        <?php foreach($section->ipages as $ipage): ?>
                            <?php if($ipage->status==1): ?>
                                <b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
                            <?php else: ?>
                                <a href="<?php echo $query ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
                            <?php endif; ?>
                        
<?php endforeach; ?>                                 
                    </div>
                <?php endif; ?>
                <table class='tableForum' id=tableUserMessage>
                    <tbody>
                        <?php foreach($section->somethings as $something): ?>
                            <tr>
                                <td colspan=2 id='mess_MessTheme' class='title'>
                                    <div id='mess_ThemeName'><?php echo $something->topic ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td class='title' id='title_ShowUserMess'>
                                    <div id='mess_ShowUserMess'>
                                        <a name='t<?php echo $something->i ?>'></a>
                                        <a id='mess_showTopicAuthorNick' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $something->id_users ?>'><?php echo $something->user ?></a>
                                        <?php if($something->img!=0): ?>
                                            <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $something->id_users ?>'>
                                                <img border=0 id='mess_showTopicAuthorImg' src="/modules/forum/images/<?php echo $something->img ?>">
                                            </a>
                                        <?php endif; ?>
                                        <div id='mess_showTopicAuthorStatus'><?php echo $something->status ?></div>
                                        <div id='mess_showTopicAuthorLocation'><?php echo $something->location ?></div>
                                    </div>
                                </td>
                                <td class='field' id='field_ShowUserMess'>
                                    <div id='mess_MessageText'>
                                        <div id='mess_showTopicMsgDate'><?php echo $something->date ?></div>
                                        <div id='mess_GoToTheme'><?php echo $something->goTo ?></div>
                                        <div id='mess_showTopicMsgText'>
                                            <span id='searchmsg'><?php echo $something->text ?></span>
                                            <?php if($something->time_edit!=0): ?>
                                                <br>
                                                <div id='edit'>
                                                    <?php echo $section->language->lang097 ?>&nbsp;<?php echo $something->date_time_edit ?>
                                                </div> 
                                            <?php endif; ?>
                                            <?php if($something->moderator_edit!=0): ?>
                                                <br>
                                                <div id='moder'>
                                                    <?php echo $section->language->lang098 ?>&nbsp;<?php echo $something->date_time_moderator_edit ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
<?php endforeach; ?>
                    </tbody>
                </table>
                <?php if($ipages!=0): ?>
                    <div id='steplist'>|
                        <?php foreach($section->ipages as $ipage): ?>
                            <?php if($ipage->status==1): ?>
                                <b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
                            <?php else: ?>
                                <a href="<?php echo $query ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
                            <?php endif; ?>
                        
<?php endforeach; ?>
                    </div>
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
<!--18-->
