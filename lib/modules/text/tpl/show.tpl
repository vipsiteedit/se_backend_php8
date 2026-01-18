
    
        <?php if($section->parametrs->param7=='Y'): ?>
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
                    }
                });
            </script>
        <?php endif; ?>
    
    <div class="content" id="view">
        <?php if($section->parametrs->param5=='Y'): ?>
            
                <script type="text/javascript">
                    function myPrint() {
                        var text = document.getElementById('forPrint').innerHTML;
                        var printwin = document.open('', 'printwin', '');
                        printwin.document.writeln('<html>');
                        printwin.document.writeln('<body onload="window.print();close();">');
                        printwin.document.writeln("<div id='print'>" + text + "</div>");
                        printwin.document.writeln('</body></html>');
                        printwin.document.close();
                    } 
                </script>
            
            <div class="print">
                <a href="javascript:myPrint();"><?php echo $section->parametrs->param6 ?></a>
            </div>   
        <?php endif; ?>
        <div id="forPrint">
            <?php if($section->parametrs->param4=='Y'): ?>
                <a name="show<?php echo $section->id ?>_<?php echo $record->id ?>"></a>
            <?php endif; ?>
            <?php if(!empty($record->title)): ?>
                <h4 class="objectTitle">
                    <span class="contentTitleTxt"><?php echo $record->title ?></span>
                </h4>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <div id="objimage">
                    <img class="objectImage" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param3=='Y'): ?>
                <?php if(!empty($record->note)): ?>
                    <div class="objectNote"><?php echo $record->note ?></div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="objectText"><?php echo $record->text ?></div> 
        </div>
        <?php if($section->parametrs->param7=='Y'): ?>
            <div id="ya_share1" style="margin: 10px 0;">
                
            </div>
        <?php endif; ?>
        <input class="buttonSend back" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->parametrs->param2 ?>">
    </div> 
