<header:js>
[js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript"> 
<!--
// функция скрывающая список выбора через 200мс после вызова 
    function hide() { 
        setTimeout("$('#suggestions').hide()", 200); 
    } // hide 
 
// вызывается при изменении поля ввода 
    function lookup(inputString) {
        if(inputString.length == 0) { 
        // если длина в поле ввода нулевая то скрываем блок выбора 
            hide(); 
        } else { 
        // посылаем данные методом post на сервер 
            $.post("?jquery_shop_search", {shopsearch: ""+inputString+"",shopcat: '<?php echo $shopcatgr ?>'}, function(data) { 
            // функция срабатывающая при получении ответа
                if(data.length > 0) { 
            // если вообще какие то данные получены то просто их отображаем 
                    $('#suggestions').show();
                     
                        $('#suggestions').html(data);   
                     
                } else {
                    $('#suggestions').hide();
                } 
            });
        } 
    } // lookup 
// эта функция вызывается когда произошел клик по списку выбора
    function fill(thisValue) {        
    // скрывает список
        $('#groupSearchForm').submit();   
        hide();
    } // fill 
// заполняет input значением 
    function auto(thisValue) {        
        $('#searchString').val(thisValue); 
    } 
-->
</script>
<div class="content shopgroups" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0" <?php echo $section->style_image ?>>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <div class="groupPath"><?php echo $SHOWPATH ?></div>
        <div class="search">
            <form id="groupSearchForm" style="margin:0px" action="<?php echo seMultiDir()."/".$section->parametrs->param19."/" ?>" method="post">
                <label><?php echo $section->language->lang006 ?></label>
                <div class="inputHint">
                    <input type="text" id="searchString" name="shopsearch" onkeyup="lookup(this.value);" onblur="hide();" alt="<?php echo $section->language->lang007 ?>" onfocus="if (this.value=='<?php echo $section->language->lang007 ?>') this.value='';" autocomplete="off"  value="<?php echo $section->language->lang007 ?>"><br>
                    <div class="suggestionsBox" id="suggestions" style="display:none; position:absolute; z-index:1001;"> 
                                                   
                    </div>       
                </div>
            </form>
        </div>
        <div class="groupContent">
            <?php if($shopcatgr==$basegroup): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__data->include_tpl($section, "subpage_2"); ?>
            <?php else: ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_1.tpl")) include $__data->include_tpl($section, "subpage_1"); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
