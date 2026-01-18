<div class="content cont_golos_txt" >
<?php if(!empty($section->title)): ?><a name="<?php echo $section->id ?>"></a> <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
  <span class="contentTitleTxt"><?php echo $section->title ?></span> </<?php echo $section->title_tag ?>> <?php endif; ?>
<?php if(!empty($section->image)): ?>
   <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?><div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> <?php endif; ?>
<?php echo $__data->linkAddRecord($section->id) ?>
    <form action="<?php echo $__data->getLinkPageName() ?>" method="post" style="margin:0px;">
        <table border="0" cellpadding="3" cellspacing="0" class="object">
        <tbody class="tableBody">
        <?php if($show==0): ?> 
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

             <tr class="votingtr" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                 <td vAlign="middle" class="radiotd">
                     <input class="votingradio" type="radio" name="voting_radio" value="<?php echo $record->id ?>">
                 </td> 
                 <td vAlign="middle"  class="titletd">
                     <span class="objectItem" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                 </td>               
             </tr>
        
<?php endforeach; ?>
        <?php else: ?>
        
            <tr><td colspan=2 style="padding-left:10px;">
            <font color="<?php echo $section->parametrs->param4 ?>"><b class="restitle"><?php echo $section->parametrs->param3 ?>: <?php echo $summ ?></b></font></td></tr>
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

             <tr>
                 <td style="padding-left:10px;">
                     <font class="restext" color="<?php echo $record->field ?>"><?php echo $record->title ?></font>
                 </td>
                 <td>
                     <span style="width:30px; color:<?php echo $record->field ?>;" class="restext"><?php echo $record->res ?>%</span>
                 </td>
            </tr>
        
<?php endforeach; ?>
        <?php endif; ?>
            <tr>
                <td colspan='2' style="font-size:5px;height:5px;">&nbsp;</td> 
            </tr> 
            <tr> 
                <td colspan="2" vAlign="middle" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" class="areaButton">
                        <tr> 
                            <td>
                                <input name="GoTo_VOTING" class="buttonSend buttonVoting" 
                                    <?php echo $voting_text->buttonstyle ?> type='submit' value="<?php echo $section->parametrs->param5 ?>">
                            </td> 
                            <td>&nbsp;</td> 
                            <td>
                                <input name="GoTo_SHOW" class="buttonSend buttonResult" 
                                    <?php echo $voting_text->buttonstyle ?> type='submit' value="<?php echo $section->parametrs->param6 ?>">
                                <input type="hidden" name='razdel' value='<?php echo $section->id ?>'>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr> 
        </tbody>
    </form>
</table>
</div> 
