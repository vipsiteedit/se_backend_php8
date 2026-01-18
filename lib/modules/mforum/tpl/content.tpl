<div class="content forum" <?php echo $section->style ?>>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_19.php")) include $__MDL_ROOT."/php/subpage_19.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_19.tpl")) include $__MDL_ROOT."/tpl/subpage_19.tpl"; ?>
    <?php if($enable==1): ?>
        <div id="message_warning"><?php echo $section->language->lang131 ?></div>
    <?php else: ?>
        <?php if($enable==2): ?>
            <div id="message_warning"><?php echo $section->language->lang132 ?>&nbsp;<?php echo $haltView ?>&nbsp;<?php echo $section->language->lang133 ?></div>
        <?php else: ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_21.php")) include $__MDL_ROOT."/php/subpage_21.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_21.tpl")) include $__MDL_ROOT."/tpl/subpage_21.tpl"; ?>
            <!--Начало тела страницы-->
            <h3 class='forumTitle' id='forumtitle'><?php echo $nameForum ?></h3>
            <table class='tableForum' id='table_main' border=0>
                <tbody class='tableBody'>
                    <tr>
                        <td <?php if($section->parametrs->param98!=2): ?>colspan=2<?php endif; ?> class='title' id='title_MainForum'>
                            <div id='main_MainForum'><?php echo $section->language->lang068 ?></div>
                        </td>
                        <td class='title' id='title_MainTopic'>
                            <div id='main_MainTopic'><?php echo $section->language->lang140 ?></div>
                        </td>
                        <td class='title' id='title_MainUpdate'>
                            <div id='main_MainUpdate'><?php echo $section->language->lang072 ?></div>
                        </td>
                    </tr>
                    <?php foreach($section->forums as $forum): ?>
                        <?php if($forum->aid!=0): ?>
                            <tr>
                                <td colspan='4' id='field_Topic' class='field'>
                                    <div id='forumrazdel'><?php echo $forum->area ?></div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <?php if($section->parametrs->param98!=2): ?>
                                <td class='field' id='statustd'>
                                    <div id="main_<?php echo $forum->StatusID ?>">&nbsp;</div>
                                </td>
                            <?php endif; ?>
                            <td class='field' id='field_MainForum'>
                                <div id='main_ForumName'>
                                    <a id='main_linkForum' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $forum->id ?>"><?php echo $forum->name ?></a>
                                    <div id='main_ShDescr'><?php echo $forum->description ?></div>
                                </div>
                            </td>
                            <td class='field' id='field_MainTopic'>
                                <div id='main_MessMount'><?php echo $forum->count ?></div>
                            </td>
                            <td class='field' id='field_MainUpdate'>
                                <?php if($forum->count!=0): ?>
                                    <div id='main_Update'>
                                        <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $forum->topic_id ?>&new&last' id='main_LinkTopic'>
                                            <?php echo $forum->topic_name ?>
                                        </a>
                                        <div id='main_date'>
                                            <?php echo $forum->topicDateNew ?>
                                            <div id='main_autUpdate'>
                                                <?php echo $section->language->lang141 ?>: 
                                                <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $forum->topic_id_user_new ?>' id='main_cellAuthorNickCr'>
                                                    <?php echo $forum->topic_nick ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php echo $section->language->lang142 ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    
<?php endforeach; ?>
                </tbody>
            </table>
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
