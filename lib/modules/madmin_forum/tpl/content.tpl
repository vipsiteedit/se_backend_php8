<div class="content forum"<?php echo $section->style ?>>
<?php if(file_exists($__MDL_ROOT."/php/subpage_13.php")) include $__MDL_ROOT."/php/subpage_13.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_13.tpl")) include $__data->include_tpl($section, "subpage_13"); ?>
<h3 class='forumTitle' id='forumList'><?php echo $section->language->lang061 ?></h3>
<table class='tableForum' id='table_main' border=0>
    <tbody class='tableBody'>
        <tr vAlign='top'>
            <td class="title" id='title_MainForum'>
                <div id='main_MainForum'><?php echo $section->language->lang062 ?></div>
            </td>
            <td class="title" id='title_MainTopic'>
                <div id='main_MainTopic'><?php echo $section->language->lang063 ?></div>
            </td>
            <td class="title" id='title_MainUpdate'>
                <div id='main_MainUpdate'><?php echo $section->language->lang064 ?></div>
            </td>
        </tr>
        <?php foreach($section->alldata as $data): ?>
            <tr>
                <td id='field_Topic' class='field colspan field_Topic'>
                    <div class='forumrazdel'><?php echo $data->name ?></div>
                </td>
                <td class='field' id='fld_Menu'>
                    <div id='adm_commands'>
                        <a class='foru_Edit' id='adm_areaEdit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $data->area_id ?>'><?php echo $section->language->lang003 ?></a>
                        <a class='foru_Edit' id='adm_areaDel' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub6/" ?>?id=<?php echo $data->area_id ?>'><?php echo $section->language->lang004 ?></a>
                        <a class='foru_Edit' id='adm_areaUp' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>?id=<?php echo $data->area_id ?>&order=<?php echo $data->ar_order_id ?>&do=1'><?php echo $section->language->lang065 ?></a>
                        <a class='foru_Edit' id='adm_areaDown' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>?id=<?php echo $data->area_id ?>&order=<?php echo $data->ar_order_id ?>&do=2'><?php echo $section->language->lang066 ?></a>
                    </div>
                </td>
                <td class='field'>&nbsp;</td>
            </tr>
            <?php $__list = 'topics'.$data->area_id; foreach($section->$__list as $topic): ?>
                <tr>
                    <td class='field field_MainForum' id='field_MainForum'>
                        <div class='main_ForumName'>
                            <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub11/" ?>?id=<?php echo $topic->id_forum ?>' class='main_linkForum'><?php echo $topic->name ?></a>
                            <div class='main_ShDescr'><?php echo $topic->description ?></div>
                        </div>
                    </td>
                    <td class='field field_MainTopic' id='field_MainTopic'>
                        <div class='main_MessMount'><?php echo $topic->count ?></div>
                    </td>
                    <td class='field' id='fld_Menu'>
                        <div id='adm_cmdTopic'>
                            <a class='foru_Edit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $topic->id_forum ?>' id='adm_topicEdit'><?php echo $section->language->lang003 ?></a>
                            <a class='foru_Edit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $topic->id_forum ?>' id='adm_topicDel'><?php echo $section->language->lang004 ?></a>
                            <a class='foru_Edit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $topic->id_forum ?>&order=<?php echo $topic->forder_id ?>&do=1' id='adm_topicUp'><?php echo $section->language->lang065 ?></a>
                            <a class='foru_Edit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $topic->id_forum ?>&order=<?php echo $topic->forder_id ?>&do=2' id='adm_topicDown'><?php echo $section->language->lang066 ?></a>
                            <a class='foru_Edit' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub8/" ?>?id=<?php echo $topic->id_forum ?>' id="adm_topicModer"><?php echo $section->language->lang041 ?></a>
                        </div>
                    </td>
                </tr>
            
<?php endforeach; ?> 
            <tr>
                <td colspan=3 id='field_addTopic'>
                    <div id='main_addTopic'>
                        <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>" method='POST'>
                            <input type='hidden' name='area_place' value=<?php echo $data->area_id ?>>
                            <input class='buttonSend' id='main_btnAddForum' type='submit' value="<?php echo $section->language->lang067 ?>">
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td id='field_AddArea' colspan=3>
                <div id='main_addArea'>
                    <form action="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>" method=POST>
                        <input class='buttonSend' id='main_btnAddArea' type='submit' value="<?php echo $section->language->lang068 ?>">
                    </form>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<?php if(file_exists($__MDL_ROOT."/php/subpage_12.php")) include $__MDL_ROOT."/php/subpage_12.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_12.tpl")) include $__data->include_tpl($section, "subpage_12"); ?>
</div>
