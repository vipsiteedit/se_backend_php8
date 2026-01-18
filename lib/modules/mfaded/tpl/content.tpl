<header:js>
    <style type="text/css">
        .fadedImages .faded * {margin:0px;}
        .faded.none {display:none;}
    </style>
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
[module_js:jscript_jquery.faded.js]
<script type="text/javascript">
    $(function(){
        $(".faded.n<?php echo $section->id ?>").faded({
            speed: '<?php echo $section->parametrs->param2 ?>',
            autoplay: '<?php echo $section->parametrs->param3 ?>',
            random: '<?php echo $section->parametrs->param4 ?>'
        });
        $(".faded.none").removeClass('none');
    });
</script>
</footer:js>
<div class="content fadedImages" data-seimglist="<?php echo $section->id ?>" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <div class="faded n<?php echo $section->id ?> none">
        <ul class="fadedArea">
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <li class="object"<?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                    <?php if(!empty($record->title)): ?>
                        <<?php echo $record->title_tag ?> class="objectTitle">
                            <span class="objectTitleTxt"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                        </<?php echo $record->title_tag ?>>
                    <?php endif; ?>
                    <?php if(!empty($record->image)): ?>
                        <img class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                    <?php endif; ?>
                    <?php if(!empty($record->note)): ?>
                        <div class="objectNote"><?php echo $record->note ?></div>
                    <?php endif; ?>
                    <?php if(!empty($record->field)): ?>
                        <a class="linkNext" href="<?php echo $record->field ?>"><?php echo $section->language->lang001 ?></a>
                    <?php endif; ?>
                </li>
            
<?php endforeach; ?>
        </ul>
    </div>
</div>
