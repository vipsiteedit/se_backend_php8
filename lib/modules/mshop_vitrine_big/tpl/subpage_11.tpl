<table border="0" class="tableTable tablePrice" cellSpacing="0" cellPadding="0">
    <tr class="tableRow tableHeader" valign="top">
        <th class="spectitle"><?php echo $section->language->lang001 ?></th>
        <th class="specvalue"><?php echo $section->language->lang002 ?></th>
    </tr>
    <?php foreach($section->specifications as $spec): ?>
    <tr class="tableRow <?php echo $spec->style ?>">
        <td class="spectitle"><?php echo $spec->title ?></td>
        <td class="specvalue"><?php echo $spec->value ?></td>
    </tr>
    
<?php endforeach; ?>
</table>
