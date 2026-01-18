<div class="content forum">                                  
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <?php if($admindel!=0): ?>
        <div id='message_warning' class="error_user"><?php echo $section->language->lang035 ?></div>
        <div id='butlayer'>
            <input class='buttonSend' id='mess_btnBack' type='button' value='<?php echo $section->language->lang014 ?>' onclick='javascript:history.go(-1)'>
        </div>
    <?php else: ?>
        <?php if($firstp!=0): ?>
            <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub8/" ?>?id=<?php echo $forum_id ?>" method='POST'>
                <div id='adm_Mdr'>
                    <h3 class='forumTitle' id='adm_MdrMk'><?php echo $section->language->lang037 ?>&nbsp;<?php echo $forumName ?></h3>
                    <div id='curmoder'>
                        <?php if($fnexists!=0): ?>
                            <?php echo $section->language->lang038 ?>&nbsp;<?php echo $forumNick ?>
                        <?php else: ?>
                            <?php echo $section->language->lang039 ?> &quot;<?php echo $forumName ?>&quot;<?php echo $section->language->lang040 ?>
                        <?php endif; ?>
                    </div>
                    <select class='adm_slcState' id='adm_inpMdrNm' name='moder_name'>
                        <?php foreach($section->users as $user): ?>
                            <option value=<?php echo $user->id ?>><?php echo $user->nick ?></option>
                        
<?php endforeach; ?>
                    </select>
                    <input class='buttonSend' id='adm_bMd' type='submit' name='save_moder' value="<?php echo $section->language->lang041 ?>">
                    <input class='buttonSend' id='adm_bBack' type='submit' value="<?php echo $section->language->lang014 ?>" name='Back'>
                </div>
            </form>
        <?php endif; ?>
        <?php if($secndp!=0): ?>
            <form method='POST'>
                <div id='adm_suMdr'>
                    <h3 class='forumTitle' id='adm_MdrMk'><?php echo $section->language->lang042 ?></h3>
                    <?php if($smoderators!=0): ?>
                        <div id='adm_delSu'>
                            <select class='adm_slcState' id='adm_slDlsu' name='supusr'>
                                <?php foreach($section->smoderators as $smod): ?>
                                    <option value="<?php echo $smod->id ?>"><?php echo $smod->nick ?></option>
                                
<?php endforeach; ?>
                            </select>
                            <input class='buttonSend' id='adm_bDelsu' type='submit' name='delsu' value="<?php echo $section->language->lang004 ?>">
                        </div>
                    <?php else: ?>
                        <div id='adm_erMess' class="error_user"><?php echo $section->language->lang043 ?></div>
                    <?php endif; ?>
                    <div id='adm_addSu'>
                        <select class='adm_slcState' id='adm_inAdsu' name='suname'>
                            <?php foreach($section->users as $user): ?>
                                <option value=<?php echo $user->id ?>><?php echo $user->nick ?></option>
                            
<?php endforeach; ?>
                        </select>
                        <input class='buttonSend' id='adm_bAddsu' type='submit' value="<?php echo $section->language->lang013 ?>" name='savesu'>
                    </div>
                    <input class='buttonSend' id='adm_bdaBck' type='submit' name='Back' value="<?php echo $section->language->lang014 ?>">
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
