
    <div class="content cont_txt" id="view">
    <?php if(strval($section->parametrs->param4)=='Y'): ?>
        <a name="show<?php echo $section->id ?>_<?php echo $record->id ?>"></a>
    <?php endif; ?>
    <div class="record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
        <?php if(!empty($record->title)): ?>
            <<?php echo $section->title_tag ?> class="objectTitle record-title">
                <span class="contentTitleTxt"><?php echo $record->title ?></span> 
            </<?php echo $section->title_tag ?>> 
        <?php endif; ?>
        <?php if(!empty($record->image)): ?>
            <?php if(strval($section->parametrs->param8)=='Y'): ?><a class="gallery" rel="group" title="" href="<?php echo $record->image_prev ?>"><?php endif; ?>
                <div class="objimage record-image">
                    <img class="objectImage" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                </div> 
            <?php if(strval($section->parametrs->param8)=='Y'): ?></a><?php endif; ?>
        <?php endif; ?>
    <?php if(strval($section->parametrs->param3)=='Y'): ?>
        <?php if(!empty($record->note)): ?>
            <div class="objectNote record-note"><?php echo $record->note ?></div> 
        <?php endif; ?>
    <?php endif; ?>
    <?php if(!empty($record->text)): ?>
        <div class="objectText record-text"><?php echo $record->text ?></div>
    <?php endif; ?> 
    <?php if(strval($section->parametrs->param5)=="Y"): ?>
         
            <header:js>[js:jquery/jquery.min.js]</header:js>
            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                var inptxt = $(".cont_txt .contentTitleTxt").html(); 
                new Ya.share({
                    'element': 'ya_share1',
                    'elementStyle': {
                        'type': 'button',
                        'linkIcon': true,   
                        'border': false,
                        'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
                    },
                    'popupStyle': {
                        'copyPasteField': true
                    },
                        'description': inptxt,
                        onready: function(ins){
                            $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=<?php echo $section->parametrs->param6 ?>\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                    }
                });
            </script>
        
        <div id="ya_share1" style="margin: 10px 0;">
            
        </div>
    <?php endif; ?>
    <input class="buttonSend" onclick="document.location.href='<?php echo $__data->getLinkPageName() ?>';" type="button" value="<?php echo $section->parametrs->param2 ?>">
    </div>
    </div> 
