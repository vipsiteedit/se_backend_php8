<div id='info'>
    <?php echo $section->language->lang134 ?>&nbsp;
    <b id='curdate'><?php echo $curday ?>&nbsp;<?php echo $curmonth ?>,&nbsp;<?php echo $curyear ?>&nbsp;<?php echo $section->language->lang135 ?></b>,
    &nbsp;<?php echo $section->language->lang136 ?>&nbsp;<b id='curtime'><?php echo $curtime ?></b>,
    &nbsp;<?php echo $section->language->lang137 ?>&nbsp;<b id='curuser'><?php echo $nick ?></b>
</div>
<div id='menu'>              
    <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/" ?>' id='forumname'><?php echo $nameForum ?></a>
    <?php if($uid!=0): ?>
        <a class='menuitem' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>?forums[]=all&result_type=topics&user=&text=&time=<?php echo $lastVisit ?>&new" id='newmessage'><?php echo $section->language->lang138 ?></a>
        <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub5/" ?>' id='my'><?php echo $section->language->lang026 ?></a>
        <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>' id='users'><?php echo $section->language->lang139 ?></a>
    <?php endif; ?>
    <a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub9/" ?>' id='search'><?php echo $section->language->lang009 ?></a>
</div>

