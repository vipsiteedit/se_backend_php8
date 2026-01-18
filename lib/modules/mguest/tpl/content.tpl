<div class="content cont_guest part<?php echo $section->id ?>" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span ondblclick="document.location.href='<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>';" class="contentTitleTxt se-login-modal"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($userAccess==3): ?>
    <div class="block-link-admin">
        <ul>
        <li><a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subblock/" ?>" class="blockip"><?php echo $section->language->lang020 ?></a></li>
        <li><a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subnoconfirm/" ?>" class="noconfirm"><?php echo $section->language->lang041 ?></a></li>
        </ul>
    </div>
    <?php endif; ?> 
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $usrcode ?>" name="usrcode">
        <div id="guest">
            <div id="comments">
                <?php if($addcomment): ?><h4 style="color: red;" class="info"><?php echo $section->language->lang042 ?></h4><?php endif; ?>
                <?php foreach($section->comments as $com): ?>
                    <div class="comm">
                        <div class="userdat">
                            <a class="adm_lnk" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subedit/" ?>?id=<?php echo $com->id ?>"> »</a>
                            <label class="date">
                                <?php echo $com->date ?>
                                <?php if($admin==1): ?>
                                    &nbsp;[<?php if($com->active=='Y'): ?><b style="color: green;">Подтвержден
                                    <?php else: ?><b style="color: red;">Не подтвержден<?php endif; ?>
                                    </b>]    
                                <?php endif; ?></label>
                        
                            <?php if($userAccess!=0): ?>
                                <a class="name" href="mailto:<?php echo $com->usrmail ?>" name=record<?php echo $com->id ?>><?php echo $com->usrname ?></a>
                            <?php else: ?>
                                <span class="name"><?php echo $com->usrname ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="com_txt"><?php echo $com->usrnote ?></div>
                        <?php if($com->admtext!=''): ?>
                            <div class="adm_txt">
                                <label class="admin_label"><?php echo $section->language->lang005 ?></label>
                                <div class="admtext"><?php echo $com->admtext ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                
<?php endforeach; ?>
                <?php if($countpage!=0): ?>
                    <div class="pageselector" id="navPart">
                        <?php if($pagen==1): ?>
                            <b class="activpg arr" id="Back">&larr;</b>
                            <b class="activpg Active">1</b>
                        <?php else: ?>
                            <a class="pagesel arr" id="Back" href="?p=<?php echo $prevpage ?>">&larr;</a>
                            <a class="pagesel links" href="?p=1">1</a>
                        <?php endif; ?>
                        <?php if($pgbegin!=2): ?>
                            <span class="points lpoints">...</span>
                        <?php endif; ?>
                        <?php foreach($section->pages as $pg): ?>
                            <?php if($pg->sel==0): ?>
                                <a class="pagesel links" href="?p=<?php echo $pg->pg ?>"><?php echo $pg->pg ?></a>
                            <?php else: ?>
                                <b class="activpg Active"><?php echo $pg->pg ?></b>
                            <?php endif; ?>
                        
<?php endforeach; ?>
                        <?php if($pgpoint_end!=0): ?>
                            <span class="points rpoints">...</span>
                        <?php endif; ?>
                        <?php if($nextpage==0): ?>
                            <b class="activpg Active"><?php echo $maxpage ?></b>
                            <b class="activpg arr" id="Next">&rarr;</b>
                        <?php else: ?>
                            <a class="pagesel links" href="?p=<?php echo $maxpage ?>"><?php echo $maxpage ?></a>
                            <a class="pagesel arr" id="Next" href="?p=<?php echo $nextpage ?>">&rarr;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>     
            </div>
            <table class="tableTable" id="guesttab" width="400">
                <?php if($usrblock!=''): ?>
                    <div class="supererror">
                        <span><?php echo $usrblock ?></span>
                    </div> 
                <?php endif; ?>
                <tbody>
                    <tr>
                        <td width="40%" class="tablrow tabltext">
                            <span class="titleTab nameTtl"><?php echo $section->language->lang027 ?></span>
                        </td>               
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp <?php echo $errstname ?> nameInp" value="<?php echo $usrname_inform ?>" name="usrname">
                            <?php if($errorname!=''): ?>
                                <div class="error">
                                    <span><?php echo $errorname ?></span>
                                </div>
                            <?php endif; ?>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow tabltext">
                            <span class="titleTab emailTtl"><?php echo $section->language->lang026 ?></span>
                        </td>                
                        <td class="tablrow">
                            <input type="text" maxlength="50" class="inp <?php echo $errstmail ?> emailInp" value="<?php echo $usrmail_inform ?>" name="usrmail">
                            <?php if($errormail!=''): ?>
                                <div class="error">
                                    <span><?php echo $errormail ?></span>
                                </div>
                            <?php endif; ?>
                        </td>  
                    </tr>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <label class="titleTab commentTtl"><?php echo $section->language->lang025 ?></label>
                            <br>
                            <textarea id="textar" class="inp <?php echo $errstnote ?>" name="note" rows="7" maxlength="<?php echo $section->parametrs->param35 ?>" cols="36"><?php echo $usrnote_inform ?></textarea>
                            <?php if($errornote!=''): ?>
                                <div class="error sysedit"><?php echo $errornote ?></div>
                            <?php endif; ?> 
                        </td>                  
                    </tr>
                    <?php if(strval($section->parametrs->param2)=="Yes"): ?>
                        <tr> 
                            <td colspan="2" class="tablrow">
                                <?php echo $anti_spam ?>
                                
                            </td> 
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="tablrow" colspan="2">
                            <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
                            <?php if($errorlicense!=''): ?>
                                <div class="error">
                                    <span><?php echo $errorlicense ?></span>
                                </div>
                            <?php endif; ?>
                            <input type="submit" value="<?php echo $section->language->lang024 ?>" name="SaveGuest<?php echo $section->id ?>" id="but" class="buttonSend">
                        </td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </form> 
</div>
