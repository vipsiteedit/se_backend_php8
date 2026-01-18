<table cellspacing="0" cellpadding="0">
    <?php if(strval($section->parametrs->param1)=='Y'): ?><tr><?php endif; ?>
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
       
        <?php if($record->id!=$__record_first): ?><?php if(strval($section->parametrs->param1)=='N'): ?><tr><?php endif; ?><td class="sep">&nbsp;</td><?php if(strval($section->parametrs->param1)==N): ?></tr><?php endif; ?><?php endif; ?>
        <?php if(strval($section->parametrs->param1)=='N'): ?><tr><?php endif; ?><td class="tab  <?php if($obj==0): ?><?php if("$record->id"=="$__record_first"): ?> active<?php endif; ?><?php else: ?><?php if($record->id==$obj): ?>active 2<?php endif; ?><?php endif; ?> ">
            <table cellspacing="0" cellpadding="0" width="100%" class="cell">
            <tr>
                <td class="left"></td>
                <td class="center"> 
                    <a class="link obj<?php echo $record->id ?>" <?php if(strval($section->parametrs->param2)=='N'): ?>rel="nofollow"<?php endif; ?> href="<?php echo $thispage ?>/obj/<?php echo $record->id ?>/"><?php echo $record->title ?><?php if(empty($record->title)): ?><?php echo $section->language->lang001 ?><?php endif; ?></a>
                </td>
                <td class="right"></td>
            </tr>
            </table>
        </td>
        <?php if(strval($section->parametrs->param1)=='N'): ?></tr><?php endif; ?>

<?php endforeach; ?>
    <?php if(strval($section->parametrs->param1)=='Y'): ?></tr><?php endif; ?>
</table>
