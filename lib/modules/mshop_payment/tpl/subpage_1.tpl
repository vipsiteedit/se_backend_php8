<div class="content payment">
    <h3 class="contentTitle"><?php echo $section->parametrs->param15 ?></h3> 
    <table border="0" cellPadding="0" cellSpacing="0" class="tableTable balans">
        <tbody class="tableBody">
            <tr>
                <td colspan=2 align="right">
                    <span id="titlebalanse"><?php echo $section->parametrs->param29 ?>:</span>
                </td>
                <td align="left">&nbsp;
                    <span id="resultbalans"><?php echo $summ_account ?></span>
                </td>
                <td colspan="2" align="right">
                    <span id="titleselect"><?php echo $section->parametrs->param30 ?></span>
                </td>
                <td>
                    <form style="margin:0px;" method="POST">
                        <select id="selectmonth" onChange="form.submit()" name="selMonth">
                            <?php foreach($section->periods as $period): ?>
                                <option value="<?php echo $period->date ?>" <?php echo $period->select ?>><?php echo $period->name ?></option>
                            
<?php endforeach; ?>
                        </select>
                    </form>
                </td>
            </tr>
            <tr class="tableRow tableHeader">
                <th class="date">
                    <span><?php echo $section->parametrs->param16 ?></span>
                </th> 
                <th class="in">
                    <span><?php echo $section->parametrs->param17 ?></span>
                </th> 
                <th class="out">
                    <span><?php echo $section->parametrs->param18 ?></span>
                </th> 
                <th class="result">
                    <span><?php echo $section->parametrs->param19 ?></span>
                </th> 
                <th class="operation">
                    <span><?php echo $section->parametrs->param20 ?></span>
                </th> 
                <th class="docum">
                    <span><?php echo $section->parametrs->param21 ?></span>
                </th> 
            </tr> 
            <?php foreach($section->pays as $record): ?> 
                <tr class="tableRow <?php echo $record->style ?>">
                    <td class="date">
                        <span><?php echo $record->date_payee ?></span>
                    </td>                           
                    <td class="in">
                        <span><?php echo $record->in_payee ?></span>
                    </td> 
                    <td class="out">
                        <span><?php echo $record->out_payee ?></span>
                    </td> 
                    <td class="result">
                        <span><?php echo $record->result ?></span>
                    </td> 
                    <td class="operation">
                        <span><?php echo $record->operation ?></span>
                    </td> 
                    <td class="docum">
                        <span><?php echo $record->docum ?></span>
                    </td> 
                </tr> 
            
<?php endforeach; ?> 
        </tbody>
    </table>
    <div class="buttonArea balansBtn">
        <input class="buttonSend" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->parametrs->param14 ?>">
    </div>
</div> 

