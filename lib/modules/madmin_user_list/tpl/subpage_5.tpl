<header:js>
[js:jquery/jquery.min.js]
</header:js>
<script type='text/javascript'>
    function editGroup(id) {
        if (!id) {
            $(".group").val(0);
            $(".groupdt .title input").val('<?php echo $title ?>');
            $(".groupdt .name input").val('<?php echo $name ?>');
            $(".access .access<?php echo $level ?> input").attr('checked', 'checked');
        } else {
            $(".group").val(<?php echo $currid ?>);
            $(".groupdt .title input").val('<?php echo $grpttl2 ?>');
            $(".groupdt .name input").val('<?php echo $grpnm2 ?>');
            $(".access .access<?php echo $glevel ?> input").attr('checked', 'checked');
        }
        $(".groupdt").css("display", "block");
    }
    function deleteGroup() {
        if (confirm("<?php echo $section->language->lang081 ?>")) {
            
                document.location.href = "<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>?delGroup";
            
        }
    }
    function editUsers(type) {
        $(".others .othertype").val(type);
        if (type == 1) {
            $(".others .group span").html('<?php echo $section->language->lang100 ?>');
        } else {
            $(".others .group span").html('<?php echo $section->language->lang101 ?>');
        }
        $(".others").css("display", "block");
    }
    function delUsers() {
        if (confirm("<?php echo $section->language->lang082 ?>")) {
            
                $(".delUser").val(1);
                $(".grpUsers").submit();
            
        }
    }
    function selAll(name) {
        $(name).attr('checked', 'checked');
    } 
    function unSel(name) {
        $(name).removeAttr('checked');
    } 
    $(function() {
        $(".others .cancel").click(function(){
            $(".others").css("display", "none");
        });
        $(".groupdt .cancel").click(function(){
            $(".groupdt").css("display", "none");
        });
    }); 
</script>
<style type='text/css'>
    .groupdt {
        display: none;
    }
    .others {
        display: none;
    }
</style>
<div class="content adminUserList usersGroups">
    <?php if($errorRes!=''): ?>
        <div class="error">
            <span><?php echo $errorRes ?></span>
        </div>
    <?php endif; ?>
    <div class='groupList'>
        <form method='get' action='' class="frmGrLst">
            <span><?php echo $section->language->lang015 ?></span>
            <select name='group' onchange="$('.frmGrLst').submit();">
                <?php if($currid!=0): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_4.php")) include $__MDL_ROOT."/php/subpage_4.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_4.tpl")) include $__data->include_tpl($section, "subpage_4"); ?>
                <?php else: ?>
                    <option>--<?php echo $section->language->lang083 ?>--</option>
                <?php endif; ?>
            </select>
            <div>
                <a href='javascript:editGroup(0);' class='new'><?php echo $section->language->lang084 ?></a>
                <?php if($currid!=0): ?>
                    <a href='javascript:editGroup(<?php echo $currid ?>);' class='edit'><?php echo $section->language->lang085 ?></a>
                    <a href='javascript:deleteGroup();' class='del'><?php echo $section->language->lang086 ?></a>
                <?php endif; ?>
            </div>
        </form>
    </div>   
    <div class='groupdt'>
        <form method='get' action=''>
            <input type='hidden' name='group' value="<?php echo $currid ?>" class="group">
            <input type='hidden' name='oldgroup' value="<?php echo $currid ?>" class="oldgroup">
            <div class="title">
                <span><?php echo $section->language->lang095 ?></span>
                <input type="text" name='grpttl' value='<?php echo $grpttl ?>'>
            </div>
            <div class="name">
                <span><?php echo $section->language->lang096 ?></span>
                <input type="text" name='grpnm' value='<?php echo $grpnm ?>'>
                <div>
                    <em><?php echo $section->language->lang097 ?></em>
                </div>
            </div>
            <div class="access">
                <span class='atitle'><?php echo $section->language->lang098 ?>:</span>
                <div class="access1">
                    <input type='radio' value='1' name="levelAccess" <?php if($glevel==1): ?>checked<?php endif; ?>>
                    <span><?php echo $section->language->lang032 ?></span>
                </div>
                <div class="access2">
                    <input type='radio' value='2' name="levelAccess" <?php if($glevel==1): ?>checked<?php endif; ?>>
                    <span><?php echo $section->language->lang033 ?></span>
                </div>
                <div class="access3">
                    <input type='radio' value='3' name="levelAccess" <?php if($glevel==1): ?>checked<?php endif; ?>>
                    <span><?php echo $section->language->lang034 ?></span>
                </div>
            </div>
            <div class='button'>
                <input type='submit' class='buttonSend editGroup' name="editGroup" value='<?php echo $section->language->lang072 ?>'>
                <input type='button' class='buttonSend cancel' value='<?php echo $section->language->lang099 ?>'>
            </div>
        </form>  
    </div>                     
    <?php if($currid!=0): ?>
        <form method='get' class="grpUsers">
            <div class="title">
                <span><?php echo $section->language->lang087 ?>&nbsp;<?php echo $grpnm ?><?php if($grpttl!=''): ?>&nbsp;(<?php echo $grpttl ?>)<?php endif; ?></span>
            </div>
            <div class='buttons'>
                <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>' class='add'><?php echo $section->language->lang088 ?></a>
                <?php if($gUsers!=0): ?>
                    <a href='javascript:editUsers(1);' class='move'><?php echo $section->language->lang089 ?></a>
                    <a href='javascript:editUsers(2);' class='copy'><?php echo $section->language->lang090 ?></a>
                    <a href='javascript:delUsers();' class='del'><?php echo $section->language->lang086 ?></a>
                    <input type="hidden" name="delUser" class="delUser" value='0'>
                <?php endif; ?>
            </div>
            <div class="others">
                <input type='hidden' name='othertype' class='othertype' value='<?php echo $othertype ?>'>
                <div class="subothers">
                    <div class="group">
                        <span><?php echo $section->language->lang101 ?></span>
                        <select name="otherGroup">
                            <?php foreach($section->othergroup as $grp): ?>
                                <option value="<?php echo $grp->id ?>">
                                    <?php if($grp->name!=''): ?><?php echo $grp->name ?><?php else: ?>---<?php endif; ?>
                                    <?php if($grp->title!=''): ?>(<?php echo $grp->title ?>)<?php endif; ?>
                                </option>
                            
<?php endforeach; ?>
                        </select>
                    </div>
                    <div class='buttons'>
                        <input type="submit" name="add" class="buttonSend add" value="<?php echo $section->language->lang102 ?>">
                        <input type='button' name="cancel" class="buttonSend cancel" value="<?php echo $section->language->lang099 ?>">
                    </div>            
                </div>
            </div>
            <?php if($gUsers!=0): ?>
                <div class="searchmy">
                    <input type="text" class="search" name="searchmy" value="<?php echo $searchmy ?>" placeholder='<?php echo $section->language->lang112 ?>'>
                    <input type="submit" class="buttonSend" name="searchmyb" value="<?php echo $section->language->lang037 ?>">                
                </div>
                <div class="pages" id="navPart">
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_6.php")) include $__MDL_ROOT."/php/subpage_6.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_6.tpl")) include $__data->include_tpl($section, "subpage_6"); ?>
                </div>
            <?php endif; ?>
            <div class="users">
                <?php if($gUsers!=0): ?>
                    <?php foreach($section->ingroup as $in): ?>
                        <div class="user inp_<?php echo $in->style ?>">
                            <input type='checkbox' name="ingroup[<?php echo $in->id ?>]" value="<?php echo $in->id ?>"> <!--<?php if($in->sel!=0): ?> checked<?php endif; ?>-->
                            <span><?php echo $in->username ?></span>
                            <a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>user/<?php echo $in->id ?>/'><?php echo $section->language->lang085 ?></a>
                        </div>
                    
<?php endforeach; ?>
                    
                <?php else: ?>
                    <div class="empty">
                        <span><?php echo $section->language->lang091 ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <?php if($gUsers!=0): ?>
                <div class="forAll myusers">
                    <a href="javascript:selAll('.users .user :checkbox');" class="select"><?php echo $section->language->lang092 ?></a>
                    <a href='javascript:unSel(".users .user :checkbox");' class="unselect"><?php echo $section->language->lang093 ?></a>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
    <div class='returnToMain'>
        <input type='button' class='buttonSend' value='<?php echo $section->language->lang094 ?>' onclick="document.location='<?php echo seMultiDir()."/".$_page."/" ?>';">        
    </div>
</div>


