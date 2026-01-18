<div class="content openid sub2">
    <form action="" method='post'>
    <div class="error"><?php echo $er ?></div>
        <table class="tableTable">

        <?php foreach($section->dels as $del): ?>
            <tr class="tableRow">
                <td class="links">
                    <a href="<?php echo seMultiDir()."/".$_page."/" ?>?deletes_openid=<?php echo $del->id ?>&<?php echo $time ?>"><?php echo $section->parametrs->param29 ?></a>
                </td> 
                <td class="image">
                <?php if($del->photo==''): ?>
                    <img class="umg" src="[module_url]nofoto.gif">
                <?php else: ?>
                    <img class="umg" src="<?php echo $del->photo ?>">
                <?php endif; ?>   
                </td> 
                <td class="fio">
                    <span class="fio_text"><?php echo $del->fio ?></span>
                </td>
            </tr>
        
<?php endforeach; ?>


    </table>
    <div class="buttonarea">
        <input class="buttonSend delbut" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>?<?php echo $time ?>';" type="button" value="<?php echo $section->parametrs->param30 ?>">
    </div> 
    </form>    
</div>
