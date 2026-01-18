<!-- Subpage 11. Спецификация -->
<table border="0" class="tableTable tablePrice" cellSpacing="0" cellPadding="0">
    <tr class="tableRow tableHeader" valign="top">
        <th class="spectitle">Спецификация</th>
        <th class="specvalue">Значения</th>
    </tr>
    <?php foreach($section->specifications as $spec): ?>
    <tr class="tableRow <?php echo $spec->style ?>">
        <td class="spectitle"><?php echo $spec->title ?></td>
        <td class="specvalue"><?php echo $spec->value ?></td>
    </tr>
    
<?php endforeach; ?>
</table>
