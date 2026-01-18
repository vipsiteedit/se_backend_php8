<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub9/" ?>" method=POST>
        <div id='adm_StsMk'>
            <h3 class='forumTitle' id='adm_StsMng'><?php echo $section->language->lang001 ?></h3>
            <input class='buttonSend' id='adm_bAdd' type='submit' value="<?php echo $section->language->lang002 ?>" name='add'>
            <input class='inputForum' id='adm_inpNmSt' type='input' name='status_name'>
            <input class='buttonSend' id='adm_bEd' type='submit' name='edit' value="<?php echo $section->language->lang003 ?>">
            <select class='adm_slcState' id=adm_slcStNm1 name=status_list>
                <?php foreach($section->statuslist as $status): ?>
                    <option value='<?php echo $status->id ?>'><?php echo $status->name ?></option>
                
<?php endforeach; ?>
            </select>
            <input class='buttonSend' id='adm_bDel' type='submit' name='del' value="<?php echo $section->language->lang004 ?>">
        </div>
    </form>
    <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub9/" ?>" method=POST>
        <div id='adm_StsSg'>
            <h3 class='forumTitle' id='adm_StsBc'><?php echo $section->language->lang005 ?></h3>
            <select class='adm_slcState' id=adm_slcStNm2 name=status_list>
                <?php foreach($section->statuslist as $status): ?>
                    <option value='<?php echo $status->id ?>'><?php echo $status->name ?></option>
                
<?php endforeach; ?>
            </select>
            <!--input class='inputForum' id='adm_inpNNmSt' type='text' name='user_name'-->
            <select class='adm_slcState' id='adm_inpNNmUs' name='user_name'>
                <option>--<?php echo $section->language->lang006 ?>--</option>
                <?php foreach($section->users as $user): ?>
                    <option value='<?php echo $user->id ?>'><?php echo $user->nick ?></option>
                
<?php endforeach; ?>
            </select>
            <input class='buttonSend' id='adm_bSet' type='submit' name='save_status' value="<?php echo $section->language->lang007 ?>">
            <input class='buttonSend' id='adm_bUnset' type='submit' name='del_status' value="<?php echo $section->language->lang004 ?>">
        </div>
    </form>
    <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>" method=POST>
        <div id='adm_BlLst'>
            <h3 class='forumTitle' id='adm_BlcLst'><?php echo $section->language->lang008 ?></h3>
            <!--input class='inputForum' id='adm_inpBnNm' type='text' name='banan_to'-->
            <select class='adm_slcState' id='adm_inpBnNm' name='banan_to'>
                <option>--<?php echo $section->language->lang006 ?>--</option>
                <?php foreach($section->users as $user): ?>
                    <option value='<?php echo $user->id ?>'><?php echo $user->nick ?></option>
                
<?php endforeach; ?>
            </select>
            <input class='buttonSend' id='adm_bAddBn' type='submit' name='add_banan' value="<?php echo $section->language->lang009 ?>">
            <select class='adm_slcState' id='adm_slcBnNmLs' name='ban_list'>
                <option>--<?php echo $section->language->lang006 ?>--</option>
                <?php foreach($section->banlist as $ban): ?>
                    <option value='<?php echo $ban->id ?>'><?php echo $ban->nick ?></option>
                
<?php endforeach; ?>
            </select>
            <input class='buttonSend' id='adm_bDelBn' type='submit' name='del_banan' value="<?php echo $section->language->lang010 ?>">
        </div>
    </form>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
