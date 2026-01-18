<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <?php if($save_name!=0): ?>
        <h3 class='forumTitle' id='adm_fparTitle'><?php echo $section->language->lang016 ?></h3>
        <table class='tableForum' id='table_forumParam'>
            <tbody class='tableBody'>
                <form method='post'>
                    <tr>
                        <td class='title' id='title_admName'>
                            <div id='adm_tName'><?php echo $section->language->lang017 ?></div>
                        </td>
                        <td class='field' id='field_admName'>
                            <input type='hidden' value="<?php echo $forum_id ?>" name='fid'>
                            <input class='inputForum' id='adm_inpName' type='text' name='forum_name' value='<?php echo $forname ?>'>
                        </td>
                    </tr>
                    <tr>
                        <td class='title' id='title_admShDesc'>
                            <div id='adm_tShDesc'><?php echo $section->language->lang018 ?></div>
                        </td>
                        <td class='field' id='field_admShDesc'>
                            <textarea class='areaForum' id='adm_arShDesc' name='descript'><?php echo $descr ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class='title' id='title_admState'>
                            <div id='adm_tState'><?php echo $section->language->lang019 ?></div>
                        </td>
                        <td class='field' id='field_admState'>
                            <select class='adm_slcState' id='adm_slcState' name="status">
                                <?php if($visible=='Y'): ?>
                                    <option selected value="Y"><?php echo $section->language->lang020 ?></option>
                                    <option value="N"><?php echo $section->language->lang021 ?></option>
                                <?php else: ?>
                                    <option value="Y"><?php echo $section->language->lang020 ?></option>
                                    <option selected value="N"><?php echo $section->language->lang021 ?></option>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='title' id='title_admSect'>
                            <div id='adm_tSect'><?php echo $section->language->lang022 ?></div>
                        </td>
                        <td class='field' id='field_forumSect'>
                            <select class='adm_slcState' id="areaSect" name='area_id'>
                                <?php foreach($section->arealist as $area): ?>
                                    <option <?php echo $area->sel ?> value="<?php echo $area->id ?>"><?php echo $area->name ?></option>
                                
<?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id='adm_srvcBtn'>
                                <input class='buttonSend' id='adm_btnSave' type='submit' name='save_name' value="<?php echo $section->language->lang013 ?>">
                                <input class='buttonSend' id='adm_bBkTMn' type='button' value="<?php echo $section->language->lang014 ?>" onclick='javascript:history.back(-1)'>
                            </div>
                        </td>
                    </tr>
                </form>
            </tbody>
        </table>
    <?php else: ?>
        <div id='message_warning'><?php echo $section->language->lang023 ?></div>
            <div id='butlayer'>
                <input class='buttonSend' id='mess_btnBack' type='button' value="<?php echo $section->language->lang014 ?>" onclick='javascript:history.back(-1)'>
            </div>
        </div>
    <?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
