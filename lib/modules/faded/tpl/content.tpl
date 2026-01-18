<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<header:js>
    <script type="text/javascript" src="[this_url_modul]jscript_jquery.faded.js"></script>
</header:js>
<header:js>
    <style type="text/css">
        .fadedImages .faded * {margin:0px;}
    </style>
</header:js>
<script type="text/javascript">
    $(function(){
        $(".faded.n<?php echo $section->id ?>").faded({
            speed: <?php echo $section->parametrs->param2 ?>,
            autoplay: <?php echo $section->parametrs->param3 ?>,
            random: <?php echo $section->parametrs->param4 ?>
        });
    });
</script>
<div class="content fadedImages"<?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <div class="faded n<?php echo $section->id ?>">
        <ul class="fadedArea">
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <li class="object"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <?php if(!empty($record->title)): ?>
                        <h4 class="objectTitle">
                            <span class="objectTitleTxt"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                        </h4>
                    <?php endif; ?>
                    <?php if(!empty($record->image)): ?>
                        <img class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>">
                    <?php endif; ?>
                    <?php if(!empty($record->note)): ?>
                        <div class="objectNote"><?php echo $record->note ?></div>
                    <?php endif; ?>
                    <?php if(!empty($record->field)): ?>
                        <a class="linkNext" href="<?php echo $record->field ?>"><?php echo $section->parametrs->param1 ?></a>
                    <?php endif; ?>
                </li>
            
<?php endforeach; ?>
        </ul>
    </div>
</div>
