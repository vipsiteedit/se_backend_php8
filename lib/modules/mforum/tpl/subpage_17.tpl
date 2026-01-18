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
            <?php if($ext_id_numeric==0): ?>
                <!-- -->
            <?php endif; ?>
            <h3 class='forumTitle' id='user_Title'><?php echo $section->language->lang095 ?></h3>
            <table class='tableForum' id='tablePvt'>
                <tbody class='tableBody'>
                    <form action='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>' method='post'>
                        <tr>
                            <td class='title' id='pvt_titleNick'>
                                <div id='pvt_Nick'><?php echo $section->language->lang127 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldNick'>
                                <div id='pvt_UserNick'><?php echo $_nick ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleRealName'>
                                <div id='pvt_RealName'><?php echo $section->language->lang028 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldRealName'>
                                <div id='pvt_UserName'><?php echo $realname ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleLocation'>
                                <div id='pvt_Location'><?php echo $section->language->lang029 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldLocation'>
                                <div id='pvt_UserLocation'><?php echo $location ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                                <td class='title' id='pvt_titleStatus'>
                                    <div id='pvt_Status'><?php echo $section->language->lang030 ?></div>
                                </td>
                                <td class='field' id='pvt_fieldStatus'>
                                    <div id='pvt_UserStatus'><?php echo $status ?>&nbsp;</div>
                                </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleMessages'>
                                <div id='pvt_Messages'><?php echo $section->language->lang031 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldMessages'>
                                <div id='pvt_UserMess'>
                                    <a id='user_linkUserMess' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>?user=<?php echo $ext_id ?>&forums[]=all&text=&result_type=messages&time=0'>
                                        <?php echo $allmsg ?>
                                    </a>                                            
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleRegDate'>
                                <div id='pvt_RegDate'><?php echo $section->language->lang034 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldRegDate'>
                                <div id='pvt_UserRgDt'><?php echo $registered ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleLastVisit'>
                                <div id='pvt_LastVisit'><?php echo $section->language->lang035 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldLastVisit'>
                                <div id='pvt_UserLsVst'><?php echo $last ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleEmail'>
                                <div id='pvt_Email'>e-mail:</div>
                            </td>
                            <td class='field' id='pvt_fieldEmail'>
                                <div id='pvt_UserMail'>
                                    <a href='mailto:<?php echo $email ?>'><?php echo $email ?></a>&nbsp;
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleICQ'>
                                <div id='pvt_ICQ'>ICQ UIN:</div>
                            </td>
                            <td class='field' id='pvt_fieldICQ'>
                                <div id='pvt_UserICQ'><?php echo $icq ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleURL'>
                                <div id='pvt_URL'>URL:</div>
                            </td>
                            <td class='field' id='pvt_fieldURL'>
                                <div id='pvt_UserURL'>
                                    <a href="http://<?php echo $url ?>"><?php echo $url ?></a>&nbsp;
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleJobTitle'>
                                <div id='pvt_JobTitle'><?php echo $section->language->lang036 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldJobTitle'>
                                <div id='pvt_UserJobTitle'><?php echo $jobtitle ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleInterests'>
                                <div id='pvt_Interests'><?php echo $section->language->lang037 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldInterests'>
                                <div id='pvt_UserInterests'><?php echo $interests ?>&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleAvatar'>
                                <div id='pvt_Avatar'><?php echo $section->language->lang038 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldAvatar'>
                                <div id='pvt_UserAvtr'> 
                                    <?php if($img_exists!=0): ?>
                                        <img id='user_AvtImg' src="/modules/forum/images/<?php echo $img ?>">&nbsp;
                                    <?php else: ?>
                                        <?php echo $section->language->lang128 ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='title' id='pvt_titleDesript'>
                                <div id='pvt_Desript'><?php echo $section->language->lang040 ?></div>
                            </td>
                            <td class='field' id='pvt_fieldDesript'>
                                <div id='pvt_UserDscr'><?php echo $origin ?>&nbsp;</div>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
            <div id='butlayer'>
                <?php if($uid!=0): ?>
                    <input type='button' class='buttonSend' id='createPersonal' onclick="document.location='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>personal/<?php echo $ext_id ?>/';" value='<?php echo $section->language->lang096 ?>'>
                <?php endif; ?>
                <input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' value='<?php echo $section->language->lang126 ?>'>
            </div>
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
<!--17-->
