<?php if(file_exists($__MDL_ROOT."/php/subpage_scripts.php")) include $__MDL_ROOT."/php/subpage_scripts.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_scripts.tpl")) include $__data->include_tpl($section, "subpage_scripts"); ?>
<div class="content blogposts add_post edit_post">
    <h3 class="contentTitle"><?php if($id==0): ?><?php echo $section->language->lang038 ?><?php else: ?><?php echo $section->language->lang059 ?><?php endif; ?></h3>
    <?php if($errortext!=''): ?>
        <div class="errortext"><?php echo $errortext ?></div>
    <?php endif; ?>  
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
        <div class="obj titlzag">
            <label class="title" for="name"><?php echo $section->language->lang039 ?></label>
            <div class="field">
                <input class="inputs" id="title" name="title" value='<?php echo $title ?>' maxlength="255">
            </div>
        </div>
                <div class="useTabl1">
                    <div class="ttl">
                        <span><?php echo $section->language->lang049 ?></span>
                    </div>
                    <div class="categories">
                        <?php foreach($section->categories as $ctgrs): ?>
                            <div class="category level<?php echo $ctgrs->level ?>">
                                <input type="checkbox" name="selidcat[]" value="<?php echo $ctgrs->id ?>"<?php echo $ctgrs->checked ?>>
                                <span><?php echo $ctgrs->name ?></span>
                            </div>
                        
<?php endforeach; ?>
                    </div>
                </div>
        <div class="obj event">
            <div class="on_event">
                <input type='checkbox' name='setevent'<?php if($event!=0): ?> checked=checked<?php endif; ?>>
                <span>Установить как событие</span>
            </div>
            <div class="event_dt">
                <div class="time">
                    <span class='ttl'>Время (часы:минуты): </span>
                    <input type='text' class="ehour" name="event_hour" value="<?php echo $ehour ?>" size=3 maxlength=2>
                    <span class='div'>:</span>
                    <input type='text' class="eminute" name="event_minute" value="<?php echo $eminute ?>" size=3 maxlength=2>
                </div>
                <div class="date">
                    <span class='ttl'>Дата (день, месяц, год): </span>
                    <input type='text' class="event_day" name="event_day" value="<?php echo $eday ?>" size=3 maxlength=2>
                    <select name="event_month">
                        <option value='1'<?php if($emonth==1): ?> selected<?php endif; ?>>январь</option>
                        <option value='2'<?php if($emonth==2): ?> selected<?php endif; ?>>февраль</option>
                        <option value='3'<?php if($emonth==3): ?> selected<?php endif; ?>>март</option>
                        <option value='4'<?php if($emonth==4): ?> selected<?php endif; ?>>апрель</option>
                        <option value='5'<?php if($emonth==5): ?> selected<?php endif; ?>>май</option>
                        <option value='6'<?php if($emonth==6): ?> selected<?php endif; ?>>июнь</option>
                        <option value='7'<?php if($emonth==7): ?> selected<?php endif; ?>>июль</option>
                        <option value='8'<?php if($emonth==8): ?> selected<?php endif; ?>>август</option>
                        <option value='9'<?php if($emonth==9): ?> selected<?php endif; ?>>сентярь</option>
                        <option value='10'<?php if($emonth==10): ?> selected<?php endif; ?>>октябрь</option>
                        <option value='11'<?php if($emonth==11): ?> selected<?php endif; ?>>ноябрь</option>
                        <option value='12'<?php if($emonth==12): ?> selected<?php endif; ?>>декабрь</option>
                    </select>
                    <input type='text' class="event_year" name="event_year" value="<?php echo $eyear ?>" size=5 maxlength=4>
                </div>    
            </div>
        </div>
        <div class="obj anons">
            <label class="title titlfield_anons" for="text"><?php echo $section->language->lang040 ?></label>
            <div class="field">
                <textarea class="inputs field_anons" id="anons" name="anons" rows="10" cols="40"><?php echo $anons ?></textarea>
            </div>
        </div>
        <div class="obj full">
            <label class="title titlfield_full" for="full"><?php echo $section->language->lang041 ?></label>
            <div class="field">
                <textarea class="inputs field_full" id="full" name="full" rows="10" cols="40"><?php echo $full ?></textarea>
            </div>
        </div>
        <div class="obj keywords">
            <label class="title titlinp_keywords" for="keywords"><?php echo $section->language->lang042 ?></label>
            <div class="field">
                <input class="inputs inp_keywords" id="keywords" name="keywords" value="<?php echo $keywords ?>" maxlength="255">
            </div>
        </div>
        <div class="obj description">
            <label class="title titlinp_description" for="description"><?php echo $section->language->lang043 ?></label>
            <div class="field">
                <input class="inputs inp_description" id="description" name="description" value="<?php echo $description ?>" maxlength="255">
            </div>
        </div>
        <div class="obj tegi">
            <label class="title titlinp_tegi" for="name"><?php echo $section->language->lang044 ?></label>
            <div class="field">
                <input class="inputs inp_tegi" id="tegi" name="tegi" value="<?php echo $tegi ?>" maxlength="255">
            </div>
        </div>
        <div class="razcom dopbl">
            <label class="title titlrazcom" for="razcom"><?php echo $section->language->lang057 ?></label>
            <select name="razcom">
                <option value="yes"><?php echo $section->language->lang045 ?></option>
                <option value="no" <?php if($razcom=="no"): ?> selected <?php endif; ?>><?php echo $section->language->lang046 ?></option>
            </select>
        </div>
        <div class="skrit dopbl">
            <label class="title titlimpskrit" for="skrit"><?php echo $section->language->lang058 ?></label>
            <select name="skrit">
                <option value="no"><?php echo $section->language->lang077 ?></option>
                <option value="yes"<?php if($skrit=="yes"): ?> selected<?php endif; ?>><?php echo $section->language->lang076 ?></option>
            </select>
        </div>
        <div class="showshort dopbl">
            <label class="title titleshowshort" for="showshort"><?php echo $section->language->lang073 ?></label>
            <select name="showshort">
                <option value="no"><?php echo $section->language->lang075 ?></option>
                <option value="yes"<?php if($showshort=="yes"): ?> selected<?php endif; ?>><?php echo $section->language->lang074 ?></option>
            </select>
        </div>
        <div class="groupButton">
            <input class="buttonSend goButton" name="GoTonewblog" type="submit" value="<?php echo $section->language->lang028 ?>">
            <?php if($id!=0): ?>
                <input class="buttonSend delButton" name="delkom" type="submit" value="<?php echo $section->language->lang027 ?>">
            <?php endif; ?>
            <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->language->lang037 ?>">
        </div>
    </form>
</div>
