<?php if(file_exists($__MDL_ROOT."/php/subpage_5.php")) include $__MDL_ROOT."/php/subpage_5.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_5.tpl")) include $__MDL_ROOT."/tpl/subpage_5.tpl"; ?>
<div class="content blogposts add_post edit_post">
    <h3 class="contentTitle"><?php if($id==0): ?><?php echo $section->parametrs->param18 ?><?php else: ?><?php echo $section->parametrs->param53 ?><?php endif; ?></h3>
    <?php if($errortext!=''): ?>
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
                    <div class="ttl">
                        <span><?php echo $section->parametrs->param27 ?></span>
                    </div>
                    <div class="categories">
                        <?php foreach($section->categories as $ctgrs): ?>
                            <div class="category level<?php echo $ctgrs->level ?>">
                                <input type="checkbox" name="selidcat[]" value="<?php echo $ctgrs->id ?>"<?php echo $ctgrs->checked ?>>
                                <span><?php echo $ctgrs->name ?></span>
                            </div>
                        
<?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>
        <div class="obj event">
            <div class="on_event">
                <input type='checkbox' name='setevent'<?php if($event!=0): ?> checked=checked<?php endif; ?> onchange="$(this).parent().find('+ div').toggle();">
                <span>Установить как событие</span>
            </div>
            <div class="event_dt"<?php if($event==0): ?> style="display: none;"<?php endif; ?>>
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
            <?php if($id!=0): ?>
                <input class="buttonSend delButton" name="delkom" type="submit" value="<?php echo $section->parametrs->param4 ?>">
            <?php endif; ?>
            <input class="buttonSend backButton" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->parametrs->param17 ?>">
        </div>
    </form>
</div>
