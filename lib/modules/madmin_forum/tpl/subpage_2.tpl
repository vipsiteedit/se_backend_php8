<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <?php if($save_name!=0): ?>
        <h3 class='forumTitle' id='adm_fparTitle2'><?php echo $section->language->lang011 ?></h3>
        <table class='tableForum' id='table_forumParam2'>
            <tbody id='tableBody'>
                <form method="post">
                    <tr>
                        <td class='title' id='title_admName'>
                            <div id='adm_tName'><?php echo $section->language->lang012 ?></div>
                        </td>
                        <td class='field' id='field_admName'>
                            <input type='hidden' value=<?php echo $area_id ?> name='aid'>
                            <input class='inputForum' id='adm_inpName' type='text' name='area_name' value="<?php echo $res ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id='adm_srvcBtn'>
                                <input class='buttonSend' id='adm_btnSave' type='submit' name='save_name' value="<?php echo $section->language->lang013 ?>">
                                <input class='buttonSend' id='adm_bBkTMn' type='button' value="<?php echo $section->language->lang014 ?>" onclick="javascript:history.back(-1)">
                            </div>
                        </td>                           
                    </tr>
                </form>
            </tbody>
        </table>
    <?php else: ?>
        <div id='message_warning'><?php echo $section->language->lang015 ?></div>
        <div id='butlayer'>
            <input class='buttonSend' id='btnBack' type='button' value="<?php echo $section->language->lang014 ?>" onclick="javascript:history.back(-1)">
        </div>
    <?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
