<header:js>
[js:jquery/jquery.min.js]
    <script type="text/javascript">
    <!--       
        function list(val){               
                $('.list ul').toggle();    
               
        }
    -->
    </script>
</header:js>
<div class="content notime cont_sub1">
    <div class="list">
                <span class="othertxt" onclick="list()"><?php echo $section->language->lang007 ?></span>

                <ul>
                    <?php foreach($section->subscribes as $subscr): ?>
                        <li>
                            <noindex>
                                <a class="scrlink" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>id/<?php echo $subscr->id_subscribe ?>/" rel="nofollow"><?php echo $subscr->id_subscribe ?></a>
                            </noindex>
                        </li>
                    
<?php endforeach; ?>
                </ul>
                
        
    </div>
    <?php echo $MANYPAGE ?>
    <table class="tables">
        <tr class="tableRow">
            <th class="tableHeader"><span>ФИО</span></th>
            <th class="tableHeader"><span>Электронная почта</span></th>
            <th class="tableHeader"><span>Телефон</span></th>
        </tr>
    
    <?php foreach($section->scribers as $subs): ?>
        <tr class="tableRow">
            <td class="lname"><span><?php echo $subs->l_name ?>&nbsp;<?php echo $subs->f_name ?>&nbsp;<?php echo $subs->s_name ?></span></td>
            <td class="email"><span><?php echo $subs->email ?></span></td>
            <td class="phone"><span><?php echo $subs->phone ?></span></td>
        </tr>
    
<?php endforeach; ?>
    
    
    </table>
    <form>
        <div class="but_area">
            <input type="hidden" value="<?php echo $id_subsc ?>" name="ide">
            <input class="buttonSend save" type="submit" value="<?php echo $section->parametrs->param15 ?>" name="saves">
            <input class="buttonSend back" type="button" value="<?php echo $section->parametrs->param16 ?>" onClick="window.history.back(-1);">
        </div>
    </form>
</div>
