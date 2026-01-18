<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<header:js>
<script type="text/javascript">
    function loadSBox(id, name, value) {
        $('.'+id).load("?ajax<?php echo $razdel ?>",{name: ""+name+"",value: ""+value+""});
    } 
</script>
</header:js>
<div class="searchBlock">
    <form style="margin:0px" method="post" action="">
        <div class="obj searchFor">
            <label><?php echo $section->language->lang034 ?></label>
            <div>
                <select name="search_for" class="srchcat_select">
                    
                    <?php echo $search_for_list ?>
                </select> 
            </div>
        </div>
        <div class="obj fromTo">
            <label><?php echo $section->language->lang028 ?></label>
            <div class="from">
                <div class="title"><?php echo $section->language->lang029 ?></div>
                <input class="inp_txt" type="text" size="5" maxlength="10" name="from" value="<?php echo $from ?>">
            </div>
            <div class="to">
                <div class="title"><?php echo $section->language->lang030 ?></div>
                <input class="inp_txt" type="text" size="5" maxlength="10" name="to" value="<?php echo $to ?>">
            </div>
        </div>
        <div class="obj manufacture">
            <label><?php echo $section->language->lang027 ?></label>
            <div>      
                <!--input class="inp_txt" size="15" type="text" name="manufacture" value="<?php echo $manufacture ?>"-->
                <select class="inp_txt" name="manufacture">
                    <option value=''><?php echo $section->language->lang023 ?></option>
                    <?php foreach($section->manufacture as $inv): ?>
                        <option value='<?php echo $inv->name ?>' <?php if($inv->sel!=0): ?> selected<?php endif; ?>><?php echo $inv->name ?></option>
                    
<?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="obj cat">
            <label><?php echo $section->language->lang026 ?></label>
            <div>
                <select name="category1" class="srchcat_select" id="srchcat_cat1" onChange="loadSBox('srchcat_cat2','category1',this.value); loadSBox('srchcat_cat3','category2','-1');">
                    <option value="-1"><?php echo $section->language->lang024 ?></option>
                    
                    <?php echo $category1_list ?>
                </select> 
                <select name="category2" class="srchcat_select srchcat_cat2" onChange="loadSBox('srchcat_cat3','category2',this.value);">
                    <option value="-1"><?php echo $section->language->lang020 ?></option>
                    
                    <?php echo $category2_list ?>
                </select> 
                <select name="category3" class="srchcat_select srchcat_cat3">
                    <option value="-1"><?php echo $section->language->lang020 ?></option>
                    
                    <?php echo $category3_list ?>
                </select>
            </div>
        </div>
        <div class="obj searchWord">
            <label><?php echo $section->language->lang048 ?></label>
            <div>
                <input class="inp_txt" id="srchcat_wrd" size="23" type="text" name="word" value="<?php echo $word ?>">
                <input class="buttonSend search" type="submit" value="<?php echo $section->language->lang025 ?>" name="search">
                <input class="buttonSend clear" type="submit" value="<?php echo $section->language->lang022 ?>" name="clearsearch">
                <!--input class="buttonSend toshop" type="button" value="<?php echo $section->language->lang043 ?>" name="backtoshop" onclick="document.location.href='<?php echo seMultiDir()."/".$section->parametrs->param81."/" ?>';"-->
                <input class="buttonSend toshop" type="submit" value="<?php echo $section->language->lang043 ?>" name="backtoshop">
            </div>
        </div>
    </form>
</div>
