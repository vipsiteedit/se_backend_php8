<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<div class="content adminUserList mainPage" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <form method='post' action='' class="search">
        <input type="search" value="<?php echo $search ?>" placeholder="<?php echo $section->language->lang112 ?>" name='search' class="text">
        <input type="submit" value="<?php echo $section->language->lang037 ?>" name='searchRun' class="buttonSend">
        <select name="searchtype">
            <option value="login" <?php echo $selecttype['login'] ?>><?php echo $section->language->lang035 ?></option>
            <option value="name" <?php echo $selecttype['name'] ?>><?php echo $section->language->lang030 ?></option>
        </select>
    </form>
    <form method='post' action='' id="frmAdminUsr" class="groupList">
        <span><?php echo $section->language->lang038 ?></span>
        <select name="usergroup" onchange="document.getElementById('frmAdminUsr').submit();">
            <option value="0"><?php echo $section->language->lang016 ?></option>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_4.php")) include $__MDL_ROOT."/php/subpage_4.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_4.tpl")) include $__data->include_tpl($section, "subpage_4"); ?>
        </select>
        <a class="groupEditor" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>"><?php echo $section->language->lang039 ?></a>
    </form>
    <form method='get' class="pages">
        <div id="navPart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_6.php")) include $__MDL_ROOT."/php/subpage_6.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_6.tpl")) include $__data->include_tpl($section, "subpage_6"); ?>
        </div>
    </form>
    <?php if($userCount!=0): ?>
        <table border="0" cellPadding="0" cellSpacing="0" class="tableTable userTable">
            <tbody class="tableBody"> 
                <tr class="tableRow tableHeader">
                    <th class="usid tusers">
                        <a href="?sorttype=id&sortway=<?php echo $sortway ?>"><?php echo $section->language->lang029 ?></a>
                    </th>
                    <th class="uslogin tusers">
                        <a href="?sorttype=username&sortway=<?php echo $sortway ?>"><?php echo $section->language->lang006 ?></a>
                    </th>
                    <th class="usfullname tusers">
                        <a href="?sorttype=fullname&sortway=<?php echo $sortway ?>"><?php echo $section->language->lang007 ?></a>
                    </th>
                    <th class="usgroup tusers">
                        <span><?php echo $section->language->lang015 ?></span>
                    </th>
                    <th class="udatereg tusers">
                        <a href="?sorttype=reg_date&sortway=<?php echo $sortway ?>"><?php echo $section->language->lang010 ?></a>
                    </th>
                    <th class="uactive tusers">
                        <span><?php echo $section->language->lang011 ?></span>
                    </th>
                    <th class="datelog tusers">
                        <a href="?sorttype=last_login&sortway=<?php echo $sortway ?>"><?php echo $section->language->lang014 ?></a>
                    </th>
                </tr>
                <?php foreach($section->objects as $record): ?>
                    <tr class="tableRow tableData <?php if($record->style!=0): ?>tableRowOdd<?php else: ?>tableRowEven<?php endif; ?> <?php if($record->is_active=='Y'): ?>authYes<?php else: ?>authNo<?php endif; ?>">
                        <td class="usid tusers">
                            <span><?php echo $record->id ?></span>
                        </td>
                        <td class="uslogin tusers">
                            <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>user/<?php echo $record->id ?>/"><?php echo $record->username ?></a>
                        </td>
                        <td class="usfullname tusers">
                            <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>user/<?php echo $record->id ?>/"><?php echo $record->fullname ?></a>
                        </td>
                        <td class="usgroup tusers">
                            
                                <?php $__list = 'usergroup'.$record->id; foreach($section->$__list as $usrg): ?>
                                    <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>group/<?php echo $usrg->id ?>/"><?php echo $usrg->name ?></a>&nbsp;
                                
<?php endforeach; ?>
                            
                            
                        </td>
                        <td class="udatereg tusers">
                            <span><?php echo $record->reg_date ?></span>
                        </td>
                        <td class="uactive tusers">
                            <span><?php if($record->is_active=='Y'): ?><?php echo $section->language->lang012 ?><?php else: ?><?php echo $section->language->lang013 ?><?php endif; ?></span>
                        </td>
                        <td class="datelog tusers">
                            <span><?php echo $record->last_login ?></span>
                        </td>
                    </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <div class="userListEmpty">
            <span><?php echo $section->language->lang040 ?></span>
        </div>
    <?php endif; ?>
</div> 

