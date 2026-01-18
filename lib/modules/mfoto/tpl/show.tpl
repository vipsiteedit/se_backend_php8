
        <div id="view">
            <div class="content cont_photo photoDetailed record-item" itemscope itemtype="http://schema.org/ImageObject" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                <?php if(!empty($record->title)): ?>
                    <<?php echo $section->title_tag ?> class="objectTitle record-title">
                        <span class="objectTitleTxt" itemprop="name"><?php echo $record->title ?></span>
                    </<?php echo $section->title_tag ?>>
                <?php endif; ?>
                <?php if(!empty($record->image)): ?>
                    <div class="objectImageBlock record-image" id="objimage">
                        <img class="objectImage" title="<?php echo $record->image_title ?>" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                    </div>
                <?php endif; ?>
                <?php if(!empty($record->note)): ?>
                    <div class="objectNote record-note" itemprop="description"><?php echo $record->note ?></div>
                <?php endif; ?>
                <?php if(!empty($record->text)): ?>
                    <div class="objectText record-text" itemprop="description"><?php echo $record->text ?></div>
                <?php endif; ?>
                <?php if(strval($section->parametrs->param3)=="Y"): ?>
                    <div style="display:none">
                        <input type="text" id="inptxt" name="inptxt" value="<?php echo $record->text ?>">
                    </div>
                    <script>
                        var inptxt = document.getElementById('inptxt').value;
                    </script>
                    
                        <header:js>[js:jquery/jquery.min.js]</header:js>
                        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
                        <script type="text/javascript">
                            new Ya.share({
                                'element': 'ya_share1',
                                'elementStyle': {
                                    'type': 'button',
                                    'linkIcon': true,   //
                                    'border': false,
                                    'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
                                },
                                'popupStyle': {
                                    'copyPasteField': true
                                },
                                'title': '<?php echo $record->title ?>',
                                'description': inptxt,
                                onready: function(ins){
                                    $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=<?php echo $section->parametrs->param4 ?>\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                                }
                            });
                        </script>
                    
                    <div id="ya_share1" style="margin: 10px 0;">
                        
                    </div>
                <?php endif; ?>        
                <input class="buttonSend" onclick="document.location='<?php echo $__data->getLinkPageName() ?>';" type="button" value="<?php echo $section->parametrs->param2 ?>">
            </div> 
        </div>
