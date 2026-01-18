<div class="content forum">
    <?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
    <h3 class='forumTitle' id='adm_moveTitle'<?php echo $section->language->lang049 ?></h3>
    <table class='tableForum' id='adm_moveTable'>
        <form method='post'>
            <tr>
                <td colspan=2>
                    <select class='adm_slcState' id='adm_slcNames' name='forum_list'>
                        <?php foreach($section->forums as $forum): ?>
                            <option <?php echo $forum->sel ?> value='<?php echo $forum->id ?>'><?php echo $forum->name ?></option>
                        
<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='title' id='adm_titleMvCln'>&nbsp;</td>
                <td class='title' id='adm_fieldMvTh'>
                    <div id='adm_fMvTh'><?php echo $section->language->lang050 ?></div>
                </td>
            </tr>
            <?php foreach($section->topics as $topic): ?>
                <tr>
                    <td class='field' id='adm_titleCh'>
                        <input type='checkbox' name='checks[]' class='adm_cbMov' value='<?php echo $topic->id ?>'>
                    </td>
                    <td class='field' id='adm_fieldMvThN'>
                        <div id='adm_fMvThN'><?php echo $topic->name ?></div>
                    </td>
                </tr>
            
<?php endforeach; ?>
            <tr>
                <td colspan=2>
                    <div id='adm_mvSrvBtn'>
                        <input class='buttonSend' id='adm_bMvOut' type='submit' name='Move' value='<?php echo $section->language->lang051 ?>'>
                        <input class='buttonSend' id='adm_bMvBack' type='button' value='<?php echo $section->language->lang014 ?>' onclick='javascript:history.back()'>
                    </div>
                </td>
            </tr>
        </form>
    </table>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
