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
            <?php if($alllist!=0): ?>
                <div id='users_Count'>
                    <?php echo $section->language->lang045 ?>&nbsp;
                    <span id='countUSR'><?php echo $regusers ?></span>
                </div>
                <?php if($ipages!=0): ?>
                    <div id='steplist'>|
                        <?php foreach($section->ipages as $ipage): ?>
                            <?php if($ipage->status==1): ?>
                                <b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
                            <?php else: ?>
                                <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
                            <?php endif; ?>
                        
<?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <h3 class='forumTitle' id='users_Title'><?php echo $section->language->lang046 ?></h3>
                <table class='tableForum' id='table_showUsers'>
                    <tbody class='tableBody'>
                        <tr>
                            <td class='title' id='users_titleNick'>
                                <div id='users_Nick'><?php echo $section->language->lang047 ?></div>
                            </td>
                            <td class='title' id='users_titleRegDate'>
                                <div id='users_RegDate'><?php echo $section->language->lang048 ?></div>
                            </td>
                        </tr>
                        <?php foreach($section->users as $user): ?>
                            <tr>
                                <td class='field' id='users_fieldUserName'>
                                    <div id='users_UserName'>
                                        <a id='users_linkUserName' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub17/" ?>?id=<?php echo $user->id ?>'><?php echo $user->nick ?></a>
                                    </div>
                                </td>
                                <td class='field' id='users_fieldNumber'>
                                    <div id='users_Number'><?php echo $user->date ?></div>
                                </td>
                            </tr>
                        
<?php endforeach; ?>
                    </tbody>
                </table>                
                <div id='butlayer'>
                    <input class='buttonSend' id='AllUsersbtBack' type='button' onclick="javascript:history.go(-1)" value='<?php echo $section->language->lang126 ?>'>
                </div>
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
<!--07-->
