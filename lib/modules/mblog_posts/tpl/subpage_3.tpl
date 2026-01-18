<?php if(file_exists($__MDL_ROOT."/php/subpage_scripts.php")) include $__MDL_ROOT."/php/subpage_scripts.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_scripts.tpl")) include $__MDL_ROOT."/tpl/subpage_scripts.tpl"; ?>
<!--div class="blogposts">
    <div class="content blogposts edit_post">
        <h3 class="contentTitle"><?php echo $section->parametrs->param53 ?></h3>
        <?php if($flag==0): ?>
            <div class="errortext"><?php echo $errortext ?></div>
        <?php endif; ?>
        <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
            <div class="obj titlzag">
                <label class="title" for="name"><?php echo $section->parametrs->param19 ?></label>
                <div class="field">
                    <input class="inputs" id="title" name="title" value='<?php echo $title ?>' maxlength="255">
                </div>
            </div>
            <table class="tableTable useTabl1" border="0" cellSpacing="0" cellPadding="0">
                <tr class="tableRow tableHeader">
                    <td class="titl">
                        <div class="contentS1"><?php echo $section->parametrs->param27 ?>&nbsp;</div>
                    </td>
                    <td class="titl1">
                        <div class="contentS2">
                            <select style="width: 148px" name="selidcat[]" multiple="multiple">
                                <?php foreach($section->categories as $ctgrs): ?>
                                    <option <?php echo $ctgrs->selected ?> value="<?php echo $ctgrs->id ?>"><?php echo $ctgrs->name ?></option>
                                
<?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="obj anons">
                <label class="title titlfield_anons" for="text"><?php echo $section->parametrs->param20 ?></label>
                <div class="field">
                    <textarea class="inputs field_anons" id="anons" name="anons" rows="10" cols="40"><?php echo $anons ?></textarea>
                </div>
            </div>
            <div class="obj full">
                <label class="title titlfield_full" for="full"><?php echo $section->parametrs->param21 ?></label>
                <div class="field">
                    <textarea class="inputs field_full" id="full" name="full" rows="10" cols="40"><?php echo $full ?></textarea>
                </div>
            </div>
            <div class="obj keywords">
                <label class="title titlinp_keywords" for="keywords"><?php echo $section->parametrs->param22 ?></label>
                <div class="field">
                    <input class="inputs inp_keywords" id="keywords" name="keywords" value="<?php echo $keywords ?>" maxlength="255">
                </div>
            </div>
            <div class="obj description">
                <label class="title titlinp_description" for="description"><?php echo $section->parametrs->param23 ?></label>
                <div class="field">
                    <input class="inputs inp_description" id="description" name="description" value="<?php echo $description ?>" maxlength="255">
                </div>
            </div>
            <div class="obj tegi">
                <label class="title titlinp_tegi" for="name"><?php echo $section->parametrs->param24 ?></label>
                <div class="field">
                    <input class="inputs inp_tegi" id="tegi" name="tegi" value="<?php echo $tegi ?>" maxlength="255">
                </div>
            </div>
            <div class="razcom">
                <label class="title titlrazcom" for="razcom"><?php echo $section->parametrs->param51 ?></label>
                <select name="razcom">
                    <option value="yes"><?php echo $section->parametrs->param28 ?></option>
                    <option value="no" <?php if($razcom=="no"): ?> selected <?php endif; ?>><?php echo $section->parametrs->param29 ?></option>
                </select>
            </div>
            <div class="skrit">
                <label class="title titlimpskrit" for="skrit"><?php echo $section->parametrs->param52 ?></label>
                <select name="skrit">
                    <option value="no"><?php echo $section->parametrs->param30 ?></option>
                    <option value="yes"<?php if($skrit=="yes"): ?> selected<?php endif; ?>><?php echo $section->parametrs->param31 ?></option>
                </select>
            </div>
            <div class="groupButton">
                <input class="buttonSend goButton" name="GoTonewblog" type="submit" value="<?php echo $section->parametrs->param34 ?>">
                <input class="buttonSend delButton" name="delkom" type="submit" value="<?php echo $section->parametrs->param4 ?>">
                <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->parametrs->param17 ?>">
            </div>
        </form>
    </div>
</div-->
