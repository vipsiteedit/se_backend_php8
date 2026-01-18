<header:css>
[include_css] 

<?php if(strval($section->parametrs->param4)=='r'): ?>
    <style>
        .part<?php echo $section->id ?> .square_banner_block-image {
                background-size: 130% auto !important;
        }        
    </style>
<?php endif; ?>
<?php if(strval($section->parametrs->param7)=='a'): ?>
    <style>
        .part<?php echo $section->id ?> .square_banner_block-image:hover {
        top: -30%;
        }
    </style>
<?php endif; ?> 

</header:css>  

<div class="<?php if(strval($section->parametrs->param3)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<section class="square_banner_block part<?php echo $section->id ?>" >
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

 <a class="square_banner_block-link" href="<?php echo $record->field ?>" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
        <span class="square_banner_block-image" <?php if(!empty($record->image)): ?>style="background-image: url('<?php echo $record->image ?>')"<?php endif; ?> >
            <div class="apromo-item_text">
                    <?php if(strval($section->parametrs->param5)=='a'): ?>
                      <?php if(!empty($record->title)): ?>
                        <<?php echo $record->title_tag ?> class="objectTitle">
                             <span class="objectTitleTxt"><?php echo $record->title ?></span>
                        </<?php echo $record->title_tag ?>>
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param6)=='a'): ?>
                      <?php if(!empty($record->note)): ?>
                        <div class="objectNote"><?php echo $record->note ?></div>
                      <?php endif; ?>
                    <?php endif; ?>
            </div>
        </span>
 </a>
    
<?php endforeach; ?>
</section>
</div>    
           
