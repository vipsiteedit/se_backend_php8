
<header:js>
    [js:jquery/jquery.min.js]
</header:js>

<script type="text/javascript">
    function loadBox(id, name, idlink) {
        $('#'+id).load("/<?php echo $_page ?>/<?php echo $razdel ?>/sub4/?" + name + "&idlink=" + idlink, {});
//        alert(1232);
    }
</script>
<div class="cpagenactiveontent contFCtlg" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
        <div class="obj search">
            <label class="searchtitl" for="searchtxt"><?php echo $section->parametrs->param105 ?></label>
            <input class="input" id="searchtxt" name="searchtxt" value="<?php echo $search ?>" maxlength="50"> 
            <input class="buttonSend goButton" name="GoSearch" type="submit" value="<?php echo $section->parametrs->param106 ?>">
            <div class="paramsearch">
                <input class="inputs" type="checkbox" name="psearsh" value="false" id="psearsh">
                <label class="title"><?php echo $section->parametrs->param104 ?></label>
            </div> 
        </div>
    </form>
    <?php echo $filecatalog_add_link ?>
    <br>
     
    <?php echo $MANYPAGE ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object">
            <h4 class="objectTitle">
                <!--SE>
                    <a class="editbbs" title="<?php echo $section->parametrs->param7 ?>" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>"><?php echo $section->parametrs->param6 ?></a>
                </SE-->
                <?php if($record->icanedit!=0): ?>
                   <a class="editbbs" title="<?php echo $section->parametrs->param7 ?>" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>id/<?php echo $record->id ?>/"><?php echo $section->parametrs->param6 ?></a>
                <?php endif; ?>
                <span class="objectTitleTxt"><?php echo $record->title ?></span>
            </h4>
            <div class='imageLayer'>
                <a  href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>object/<?php echo $record->id ?>"><?php echo $record->image ?></a>
            </div>
            <div class="objectNote">
                <a class="shortText" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>object/<?php echo $record->id ?>/"><?php echo $record->note ?></a>
            </div>
            <div class="contact">
                <div class="obj date">
                    <div class="name"><?php echo $section->parametrs->param11 ?></div>
                    <b class="text"><?php echo $record->date ?></b>
                </div>
                <div class="obj authorName">
                    <div class="name"><?php echo $section->parametrs->param12 ?></div>
                    <b class="text"><?php echo $record->name ?></b>   
                </div>
                <div class="obj prosmotri">
                    <div class="name"><?php echo $section->parametrs->param102 ?></div>
                    <b class="text"><?php echo $record->viewing ?></b>
                </div>
                <div class="obj statistics">
                    <div class="name"><?php echo $section->parametrs->param14 ?></div>
                    <b class="text"><?php echo $record->statistics ?></b>
                </div>
                <input class="buttonSend buttonDowload"  type="button" onclick="loadBox('linksspisok<?php echo $record->id ?>','showlink',<?php echo $record->id ?>);"  value="<?php echo $section->parametrs->param34 ?>">  
                <div class="linksspisok" id="linksspisok<?php echo $record->id ?>">
                    
                </div> 
            </div> 
        </div>
    
<?php endforeach; ?>
    <?php echo $MANYPAGE ?>
</div> 
