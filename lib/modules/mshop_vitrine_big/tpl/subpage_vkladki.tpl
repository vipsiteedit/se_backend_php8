        <?php if($section->parametrs->param258!='N'): ?>                    < Использовать режим Вкладки >
            <div style="display: inline-block; width:100%;" id="tabs-good">
                <ul>
                    <?php if($section->parametrs->param134!='N'): ?>        < Отображать Подробное описание >
                        <li><a href="#tabs-text"><?php echo $section->parametrs->param256 ?></a></li>
                    <?php endif; ?>
                    <?php if($section->parametrs->param271!='N'): ?>        < Отображать спецификацию >
                        <li><a href="#tabs-spec"><?php echo $section->parametrs->param270 ?></a></li>
                    <?php endif; ?>
                </ul>
        <?php endif; ?>                           
        <?php if($section->parametrs->param134!='N'): ?>                    < Отображать Подробное описание >
            <?php if($section->parametrs->param258!='N'): ?>                < Использовать режим Вкладки >
                <div id="tabs-text" style="max-height:500px; overflow:auto;">
            <?php else: ?>
                <div class="goodsDetText">
            <?php endif; ?>
            <?php echo $price_fields_text ?>
            </div>
            <?php endif; ?>
            <?php if($section->parametrs->param271!='N'): ?>                < Отображать спецификацию >
                <?php if($section->parametrs->param258!='N'): ?>            < Использовать режим Вкладки >
                    <div id="tabs-spec" style="max-height:500px; overflow:auto;">
                <?php else: ?>
                    <div class="goodsDetSpec">
                <?php endif; ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_11.php")) include $__MDL_ROOT."/php/subpage_11.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_11.tpl")) include $__MDL_ROOT."/tpl/subpage_11.tpl"; ?>
                    </div>
            <?php endif; ?>
            <?php if($section->parametrs->param258!='N'): ?>                < Использовать режим Вкладки >
            </div>
                    <script type="text/javascript">
                    <
                    var $tabs = $( "#tabs-good" ).tabs();
                    $tabs.tabs('select', 0);
                    >
                    </script>
            <?php endif; ?>
